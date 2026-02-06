<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function approvisionnement()
    {
        return $this->hasMany(Approvisionnement::class, 'produit_id','id');
    }

    public function dette()
    {
        return $this->hasMany(Dette::class, 'produit_id','id');
    }

    public function perte()
    {
        return $this->hasMany(PerteProduit::class, 'produit_id','id');
    }

    public function sortie()
    {
        return $this->hasMany(Sortie::class, 'produit_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'author_id','id');
    }
}
