<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = 'animals';

    protected $fillable = [
        'name', 'number', 'sort_id'
    ];

    /**
     * Retorna todos los tickets que han jugado por este animal
     *
     * @return $this
     */
    public function tickets()
    {
        return $this->belongsToMany('App\Ticket', 'animal_ticket')->withPivot([
            'animal_id', 'ticket_id', 'amount'
        ]);
    }

    /**
     * Retorna todos los sorteos que tienen a este animal
     * como ganador
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany('App\Result', 'animal_id');
    }

    /**
     * Sorteo al que pertenece este animal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sort()
    {
        return $this->belongsTo('App\Sort', 'sort_id');
    }

    /**
     * Retorna el nombre del animal en minuscula
     * y sin acento
     *
     * @return mixed|string
     */
    public function getClearName() {
        $animalName = strtolower($this->name);
        $animalName = str_replace('á', 'a', $animalName);
        $animalName = str_replace('é', 'e', $animalName);
        $animalName = str_replace('í', 'i', $animalName);
        $animalName = str_replace('ó', 'o', $animalName);
        $animalName = str_replace('ú', 'u', $animalName);
        $animalName = str_replace('Á', 'a', $animalName);

        return $animalName;
    }

    /**
     * Retorna el nombre del animalito mas los espacios
     * faltantes para cuadrar el ticket de impresion
     *
     * @return string
     */
    public function getLabelMoreSpace() {
        $label = $this->number . '-' . $this->name;
        $countLabel = $this->number . '-' . $this->getClearName();
        $length = 25 - strlen($countLabel);

        for ($x = 0; $x < $length; $x++) {
            $label .= ' ';
        }

        return $label;
    }
}
