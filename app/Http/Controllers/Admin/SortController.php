<?php

namespace App\Http\Controllers\Admin;

use App\DailySort;
use App\Sort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SortController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sorts = Sort::orderBy('time_sort')->get();

        return view('admin.sort.index')->with(['sorts' => $sorts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sort.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sort = new Sort($request->all());
        $sort->save();

        $this->sessionMessages('Sorteo registrado');

        return redirect()->route('sorts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sort = Sort::findOrFail($id);

        return view('admin.sort.edit')->with(['sort' => $sort]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sort = Sort::findOrFail($id);
        $sort->update($request->all());

        $this->sessionMessages('Sorteo actualizado');

        return redirect()->route('sorts.edit', ['sort' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Crea un sorteo diario para la fecha actual
     *
     * @param $sortId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upSort($sortId) {
        $sort = Sort::find($sortId);
        $dailySort = $sort->getLastDailySort();

        if (! $sort->hasDailySortToday()) {
            $dailySort = new DailySort();
            $dailySort->date_sort = new \DateTime('now');
            $dailySort->sort_id = $sortId;
        }

        $dailySort->status = DailySort::STATUS_ACTIVE;
        $dailySort->save();

        $this->sessionMessages('Sorteo activado para la fecha: ' . $dailySort->getDateSort()->format('d-m-Y'));

        return redirect()->route('sorts.edit', ['sort' => $sortId]);
    }

    /**
     * Habilita todos los sorteos
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upAll() {
        DB::beginTransaction();

        $sorts = Sort::all();

        foreach ($sorts as $sort) {

            $dailySort = $sort->getLastDailySort();

            if (! $dailySort) {
                //  Se registra un sorteo para el dia
                $dailySort = new DailySort();
                $dailySort->date_sort = new \DateTime('now');
                $dailySort->sort_id = $sort->id;
            }

            $dailySort->status = DailySort::STATUS_ACTIVE;
            $dailySort->save();

        }

        DB::commit();

        $this->sessionMessages('Sorteos habilitados');

        return redirect()->route('sorts.index');
    }

    /**
     * Cierra un sorteo diario
     *
     * @param $sortId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downSort($sortId) {
        $sort = Sort::find($sortId);

        $dailySort = $sort->getLastDailySort();

        if ($dailySort) {
            $dailySort->status = DailySort::STATUS_CLOSE;
            $dailySort->save();
        }

        $this->sessionMessages('Sorteo cerrado para la fecha: ' . $dailySort->getDateSort()->format('d-m-Y'));

        return redirect()->route('sorts.edit', ['sort' => $sortId]);
    }

    /**
     * Cierra todos los sorteos
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downAll() {
        DB::beginTransaction();

        $sorts = Sort::all();

        foreach ($sorts as $sort) {

            $dailySort = $sort->getLastDailySort();

            if ($dailySort) {
                //  Se cierra el sorteo del dia
                $dailySort->status = DailySort::STATUS_CLOSE;
                $dailySort->save();
            }
        }

        DB::commit();

        $this->sessionMessages('Sorteos cerrados');

        return redirect()->route('sorts.index');
    }
}
