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
     * @param bool $toLower
     * @return mixed|string
     */
    public function getClearName($toLower = true) {
        $animalName = str_replace('á', 'a', $this->name);
        $animalName = str_replace('é', 'e', $animalName);
        $animalName = str_replace('í', 'i', $animalName);
        $animalName = str_replace('ó', 'o', $animalName);
        $animalName = str_replace('ú', 'u', $animalName);
        $animalName = str_replace('Á', 'A', $animalName);
        $animalName = str_replace('É', 'E', $animalName);
        $animalName = str_replace('Í', 'I', $animalName);
        $animalName = str_replace('Ó', 'O', $animalName);
        $animalName = str_replace('Ú', 'U', $animalName);
        if ($toLower) {
            $animalName = strtolower($animalName);
        }

        return $animalName;
    }

    /**
     * Retorna el nombre del animalito mas los espacios
     * faltantes para cuadrar el ticket de impresion
     *
     * @return string
     */
    public function getLabelMoreSpace() {
        $label = $this->number . '-' . $this->getClearName(false);
        $length = 25 - strlen($label);

        for ($x = 0; $x < $length; $x++) {
            $label .= ' ';
        }

        return $label;
    }
}
