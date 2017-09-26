<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TicketOfficeRequest;
use App\Http\Requests\Admin\TicketOfficeUpdateRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Este controlador maneja el CRUD para la tabla usuarios
 * ya que cada usuario pasa a ser una taquilla de venta
 * dentro del sistema
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * Class TicketOfficeController
 * @package App\Http\Controllers\Admin
 */
class TicketOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('level', User::LEVEL_USER)
            ->orderBy('id', 'DESC')
            ->get()
        ;

        return view('admin.ticketOffice.index')->with(['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ticketOffice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TicketOfficeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketOfficeRequest $request)
    {
        $user = new User($request->all());
        $user->level = User::LEVEL_USER;
        $user->password = bcrypt($request->password);
        $user->save();

        $this->sessionMessages('Taquilla registrada');

        return redirect()->route('ticketOffice.index');
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
        $user = User::find($id);

        return view('admin.ticketOffice.edit')->with(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TicketOfficeUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketOfficeUpdateRequest $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;

        if (!empty($request->password) && !empty($request->password_confirmation)) {
            if ($request->password !== $request->password_confirmation) {
                $this->sessionMessages('Las contraseñas no coinciden', 'alert-danger');

                return redirect()->route('ticketOffice.edit', ['ticketOffice' => $id]);
            }

            $user->password = bcrypt($request->password);
        }

        $user->save();

        $this->sessionMessages('Taquilla actualizada');

        return redirect()->route('ticketOffice.edit', ['ticketOffice' => $id]);
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
