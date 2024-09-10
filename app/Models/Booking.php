<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function activity(){
        return $this->belongsTo(Activity::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
