<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailySort extends Model
{
    /** Estatus de sorteos */
    const STATUS_ACTIVE = 'Activo';
    const STATUS_CLOSE = 'Cerrado';

    protected $table = 'daily_sort';

    protected $fillable = [
        'sort_id', 'date_sort', 'status'
    ];

    /**
     * Retorna todos los tickets registrados a este sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets() {
        return $this->hasMany('App\Ticket', 'daily_sort_id');
    }

    /**
     * Retorna el sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sort() {
        return $this->belongsTo('App\Sort', 'sort_id');
    }

    /**
     * Retorna el resultado del sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function result() {
        return $this->hasOne('App\Result', 'daily_sort_id');
    }

    /**
     * Retorna fecha en DateTime
     *
     * @return \DateTime
     */
    public function getDateSort() {
        return \DateTime::createFromFormat('Y-m-d H:i:s', $this->date_sort);
    }

    /**
     * Retorna el total jugado en todos los tickets
     * asociados a este sorteo
     *
     * @return int
     */
    public function totalTickets() {
        $total = 0;
        foreach ($this->tickets as $ticket) {
            $total += $ticket->amount();
        }

        return $total;
    }
}
