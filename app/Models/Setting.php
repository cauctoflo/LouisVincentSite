<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        "key",
        "value"
    ];

    public function getValue($key)
    /**
     * Renvoie la valeur d'une clé de paramètre.
     *
     * @param string $key La clé du paramètre à rechercher.
     * @return string|null La valeur associée à la clé, ou null si la clé n'existe pas.
     */


    {
        return $this->where("key", $key)->first()->value ?? null;
    }
}
