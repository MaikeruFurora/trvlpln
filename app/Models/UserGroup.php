<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:M d, Y ~ h:i A',
        'updated_at' => 'datetime:M d, Y ~ h:i A',
    ];

    public function scopeStoreGroup($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateGroup($q,$request){
        return $q->whereId($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        return [
            'handle_group_id' => $request->handle_group_id,
            'user_id'         => $request->user_id,
        ];

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
