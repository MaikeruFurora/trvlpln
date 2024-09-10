<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
