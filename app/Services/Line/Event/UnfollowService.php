<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\Event\UnfollowEvent;
use Illuminate\Support\Facades\DB;
use Exception;

class UnfollowService
{
    /**
     * @var LINEBot
     */
    private $bot;

    /**
     * Follow constructor.
     * @param LINEBot $bot
     */
    public function __construct(LINEBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * LineID 削除
     * @param UnfollowEvent $event
     * @return bool
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Exception
     */
    public function execute(UnfollowEvent $event)
    {
        try {
            DB::beginTransaction();

            $line_id = $event->getUserId();
            if (empty($line_id)) {
                logger()->info('failed to get line id. skip processing.');
                return false;
            }

            LineFriend::where('line_id', $line_id)->delete();

            DB::commit();

            return true;

        } catch (Exception $e) {
            logger()->error($e);
            DB::rollBack();
            return false;
        }
    }

}