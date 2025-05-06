<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneDevis extends Model
{
    use HasFactory;

    protected $table = 'ligne_devis';

    protected $fillable = [
        'id_devis',
        'id_product',
        'quantite',
         'remise',
        'total_ht',
        'tva',
        'total_ttc',
    ];
    public function product()
{
    return $this->belongsTo(Product::class, 'id_product');
}

}