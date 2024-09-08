<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wrhs extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive($query){
        return $query->where('active',1);
    }

    public function scopeStoreWarehouse($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateWarehouse($q,$request){
        return $q->whereId($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        $oldpass = Static::find($request->id);
        $slug    = Str::slug($request->input('name'), '_');
        return [
            'name'      => $request->name,
            'slug'      => $slug,
            'active'    => $request->active ?? true,
            'is_show'   => $request->is_show ?? true,
        ];
    }
    
}
