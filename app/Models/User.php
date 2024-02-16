<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    ];


    public function activities(){

        return $this->hasMany(Activity::class);

    }

    public function scopeTypeBDO($query){
        return $query->where('type',self::BDO);
    }

    public function scopeTypeVisor($query){
        return $query->where('type', self::SUPERVISOR);
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
            'wrhs'      => $request->wrhs,
            'type'      => $request->type,
            'password'  => !empty($request->password)?Hash::make($request->password):$oldpass->password,
        ];

    }

    public function scopeGetMySupervision($q,$type,$wrhs){
        if ($type=='supervisor') {
            return $q->where('type','bdo')->where('wrhs',$wrhs);
        }
    }
}
