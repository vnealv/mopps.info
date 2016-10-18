<?php
/**
 * Created by PhpStorm.
 * User: neal
 * Date: 01/08/2016
 * Time: 12:44 AM
 */

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'mopps_vehicle';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand', 'model', 'color', 'vehicle_number', 'user_id', 'photo_id'
    ];

//    protected $dateFormat = 'U';


    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function parkingSession() {
        return $this->hasMany('App\ParkingSession');
    }

    public function history() {
        return $this->hasMany('App\History');
    }

    public function vehicle_photo() {
        return $this->hasOne('App\VehiclePhoto');
    }



}
