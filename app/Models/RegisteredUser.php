<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Job
 *
 * @package App\Models
 * @property int $id
 * @property string $category
 * @property string $title
 * @property string $href
 * @property string $post_datetime
 * @property string $sent_01
 * @property string $sent_02
 * @property string $sent_03
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job query()
 * @mixin \Eloquent
 */
class Job extends Model
{
    protected $fillable = [
        'id'
        , 'category'
        , 'title'
        , 'href'
        , 'sent_01'
        , 'sent_02'
        , 'sent_03'
        , 'post_datetime'
    ];
}
