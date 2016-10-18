<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'mopps_transactionHistory';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'amount', 'date_unix', 'vehicle_id', 'vehicle_number', 'parking_id', 'parking_name', 'surcharge', 'gst', 'tax', 'total'
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
