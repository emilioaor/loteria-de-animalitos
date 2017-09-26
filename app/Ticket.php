<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    const STATUS_ACTIVE = 'Activo';
    const STATUS_PAY = 'Pago';

    protected $table = 'tickets';

    protected $fillable = [
        'public_id','date','user_id','daily_sort_id'
    ];

    /**
     * Retorna el usuario que genero el ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Retorna todos los animales jugados en este ticket
     *
     * @return $this
     */
    public function animals()
    {
        return $this->belongsToMany('App\Animal', 'animal_ticket')->withPivot([
           'ticket_id', 'animal_id', 'amount'
        ]);
    }

    /**
     * Retorna el sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dailySort() {
        return $this->belongsTo('App\DailySort', 'daily_sort_id');
    }

    /**
     * Retorna todas las lineas en cola de impresion para
     * este ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function printSpooler() {
        return $this->hasMany('App\PrintSpooler', 'ticket_id');
    }

    /**
     * Retorna el monto jugado en este ticket
     *
     * @return int
     */
    public function amount() {
        $amount = 0;
        foreach ($this->animals as $animal) {
            $amount += $animal->pivot->amount;
        }

        return $amount;
    }

    /**
     * Indica si un ticket es ganador
     *
     * @return bool
     */
    public function isGain() {
        $result = $this->dailySort->result;

        if (! $result) {
            return false;
        }

        foreach ($this->animals as $animal) {
            if ($animal->id === $result->animal->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Indica la cantidad que se le debe pagar al ganador
     * del ticket
     *
     * @return float|int
     */
    public function payToGain() {
        $animalGain = $this->dailySort->result->animal;
        $amount = 0;
        foreach ($this->animals as $animal) {
            if ($animal->id === $animalGain->id) {
                $amount = $animal->pivot->amount;
                $div = $amount /100;
                $amount = $div * $this->dailySort->sort->pay_per_100;
            }
        }

        return $amount;
    }
}
