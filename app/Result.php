<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = 'results';

    protected $fillable = [
        'daily_sort_id', 'animal_id'
    ];

    /**
     * Retorna el sorteo diario que pertenece este resultado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dailySort() {
        return $this->belongsTo('App\DailySort', 'daily_sort_id');
    }

    /**
     * Retorna el animal ganador
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function animal() {
        return $this->belongsTo('App\Animal', 'animal_id');
    }
}
