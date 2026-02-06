<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_perte extends Model
{
    use HasFactory;

    protected $fillable = ['id','name'];

    public function perte()
    {
        return $this->hasMany(PerteProduit::class, 'gaspillage_id','id');
    }
}
