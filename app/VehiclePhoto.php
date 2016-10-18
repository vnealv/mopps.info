<?php
/**
 * Created by PhpStorm.
 * User: neal
 * Date: 10/08/2016
 * Time: 5:25 PM
 */


namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehiclePhoto extends Authenticatable
{


    protected $table = 'mopps_vehiclePhoto';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'vehicle_id', 'photo_id', 'base64'
    ];


//    protected $hidden = ['base64'];

    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function vehicle() {
        return $this->belongsTo('App\Vehicle');
    }



}
