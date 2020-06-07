<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property string $body
 * @property mixed|null $attachments
 * @property int $timestamp
 * @property int $user_id
 * @property int $room_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUserId($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    protected $casts = [
        'attachments' => 'array'
    ];
    /**
     * User that posted the message
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
