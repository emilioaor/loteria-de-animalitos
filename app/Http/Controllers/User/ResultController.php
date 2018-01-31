<?php

namespace App\Http\Controllers\User;

use App\DailySort;
use App\Result;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{

    /**
     * Carga la vista con los resultados
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $dailySorts = DailySort::orderBy('sort_id')->orderBy('time_sort')->get();
        $now = new \DateTime();

        if (! $request->has('date')) {
            $date = $now;
        } else {

            $date = new \DateTime(is_array($request->date) ? $request->date['date'] : $request->date);

            if ($date > $now) {
                $date = $now;
            }
        }

        return view('user.result.index')->with([
            'dailySorts' => $dailySorts,
            'date' => $date,
            'now' => $now,
        ]);
    }

    /**
     * Establece el animalito ganador a un sorteo
     *
     * @param $dailySortId
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function animalGain($dailySortId, Request $request) {
        $dailySort = DailySort::find($dailySortId);
        $date = new \DateTime($request->date_sort);

        DB::beginTransaction();

        if ($result = $dailySort->getResultToDate($date)) {
            // Si ya posee un ganador este sorteo, paso los tickets a "activo" nuevamente
            $tickets = $dailySort->getTicketsToDate($date);

            foreach ($tickets as $ticket) {
                if ($ticket->isGain()) {
                    $ticket->status = Ticket::STATUS_ACTIVE;
                    $ticket->save();
                }
            }
        }

        if (! $result) {
            // Si no tiene resultado este sorteo, inicializo uno
            $result = new Result();
            $result->daily_sort_id = $dailySortId;
        }
        $result->animal_id = $request->animal_id;
        $result->created_at = $date;
        $result->save();

        $tickets = isset($tickets) ? $tickets : $dailySort->getTicketsToDate($date);

        foreach ($tickets as $ticket) {
            // Seteo los tickets ganadores
            if ($ticket->ticketIsGain()) {
                $ticket->status = Ticket::STATUS_GAIN;
                $ticket->save();
            }
        }

        DB::commit();

        $this->sessionMessages('Resultado guardado');

        return redirect($this->getRedirectUrl('results.index'));
    }
}
