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
            $rsp = $this->bot->getProfile($line_id);
            if (!$rsp->isSucceeded()) {
                logger()->info('failed to get profile. skip processing.');
                return false;
            }

            $profile = $rsp->getJSONDecodedBody();
            $line_friend = new LineFriend();
            $input = [
                'line_id' => $line_id,
                'display_name' => $profile['displayName'],
            ];

            $line_friend->fill($input)->delete();
            DB::commit();

            return true;

        } catch (Exception $e) {
            logger()->error($e);
            DB::rollBack();
            return false;
        }
    }

}