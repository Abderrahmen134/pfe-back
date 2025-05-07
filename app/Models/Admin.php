<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'mot_de_passe',
        'phone',
        'gouvernorat',
        'api_token'
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}
