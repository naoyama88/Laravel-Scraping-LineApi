<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegisteredUser
 *
 * @package App\Models
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $email_cycle_status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job query()
 * @mixin \Eloquent
 */
class RegisteredUser extends Model
{
    protected $fillable = [
        'id'
        , 'email'
        , 'name'
        , 'email_cycle_status'
    ];
}
