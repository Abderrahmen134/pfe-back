<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    // Nom de la table (optionnel si le nom est 'types' au pluriel)
    protected $table = 'types';

    // Champs autorisés à l’insertion/modification en masse
    protected $fillable = [
        'name',
    ];

    /**
     * Relation : un type a plusieurs produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
