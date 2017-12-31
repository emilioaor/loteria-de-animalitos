<?php

namespace App\Http\Controllers\User;

use App\Animal;
use App\DailySort;
use App\PrintSpooler;
use App\Sort;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;

/**
 * Maneja las rutas principales de la taquilla
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 */
class IndexController extends Controller
{

    /**
     * Carga la vista principal de la taquilla
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $sorts = DailySort::orderBy('time_sort')->get();
        $activeSorts = [];

        // Filtro solo los sorteos activos
        foreach ($sorts as $sort) {
            if ($sort->hasActive()) {
                $activeSorts[] = $sort;
            }
        }

        $animals = Animal::all();
        $animals = $this->getDailyLimit($animals);

        return view('user.create')->with([
            'sorts' => $activeSorts,
            'animals' => $animals,

        ]);
    }


    /**
     * Registra un ticket
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        DB::beginTransaction();

            if (empty($request->sorts)) {
                DB::rollback();
                $this->sessionMessages('Debe seleccionar al menos un sorteo', 'alert-danger');

                return redirect()->route('user.index');
            }

            foreach ($request->sorts as $id => $ds) {
                $dailySort = DailySort::find($id);

                if (! $dailySort->hasActive()) {
                    DB::rollback();
                    $this->sessionMessages('Este ticket posee sorteos cerrados', 'alert-danger');

                    return redirect()->route('user.index');
                }
            }

            $ticket = new Ticket();

            $ticket->public_id = 'TICK00' . (Ticket::all()->count() + 1);
            $ticket->user_id = Auth::user()->id;
            $ticket->status = Ticket::STATUS_ACTIVE;
            $ticket->pay_per_100 = $dailySort->sort->pay_per_100;
            $ticket->save();
            $ticket->dailySorts()->sync(array_keys($request->sorts));

            $amounts = $request->amounts;

            foreach ($request->animals as $index => $animalId) {
                if (! empty($amounts[$index])) {
                    $ticket->animals()->attach($animalId, [ 'amount' => $amounts[$index] ]);
                }
            }

            $printSpooler = new PrintSpooler();
            $printSpooler->ticket_id = $ticket->id;
            $printSpooler->status = PrintSpooler::STATUS_PENDING;
            $printSpooler->save();

        DB::commit();

        $this->sessionMessages('Ticket registrado');

        return redirect()->route('user.index');
    }

    /**
     * Carga una vista con la lista de tickets
     *
     * @param Request $request
     * @return $this
     */
    public function listTicket(Request $request) {
        $tickets = Ticket::orderBy('created_at', 'DESC');

        if (Auth::user()->level === User::LEVEL_USER) {
            $tickets->where('user_id', Auth::user()->id);
        }

        if (! empty($request->search)) {
            $search = $request->search;
            $search = '%' . str_replace(' ', '%', $search) . '%';
            $tickets->where('public_id', 'LIKE', $search);
        }

        return view('user.index')->with(['tickets' => $tickets->paginate(20)]);
    }

    /**
     * Carga el detalle de un ticket
     *
     * @param $id
     * @return $this
     */
    public function show($id) {
        $ticket = Ticket::findOrFail($id);

        return view('user.show')->with(['ticket' => $ticket]);
    }

    /**
     * Marca un ticket como pago
     *
     * @param $ticketId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payTicket($ticketId) {
        $ticket = Ticket::find($ticketId);

        if (! $ticket->allSortClosed()) {
            $this->sessionMessages('Deben cerrar todos los sorteos asociados al ticket' ,'alert-danger');

            return redirect()->route('user.show', ['ticket' => $ticketId]);
        }

        $ticket->status = Ticket::STATUS_PAY;
        $ticket->save();

        $this->sessionMessages('Ticket pago');

        return redirect()->route('user.show', ['ticket' => $ticketId]);
    }

    /**
     * Anula un ticket
     *
     * @param $ticketId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function nullTicket($ticketId) {
        $ticket = Ticket::find($ticketId);
        $ticket->status = Ticket::STATUS_NULL;
        $ticket->save();

        $this->sessionMessages('Ticket anulado');

        return redirect()->route('user.show', ['ticket' => $ticketId]);
    }

    /**
     * Obtiene el limite disponible para cada animalito
     *
     * @param $animals
     * @return array
     */
    private function getDailyLimit($animals)
    {
        $start = (new \DateTime())->setTime(0, 0, 0);
        $end = (new \DateTime())->setTime(23, 59, 59);
        // Obtengo todos los tickets de hoy
        $tickets = Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->get();

        foreach ($animals as $animal) {
            if (! isset($animal->limit)) {
                $animal->limit = floatval($animal->sort->daily_limit);
            }

            foreach ($tickets as $ticket) {
                foreach ($ticket->animals as $ticketAnimal) {
                    if ($ticketAnimal->id === $animal->id) {
                        $animal->limit -= $ticketAnimal->pivot->amount * count($ticket->dailySorts);
                    }
                }
            }

        }

        return $animals;
    }
}
