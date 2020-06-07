<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\HighPeople
 *
 * @property int $id
 * @property int $count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HighPeople newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HighPeople newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HighPeople query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HighPeople whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HighPeople whereId($value)
 * @mixin \Eloquent
 */
class HighPeople extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
