<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
   
    protected $casts = [
        'created_at' => 'datetime:M d, Y ~ h:i A',
        'updated_at' => 'datetime:M d, Y ~ h:i A',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    protected static function boot()
    {
        parent::boot();

        // Set created_by and modified_by during creation
        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->modified_by = Auth::id();
        });

        // Set modified_by during update
        static::updating(function ($model) {
            $model->modified_by = Auth::id();
        });
    }
    
}
