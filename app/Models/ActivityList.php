<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ActivityList extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function activity(){
        return $this->hasOne(Activity::class);
    }

    public function scopeStoreList($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateList($q,$request){
        return $q->whereId($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){

        return [
            'name'         => $request->name,
            'is_disabled'  => $request->is_disabled,
            'color'        => $request->color,
            'icon'         => $request->icon,
        ];

    }

    public function scopeGetActive($q){
        return $q->where('is_disabled',0);
    }
}
