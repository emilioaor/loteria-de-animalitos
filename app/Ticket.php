<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    const STATUS_ACTIVE = 'Activo';
    const STATUS_PAY = 'Pago';
    const STATUS_NULL = 'Anulado';
    const STATUS_GAIN = 'Ganador';

    protected $table = 'tickets';

    protected $fillable = [
        'public_id','date','user_id', 'pay_per_100',
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
    public function dailySorts() {
        return $this->belongsToMany('App\DailySort', 'ticket_sort')->withPivot([
            'ticket_id', 'daily_sort_id',
        ]);
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

        $amount = $amount * count($this->dailySorts);

        return $amount;
    }

    /**
     * Indica si un ticket es ganador
     *
     * @return bool
     */
    public function isGain() {
        return $this->status === self::STATUS_GAIN;
    }

    /**
     * Indica si un ticket esta pago
     *
     * @return bool
     */
    public function isPay() {
        return $this->status === self::STATUS_PAY;
    }

    /**
     * Indica si un ticket esta anulado
     *
     * @return bool
     */
    public function isNull()
    {
        return $this->status === self::STATUS_NULL;
    }

    /**
     * Evalua si un ticket es ganador. Este codigo se debe usar cuando se
     * esta estableciendo el resultado, en cualquier otro caso probablemente
     * deba usar isGain() ya que no es necesario hacer esta consulta
     *
     * @return bool
     */
    public function ticketIsGain() {
        if ($this->isNull()) {
            return false;
        }

        foreach ($this->dailySorts as $dailySort) {

            $result = $dailySort->getResultToDate($this->created_at);

            if (! $result) {
                continue;
            }

            foreach ($this->animals as $animal) {
                if ($animal->number === $result->animal->number) {
                    return true;
                }
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
        $total = 0;
        foreach ($this->dailySorts as $dailySort) {

            if (! ($result = $dailySort->getResultToDate($this->created_at))) {
                continue;
            }

            $animalGain = $result->animal;
            $amount = 0;
            foreach ($this->animals as $animal) {
                if ($animal->number === $animalGain->number) {
                    $amount = $animal->pivot->amount;
                    $div = $amount /100;
                    $amount = $div * $this->pay_per_100;
                }
            }

            $total += $amount;
        }

        return $total;
    }

    /**
     * Indica si ya cerraron todos los sorteos
     * asociados a este ticket
     *
     * @return bool
     */
    public function allSortClosed()
    {
        $now = new \DateTime();

        foreach ($this->dailySorts as $dailySort) {
            $dateFormat = $this->created_at->format('Y-m-d') . ' ' . $dailySort->time_sort;
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateFormat);

            if ($date > $now) {
                return false;
            }
        }

        return true;
    }

    /**
     * Indica si ya se configuro resultado a todos los sorteos
     * asociados a este ticket
     *
     * @return bool
     */
    public function allSortResult()
    {
        foreach ($this->dailySorts as $dailySort) {
            $result = $dailySort->getResultToDate($this->created_at);

            if (! $result) {
                return false;
            }
        }

        return true;
    }
}
