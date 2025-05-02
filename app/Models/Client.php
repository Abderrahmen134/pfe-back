<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
protected $fillable = ['prenom','nom', 'email' ,'phone', 'gouvernorat','mot_de_passe', 'api_token'];
    //ptected $hidden = ['mot_de_passe', 'api_token'];
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}