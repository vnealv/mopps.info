<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parking extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'mopps_parking';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_name', 'street', 'city', 'state', 'country', 'zip_code', 'latitude', 'longitude', 'rate_per_hour', 'area_code'
    ];

//    protected $dateFormat = 'U';


    protected $dates = ['deleted_at'];

    public function parkingSession() {
        return $this->hasMany('App\ParkingSession');
    }

    public function history() {
        return $this->hasMany('App\History');
    }

}


