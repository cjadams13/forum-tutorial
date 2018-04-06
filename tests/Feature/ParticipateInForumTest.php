<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_unauthenticated_user_may_not_add_replies ()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads/1/replies');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads ()
    {
        $user = factory('App\User')->create();

        // be() sets this user as currently logged in user
        $this->be($user);

        $thread = factory('App\Thread')->create();

        // make creates it in memory, and then the post saves it to the DB
        // keeps the reply from being added to the DB twice
        $reply = factory('App\Reply')->make();

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}