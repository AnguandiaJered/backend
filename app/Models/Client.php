<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sortie()
    {
        return $this->hasMany(Sortie::class, 'client_id','id');
    }

    public function dette()
    {
        return $this->hasMany(Dette::class, 'client_id','id');
    }

    public function paiement()
    {
        return $this->hasMany(Remboursement::class, 'client_id','id');
    }
}
