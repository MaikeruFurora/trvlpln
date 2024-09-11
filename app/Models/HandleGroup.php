<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandleGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users(){
        return $this->belongsTo(User::class,'group_id','id');
    }

    public function scopeGetActive($query){
        return $query->where('active',1);
    }

    public function scopeStoreGroup($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateGroup($q,$request){
        return $q->whereId($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        return [
            'name'    => $request->name,
            'user_id' => $request->user_id,
            'active'  => $request->active ?? true,
        ];

    }
}
