<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $table = 'mopps_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'user_id'
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'salt'
    ];

    /**
     * This mutator automatically hashes the password.
     *
     * @var string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    public function vehicles(){
        return $this->hasMany('App\Vehicle');
    }

    public function photo() {
        return $this->hasOne('App\UserPhoto');
    }

    public function account(){
        return $this->hasOne('App\UserAccount');
    }

    public function parkingSession() {
        return $this->hasMany('App\ParkingSession');
    }

    public function history() {
        return $this->hasMany('App\History');
    }

    public function vehicle_photo() {
        return $this->hasMany('App\VehiclePhoto');
    }
}
