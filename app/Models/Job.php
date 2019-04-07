<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Job
 * @package App\Models
 *
 * @property int $id
 * @property string $category
 * @property string $title
 * @property string $href
 * @property timestamp $post_datetime
 */
class Job extends Model
{
    protected $fillable = [
        'id'
        , 'category'
        , 'title'
        , 'href'
        , 'post_datetime'
    ];
}
