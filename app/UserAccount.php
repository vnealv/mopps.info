<?php
/**
 * Created by PhpStorm.
 * User: neal
 * Date: 01/08/2016
 * Time: 11:17 AM
 */


namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAccount extends Authenticatable
{

    protected $table = 'mopps_userAccount';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'balance',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }



}
