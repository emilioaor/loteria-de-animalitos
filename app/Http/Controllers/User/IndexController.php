<?php

namespace App\Http\Controllers\User;

use App\DailySort;
use App\PrintSpooler;
use App\Result;
use App\Sort;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $sorts = Sort::all();
        $activeSorts = [];
        $seconds = 0;
        $now = new \DateTime();

        foreach ($sorts as $sort) {
            $dailySorts = $sort->dailySorts()->orderBy('time_sort')->get();

            // Filtro solo los sorteos activos
            foreach ($dailySorts as $ds) {
                if ($ds->hasActive()) {
                    if (! isset($activeSorts[$sort->id])) {
                        $activeSorts[$sort->id] = [];
                    }

                    $activeSorts[$sort->id][] = $ds;

                    if ($seconds === 0) {
                        // Guardo los segundos restantes para el primer sorteo
                        $tenMinuteLess = $ds->getTimeSort()->modify('-10 minutes');
                        $seconds = ($now->diff($tenMinuteLess)->h * 3600) + ($now->diff($tenMinuteLess)->i * 60) + ($now->diff($tenMinuteLess)->s);
                    }
                }
            }
        }

        $animals = $sorts[0]->animals;
        $animals = $this->getDailyLimit($animals);

        return view('user.create')->with([
            'sorts' => $activeSorts,
            'animals' => $animals,
            'seconds' => $seconds,
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

        if (! empty($request->status)) {
            $tickets->where('status', $request->status);
        }

        return view('user.index')->with([
            'tickets' => $tickets->paginate(20)->setPath(
                route('user.list', [
                    'search' => isset($request->search) ? $request->search : null,
                    'status' => isset($request->status) ? $request->status : null,
                ])
            ),
        ]);
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

        if (! $ticket->allSortResult()) {
            $this->sessionMessages('Se debe asignar resultado a todos los sorteos' ,'alert-danger');

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
        // Obtengo todos los tickets de hoy
        $start = (new \DateTime())->setTime(0, 0, 0);
        $end = (new \DateTime())->setTime(23, 59, 59);
        $tickets = Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->get();

        // Obtengo los resultados de la ultima semana
        $startResult = clone $start;
        $startResult->modify('-7 days');
        $endResult = clone $end;

        $results = Result::where('created_at', '>=', $startResult)->where('created_at', '<=', $endResult)->get();

        // Recorro todos los animalitos
        foreach ($animals as $animal) {
            $resultFlag = false;

            // Recorro todos los resultados para saber si ya ha salido este animal
            foreach ($results as $result) {
                if ($result->animal->number === $animal->number) {
                    $resultFlag = true;
                }
            }

            // Verifico que no este definido el limite para inicializarlo
            if (! isset($animal->limit)) {
                if ($resultFlag) {
                    // Si el animalito ya salio esta semana, le inicializo el limite del sorteo
                    $animal->limit = floatval($animal->sort->daily_limit);
                } else {
                    // Si no ha salido esta semana, se asigna el limite configurado para estos casos
                    $animal->limit = ($weekLimit = floatval($animal->sort->week_limit)) ?
                        $weekLimit :
                        0;
                }
            }

            // Al limite inicializado le descuento todas las ventas de hoy
            foreach ($tickets as $ticket) {
                foreach ($ticket->animals as $ticketAnimal) {
                    if ($ticketAnimal->number === $animal->number) {
                        $animal->limit -= $ticketAnimal->pivot->amount * count($ticket->dailySorts);
                    }
                }
            }

        }

        return $animals;
    }

    /**
     * Imprime un ticket
     *
     * @param $ticketId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function printTicket($ticketId)
    {
        $ticket = Ticket::find($ticketId);

        $printSpooler = new PrintSpooler();
        $printSpooler->ticket_id = $ticket->id;
        $printSpooler->status = PrintSpooler::STATUS_PENDING;
        $printSpooler->save();

        $this->sessionMessages('Ticket a cola de impresion');

        return redirect()->route('user.show', ['ticket' => $ticketId]);
    }

    /**
     * Retorna un json de los ultimos tickets registrados
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getLastTickets(Request $request)
    {
        $data = [];
        $tickets = Ticket::orderByDesc('id')->with('animals')->limit(10);

        if (! empty($request->search)) {
            $search = $request->search;
            $search = '%' . str_replace(' ', '%', $search) . '%';
            $tickets->where('public_id', 'LIKE', $search);
        }

        foreach ($tickets->get() as $ticket) {
            $data[] = $ticket;
        }

        return new JsonResponse($data);
    }
}
