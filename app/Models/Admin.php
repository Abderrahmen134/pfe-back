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
        'api_token',
        "user_id",
        'statutad',
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
    public function user()
{
    return $this->belongsTo(User::class);
}

}
