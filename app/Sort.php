<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sort extends Model
{
    protected $table = 'sorts';

    protected $fillable = [
        'time_sort', 'description', 'pay_per_100'
    ];

    /**
     * Retorna los sorteos diarios
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dailySorts() {
        return $this->hasMany('App\DailySort', 'sort_id');
    }

    /**
     * Retorna el ultimo sorteo diario asociado
     * a este sorteo
     *
     * @return Model|null|static
     */
    public function getLastDailySort() {
        return $this->dailySorts()
            ->orderBy('daily_sort.date_sort', 'DESC')
            ->first()
        ;
    }

    /**
     * Verifica si hay un sorteo diario registrado para hoy
     *
     * @return bool
     */
    public function hasDailySortToday() {
        $startDate = (new \DateTime('now'))->setTime(0, 0, 0);
        $endDate = (new \DateTime('now'))->setTime(23, 59, 59);
        $dailySort = $this->getLastDailySort();

        if (! $dailySort) {
            return false;
        }

        if ($dailySort->getDateSort() >= $startDate && $dailySort->getDateSort() <= $endDate) {
            return true;
        }

        return false;
    }
}
