<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

/**
 * @apiDefine User
 * @apiSuccess {Object} user                The authenticated User.
 * @apiSuccess {Int} user.id                User's ID.
 * @apiSuccess {String} user.nickname       User's username.
 * @apiSuccess {String} user.email          User's email.
 * @apiSuccess {String} user.social_name    User's social name.
 * @apiSuccess {String} user.social_avatar  User's social avatar.
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * The roles that belong to the user
     */
    public function roles() {
        return $this->belongsToMany('App\Models\Role', 'role_user');
    }

    /**
     * The messages posted by the user
     */
    public function messages() {
        return $this->hasMany('App\Models\Message');
    }

    /**
     * User's social accounts
     */
    public function socials() {
        return $this->hasMany('App\Models\SocialAccount');
    }

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

    /**
     * Set password mapper
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Checks if user has role
     */
    public function hasRole(Role $role): bool
    {
        return $this->roles()->get()->contains($role);
    }

    /**
     * Checks if user is banned
     */
    public function isBanned(): bool
    {
        return (bool) array_filter(
            $this->roles()->get()->toArray(),
            function($role) {
                return $role['title'] === 'banned';
            }
        );
    }

    /**
     * Checks if user is admin
     */
    public function isAdmin(): bool
    {
        return (bool) array_filter(
            $this->roles()->get()->toArray(),
            function($role) {
                return (
                    $role['title'] === 'superadmin' ||
                    $role['title'] === 'admin'
                );
            }
        );
    }
}
