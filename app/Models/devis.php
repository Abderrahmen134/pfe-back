<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class devis extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'client_id',
        'product_id',
        'quantity',
        'status',
        'note',
    ];
  // Relations (si tu veux les charger)
  public function client()
  {
      return $this->belongsTo(Client::class);
  }

  public function product()
  {
      return $this->belongsTo(Product::class);
  }
}