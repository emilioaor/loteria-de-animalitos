<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailySort extends Model
{
    protected $table = 'daily_sort';

    protected $fillable = [
        'sort_id', 'time_sort',
    ];

    /**
     * Retorna todos los tickets registrados a este sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets() {
        return $this->belongsToMany('App\Ticket', 'ticket_sort')->withPivot([
            'daily_sort_id', 'ticket_id',
        ]);
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
     * Retorna los resultados para este sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function results() {
        return $this->hasMany('App\Result', 'daily_sort_id');
    }

    /**
     * Retorna el total jugado en todos los tickets
     * asociados a este sorteo
     *
     * @return int
     */
    public function totalTickets() {
        $total = 0;
        $start = (new \DateTime())->setTime(0, 0, 0);
        $end = (new \DateTime())->setTime(23, 59, 59);

        $tickets = $this->tickets()
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->get();

        foreach ($tickets as $ticket) {
            $total += ($ticket->amount() / count($ticket->dailySorts));
        }

        return $total;
    }


    /**
     * Indica si el sorteo esta activo
     *
     * @return bool
     */
    public function hasActive()
    {
        // Hora actual
        $now = (new \DateTime());
        // 10 minutos antes del sorteo
        $timeSort = \DateTime::createFromFormat('H:i:s', $this->time_sort)->modify('-10 minutes');

        if ($timeSort > $now) {
            return true;
        }

        return false;
    }

    /**
     * Retorna el animal ganador de hoy para este sorteo
     *
     * @return mixed|null
     */
    public function getAnimalGain()
    {
        $result = $this->getResultToday();

        if ($result) {
            return $result->animal;
        }

        return null;
    }

    /**
     * Obtengo el resultado de hoy para este sorteo
     *
     * @return Model|null|static
     */
    public function getResultToday()
    {
        $start = (new \DateTime())->setTime(0, 0, 0);
        $end = (new \DateTime())->setTime(23, 59, 59);

        // Obtengo el resultado de este sorteo para hoy
        return $this->results()
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->first();
    }

    /**
     * Obtengo el resultado de este sorteo para la fecha
     * especificada
     *
     * @param \DateTime $date, fecha en que se busca el resultado
     * @return Model|null|static
     */
    public function getResultToDate(\DateTime $date)
    {
        $start = $date->setTime(0, 0, 0);
        $end = clone $date;
        $end->setTime(23, 59, 59);

        // Obtengo el resultado de este sorteo para hoy
        return $this->results()
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->first();
    }

    /**
     * Obtengo el animalito ganador para el sorteo de la fecha
     * especificada
     *
     * @param \DateTime $date
     * @return mixed|null
     */
    public function getAnimalGainToDate(\DateTime $date)
    {
        $result = $this->getResultToDate($date);

        if ($result) {
            return $result->animal;
        }

        return null;
    }

    /**
     * Retorna la hora del sorteo en formato hora:minuto am/pm
     *
     * @return string
     */
    public function timeSortFormat()
    {
        return \DateTime::createFromFormat('H:i:s', $this->time_sort)->format('h:i a');
    }
}
