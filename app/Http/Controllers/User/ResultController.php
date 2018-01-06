<?php

namespace App\Http\Controllers\User;

use App\Animal;
use App\DailySort;
use App\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $animals = Animal::all();
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
            'animals' => $animals,
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

        if (! ($result = $dailySort->getResultToDate($date))) {
            $result = new Result();
            $result->daily_sort_id = $dailySortId;
        }

        $result->animal_id = $request->animal_id;
        $result->created_at = $date;
        $result->save();

        $this->sessionMessages('Resultado guardado');

        return redirect($this->getRedirectUrl('results.index'));
    }
}
