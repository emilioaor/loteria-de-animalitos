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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $dailySorts = DailySort::orderBy('sort_id')->orderBy('time_sort')->get();
        $animals = Animal::all();

        return view('user.result.index')->with([
            'dailySorts' => $dailySorts,
            'animals' => $animals,
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

        if (! ($result = $dailySort->getResultToday())) {
            $result = new Result();
            $result->daily_sort_id = $dailySortId;
        }

        $result->animal_id = $request->animal_id;
        $result->save();

        $this->sessionMessages('Resultado guardado');

        return redirect()->route('results.index');
    }
}
