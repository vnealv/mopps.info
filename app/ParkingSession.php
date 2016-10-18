<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingSession extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'mopps_parkingSession';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'vehicle_id', 'isActive', 'start_unixtime', 'parking_duration', 'end_unixtime', 'parking_id', 'amount', 'latitude', 'longitude', 'vehicle_number'
    ];

//    protected $dateFormat = 'U';


    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function vehicle() {
        return $this->belongsTo('App\Vehicle');
    }

    public function parking() {
        return $this->belongsTo('App\Parking');
    }

}
