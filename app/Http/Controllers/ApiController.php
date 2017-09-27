<?php

namespace App\Http\Controllers;

use App\PrintSpooler;
use App\User;
use App\Ticket;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{

    /**
     * Obtiene el ticket en texto plano para imprimir
     *
     * @param $ticketCode
     * @return $this
     */
    public function getTicketText($ticketCode) {
        $ticketId = explode('-', $ticketCode);

        if ($ticketId[0] == 'ANI') {
            $ticket = Ticket::find($ticketId[1]);

            return view('text.ticket')->with(['ticket' => $ticket]);
        }

        return new Response(null, 400);
    }


    /**
     * Obtiene todos los tickets en cola de impresion
     * para una taquilla especifica
     *
     * @param $printCode
     * @return JsonResponse
     */
    public function getPrintSpooler($printCode) {

        $user = User::where('print_code', $printCode)->first();

        $pendingTickets = PrintSpooler::select('print_spooler.id')
            ->join('tickets', 'tickets.id', '=', 'ticket_id')
            ->where('user_id', $user ? $user->id : null)
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

            $response .= 'ANI-' . $printSpooler->ticket_id . "\n";
        }

        return $response;
    }
}
