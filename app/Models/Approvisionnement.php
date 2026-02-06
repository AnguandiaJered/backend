<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approvisionnement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    //protected $fillable = [ ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'author_id','id');
    }
}
