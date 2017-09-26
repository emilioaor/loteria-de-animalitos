<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrintSpooler extends Model
{
    /** Estatus */
    const STATUS_PENDING = 'Pendiente';
    const STATUS_COMPLETE = 'Completo';

    protected $table = 'print_spooler';

    protected $fillable = [
        'ticket_id', 'status',
    ];

    /**
     * Retorna el ticket que se imprime en esta cola de impresion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket() {
        return $this->belongsTo('App\Ticket', 'ticket_id');
    }
}
