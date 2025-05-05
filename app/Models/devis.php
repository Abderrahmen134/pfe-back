<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'societe',
        'id_client',
        
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function ligneDevis()
    {
        return $this->belongsTo(LigneDevis::class, 'id_ligne_devis');
    }
}
