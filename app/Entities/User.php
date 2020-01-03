<?php

namespace App\Entities;

use Eloquent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Entities\User
 *
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const ROLE_TEACHER = 0;
    const ROLE_PARENT  = 1;
    const ROLE_ADMIN   = 2;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'user_name',
        'email',
        'phone',
        'sex',
        'birthday',
        'street_address_now',
        'number_address_now',
        'district_id_now',
        'city_id_now',
        'street_address_native_village',
        'number_address_native_village',
        'district_id_native_village',
        'city_id_native_village',
        'work_at',
        'work_now',
        'voice_id',
        'level_id',
        'teaching_classes',
        'teaching_subjects',
        'teaching_at_districts',
        'identity_card',
        'degree',
        'avatar',
        'role',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
