<?php

namespace App;

class Reputation
{
    const THREAD_WAS_PUBLISHED = 10;
    const REPLY_POSTED = 2;
    const BEST_REPLY_AWARDED = 50;
    const REPLY_FAVORITED = 5;
    /**
     * Award reputation points to the given user.
     *
     * @param $user
     * @param $points
     */
    public static function award($user, $points)
    {
        $user->increment('reputation', $points);
    }


    /**
     * Revoke reputation points from the given user.
     *
     * @param $user
     * @param $points
     */
    public static function revoke($user, $points)
    {
        $user->decrement('reputation', $points);
    }
}