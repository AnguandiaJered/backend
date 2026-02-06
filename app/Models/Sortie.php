<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sortie extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Produit::class,'produit_id','id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'author_id','id');
    }
}
