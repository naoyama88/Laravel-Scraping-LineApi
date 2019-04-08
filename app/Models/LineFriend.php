<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LineFriend
 *
 * @package App\Models
 * @property int $id
 * @property string $line_id
 * @property string $display_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LineFriend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LineFriend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LineFriend query()
 * @mixin \Eloquent
 */
class LineFriend extends Model
{
    protected $fillable = ['line_id', 'display_name'];
}
