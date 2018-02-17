<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sort extends Model
{
    protected $table = 'sorts';

    protected $fillable = [
        'description', 'pay_per_100', 'folder', 'daily_limit', 'week_limit'
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
     * Todos los animalitos de este sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function animals()
    {
        return $this->hasMany('App\Animal', 'sort_id');
    }
}
