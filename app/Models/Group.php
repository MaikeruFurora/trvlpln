<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive($query){
        return $query->where('active',1);
    }

    public function scopeStoreGroup($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateGroup($q,$request){
        return $q->whereId($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        $oldpass = Static::find($request->id);
        return [
            'name'      => $request->name,
            'active'  => $request->active ?? true,
        ];

    }
}
