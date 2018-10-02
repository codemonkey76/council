<?php

namespace Tests\Feature;

use App\Thread;
use Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->trending = new \App\Trending();

        $this->trending->reset();
    }

    /** @test */
    function it_increments_a_thread_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());
        $thread = create(Thread::class);
        $this->call('GET', $thread->path());
        $this->assertCount(1, $this->trending->get());
        $this->assertEquals($thread->title, $this->trending->get()[0]->title);
    }


}
