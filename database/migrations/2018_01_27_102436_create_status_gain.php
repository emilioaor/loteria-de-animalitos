<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Ticket;

class CreateStatusGain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * | La funcion de esta migracion es setear un nuevo estatus, "STATUS_GAIN",
         * | esto es para evitar que las consultas donde se comprueban los tickets
         * | ganadores tarden tanto. En lugar de calcular si es ganador simplemente
         * | se va a verificar su estatus.
         * |------------------------------------------------------------------------
         */

        $tickets = Ticket::all();

        foreach ($tickets as $ticket) {
            if ($this->ticketIsGain($ticket) && $ticket->status === Ticket::STATUS_ACTIVE) {
                // Si el ticket es ganador cambio su estatus
                $ticket->status = Ticket::STATUS_GAIN;
                $ticket->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tickets = Ticket::all();

        foreach ($tickets as $ticket) {
            if ($ticket->status === Ticket::STATUS_GAIN) {
                // Si el ticket es ganador cambio su estatus
                $ticket->status = Ticket::STATUS_ACTIVE;
                $ticket->save();
            }
        }
    }

    /**
     * Indica si un ticket es ganador
     *
     * @param Ticket $ticket
     * @return bool
     */
    private function ticketIsGain(Ticket $ticket) {
        foreach ($ticket->dailySorts as $dailySort) {

            $result = $dailySort->getResultToDate($ticket->created_at);

            if (! $result) {
                continue;
            }

            foreach ($ticket->animals as $animal) {
                if ($animal->number === $result->animal->number) {
                    return true;
                }
            }
        }

        return false;
    }
}
