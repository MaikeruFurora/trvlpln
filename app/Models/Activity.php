<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class Activity extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'title',
        'content',
    ];
    
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function activity_list(){
        return $this->belongsTo(ActivityList::class);
    }

    public function histories(){
        return $this->hasMany(History::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

}
