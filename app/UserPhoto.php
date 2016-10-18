<?php
/**
 * Created by PhpStorm.
 * User: neal
 * Date: 01/08/2016
 * Time: 11:11 AM
 */

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;


class UserPhoto extends Authenticatable
{


    protected $table = 'mopps_userPhoto';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'photo_id', 'base64'
    ];


//    protected $hidden = ['base64'];

    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }



}
