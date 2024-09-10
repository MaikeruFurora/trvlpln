<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeStoreProduct($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateProduct($q,$request){
        return $q->whereId($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        $oldpass = Static::find($request->id);
        return [
            'name'      => $request->name,
            'code'      => $request->code,
            'group_id'  => $request->group,
        ];
    }

    public function booking(){
        return $this->hasMany(Booking::class);
    }
   
    
}
