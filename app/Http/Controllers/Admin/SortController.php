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
        $sorts = DailySort::orderBy('sort_id')->orderBy('time_sort')->get();

        return view('admin.sort.index')->with(['sorts' => $sorts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sorts = Sort::all();

        return view('admin.sort.create', ['sorts' => $sorts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sort = new DailySort($request->all());
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
        $sort = DailySort::findOrFail($id);
        $sorts = Sort::all();

        return view('admin.sort.edit')->with([
            'sort' => $sort,
            'sorts' => $sorts,
        ]);
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
        $sort = DailySort::findOrFail($id);
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
}
