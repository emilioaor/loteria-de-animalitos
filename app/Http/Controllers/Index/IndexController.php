<?php

namespace App\Http\Controllers\Index;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\AuthRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Maneja todas las rutas que no requieren autenticacion
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 */
class IndexController extends Controller
{

    /**
     * Carga la vista de login
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login() {
        return view('index.login');
    }

    /**
     * Autentica al usuario
     *
     * @param AuthRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function loginUser(AuthRequest $request) {

        if (! Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $this->sessionMessages('Usuario o contraseña incorrectos', 'alert-danger');

            return redirect()->route('index.login');
        }

        if (Auth::user()->level === User::LEVEL_ADMIN) {
            return redirect()->route('user.list');
        }

        return redirect()->route('user.index');
    }

    /**
     * Cierra la sesión del usuario
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout() {
        Auth::logout();

        return redirect()->route('index.login');
    }
}
