<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\Event\FollowEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class FollowService
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
     * 登録
     * @param FollowEvent $event
     * @return bool
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function execute(FollowEvent $event)
    {
        try {
            DB::beginTransaction();

            $lineId = $event->getUserId();
            $rsp = $this->bot->getProfile($lineId);
            if (!$rsp->isSucceeded()) {
                Log::info('failed to get profile. skip processing.');
                return false;
            }

            $profile = $rsp->getJSONDecodedBody();
            $lineFriend = new LineFriend();
            $input = [
                'line_id' => $lineId,
                'display_name' => $profile['displayName'],
            ];

            $lineFriend->fill($input)->save();
            DB::commit();

            return true;

        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
    }

}