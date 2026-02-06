<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remboursement extends Model
{
    use HasFactory, SoftDeletes;

    public function dette()
    {
        return $this->belongsTo(Dette::class,'dette_id','id');
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
