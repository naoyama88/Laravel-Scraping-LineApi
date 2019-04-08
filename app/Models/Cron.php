<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Cron
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cron newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cron newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cron query()
 * @mixin \Eloquent
 */
class Cron extends Model
{
    protected $primaryKey = 'command';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['command', 'next_run', 'last_run'];

    public static function shouldIRun($command, $minutes) {
        error_log('error_log function2');
        $cron = Cron::find($command);
        $now  = Carbon::now();
        if ($cron && $cron->next_run > $now->timestamp) {
            return false;
        }

        Cron::updateOrCreate(
            ['command'  => $command],
            ['next_run' => Carbon::now()->addMinutes($minutes)->timestamp,
                'last_run' => Carbon::now()->timestamp]
        );
        return true;
    }
}