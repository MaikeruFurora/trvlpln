<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    const SUPERVISOR = 'SUPERVISOR';
    const BDO = 'BDO';
    const ADMIN = 'ADMIN';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'wrhs',
        'username',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:M d, Y ~ h:i A',
        'updated_at' => 'datetime:M d, Y ~ h:i A',
    ];


    public function activities(){

        return $this->hasMany(Activity::class);

    }

    public function scopeGetActive($query){
        return $query->where('is_active','YES');
    }

    public function scopeTypeBDO($query){
        return $query->where('type',self::BDO);
    }

    public function scopeTypeVisor($query){
        return $query->where('type', self::SUPERVISOR);
    }

    public function scopeBothBDOAndVisor($query){
        return $query->whereNotIn('type', [self::ADMIN]);
    }

    public function scopeNotAdmin($query){
        return $query->whereNot('type', self::ADMIN);
    }

    public function scopeStoreUser($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateUser($q,$request){
        return $q->whereId($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        $oldpass = Static::find($request->id);
        return [
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'wrhs'      => trim($request->wrhs),
            'wrhs_id'   => $request->wrhs_id,
            'group_id'  => $request->group_id,
            'type'      => $request->type,
            'is_active' => $request->is_active,
            'password'  => !empty($request->password)?Hash::make($request->password):$oldpass->password,
        ];

    }

    public function scopeGetMySupervision($q,$type,$wrhs){
        if ($type=='supervisor') {
            return $q->where('type','bdo')->where('wrhs',$wrhs);
        }
    }

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

    public function handleGroup(){
        return $this->hasMany(Group::class,'group_id');
    }
}
