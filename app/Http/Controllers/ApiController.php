<?php

namespace App\Http\Controllers;

use App\PrintSpooler;
use Illuminate\Http\Request;
use App\Ticket;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{

    /**
     * Obtiene el ticket en texto plano para imprimir
     *
     * @param $ticketId
     * @return $this
     */
    public function getTicketText($ticketId) {
        $ticket = Ticket::find($ticketId);

        return view('text.ticket')->with(['ticket' => $ticket]);
    }


    /**
     * Obtiene todos los tickets en cola de impresion
     * para una taquilla especifica
     *
     * @param $user
     * @return JsonResponse
     */
    public function getPrintSpooler($user) {

        $pendingTickets = PrintSpooler::select('print_spooler.id')
            ->join('tickets', 'tickets.id', '=', 'ticket_id')
            ->where('user_id', $user)
            ->where('print_spooler.status', PrintSpooler::STATUS_PENDING)
            ->get()
        ;

        $response = null;

        foreach ($pendingTickets as $id) {

            if (is_null($response)) {
                $response = '';
            }

            $printSpooler = PrintSpooler::find($id['id']);
            $printSpooler->status = PrintSpooler::STATUS_COMPLETE;
            $printSpooler->save();

            $response .= $printSpooler->ticket_id . "\n";
        }

        return $response;
    }
}
