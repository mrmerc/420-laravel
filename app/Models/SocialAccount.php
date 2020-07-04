<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'social_provider_id',
        'social_provider',
        'social_name',
        'social_avatar',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
