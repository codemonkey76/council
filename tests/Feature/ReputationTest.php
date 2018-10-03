<?php

namespace Tests\Feature;

use App\Reputation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReputationTest extends TestCase {

    use DatabaseMigrations;

    /** @test */
    public function a_user_earns_points_when_they_create_a_thread()
    {
        $thread = create('App\Thread');

        $this->assertEquals(Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation);
    }

    /** @test */
    function a_user_loses_points_when_they_delete_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->assertEquals(Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation);

        $this->delete($thread->path());

        $this->assertEquals(0, $thread->creator->fresh()->reputation);
    }

    /** @test */
    function a_user_earns_points_when_they_reply_to_a_thread()
    {
        $thread = create('App\Thread');

        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body'    => 'Test'
        ]);

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);
    }

    /** @test */
    function a_user_loses_points_when_they_delete_their_reply_to_a_thread()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);

        $this->delete("/replies/{$reply->id}");

        $this->assertEquals(0, $reply->owner->fresh()->reputation);
    }

    /** @test */
    function a_user_earns_points_when_their_reply_is_marked_as_best()
    {
        $thread = create('App\Thread');

        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body'    => 'Test'
        ]);

        $thread->markBestReply($reply);
        $this->assertEquals(Reputation::REPLY_POSTED + Reputation::BEST_REPLY_AWARDED, $reply->owner->reputation);
    }

    /** @test */
    function a_user_loses_points_when_their_reply_is_unmarked_as_best()
    {
        $thread = create('App\Thread');

        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body'    => 'Test'
        ]);
        $replyByDifferentUser = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Test'
        ]);

        $thread->markBestReply($reply);
        $this->assertEquals(Reputation::REPLY_POSTED + Reputation::BEST_REPLY_AWARDED, $reply->owner->reputation);

        $thread->markBestReply($replyByDifferentUser);
        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->fresh()->reputation);
    }
    /** @test */
    function a_user_earns_points_when_their_reply_is_favorited()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply'
        ]);

        $this->post("/replies/{$reply->id}/favorites");

        $this->assertEquals(Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED, $reply->owner->fresh()->reputation);
    }

    /** @test */
    function a_user_loses_points_when_their_favorited_reply_is_unfavorited()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply'
        ]);

        $this->post("/replies/{$reply->id}/favorites");

        $this->assertEquals(Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED, $reply->owner->fresh()->reputation);

        $this->delete("/replies/{$reply->id}/favorites");

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->fresh()->reputation);
    }
}

