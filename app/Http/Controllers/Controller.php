<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Genera una session para mostrar una alerta
     * en el sistema
     *
     * @param $message
     * @param string $type
     */
    public function sessionMessages($message, $type = 'alert-success') {
        Session::flash('alert-message', $message);
        Session::flash('alert-type', $type);
    }
}
