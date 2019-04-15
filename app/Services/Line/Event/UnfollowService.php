<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\Event\UnfollowEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class UnfollowService
{
    /**
     * @var LINEBot
     */
    private $bot;

    /**
     * Unfollow constructor.
     * @param LINEBot $bot
     */
    public function __construct(LINEBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * 登録削除
     * @param UnfollowEvent $event
     * @return bool
     */
    public function execute(UnfollowEvent $event)
    {
        try {
            DB::beginTransaction();

            $lineId = $event->getUserId();

            LineFriend::where('line_id', $lineId)->delete();
            DB::commit();

            return true;

        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
    }

}