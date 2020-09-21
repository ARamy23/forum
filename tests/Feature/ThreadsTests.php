<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadsTests extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    public function any_user_can_browse_threads() {
        $this->withoutExceptionHandling();

        $thread = Thread::factory()->create();

        $this->getJson('/api/threads')
            ->assertSee($thread['body'])
            ->assertStatus(200);
    }

    /** @test */
    public function any_user_can_browse_replies() {
        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create(['thread_id' => $thread->id]);

        $this->getJson("/api/threads/" . $thread->id . "/replies")
            ->assertSee($reply['body'])
            ->assertStatus(200);

        $this->assertDatabaseHas('replies', $reply->toArray());
    }

    /** @test */
    public function only_authenticated_users_can_make_threads() {
        $this->signIn();
        $this->withoutExceptionHandling();

        $createdThread = Thread::factory()->create();

        $this->postJson('/api/threads', $createdThread->toArray())
            ->assertSee($createdThread['body']);

        $this->assertDatabaseHas('threads', $createdThread->toArray());
    }

    /** @test */
    public function only_authenticated_users_can_make_replies() {
        $authenticatedUser = $this->signIn();
        $this->withoutExceptionHandling();

        $createdThread = Thread::factory()->create(['user_id' => $authenticatedUser->id]);
        $reply = Reply::factory()->create([
            'user_id' => $authenticatedUser->id,
            'thread_id' => $createdThread->id
        ]);

        $this->postJson($createdThread->path() . '/replies', $reply->toArray())
            ->assertSee($reply['body']);
    }

    /** @test */
    public function only_thread_owner_can_delete_his_thread() {
        $authenticatedUser = $this->signIn();

        $createdThread = Thread::factory()->create(['user_id' => $authenticatedUser->id]);
        $createdThreadByAnotherUser = Thread::factory()->create();
        $this->deleteJson($createdThread->path())
            ->assertStatus(200);

        $this->assertDatabaseMissing('threads', $createdThread->toArray());

        $this->deleteJson($createdThreadByAnotherUser->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('threads', $createdThreadByAnotherUser->toArray());
    }

    /** @test */
    public function only_reply_owner_or_thread_owner_can_delete_replies() {
        $owner = $this->signIn();
        $replier = User::factory()->create(['id' => 2]);
        $replier2 = User::factory()->create(['id' => 3]);

        $thread = Thread::factory()->create(['user_id' => $owner->id]);
        $ownerReply1 = Reply::factory()->create([
            'user_id' => $owner->id,
            'thread_id' => $thread->id,
            'id' => 23
            ]);
        $ownerReply2 = Reply::factory()->create([
            'user_id' => $owner->id,
            'thread_id' => $thread->id
        ]);

        $replierReply1 = Reply::factory()->create([
            'user_id' => $replier->id,
            'thread_id' => $thread->id
        ]);

        $replierReply2 = Reply::factory()->create([
            'user_id' => $replier->id,
            'thread_id' => $thread->id,
            'id' => 12
        ]);

        $replier2Reply1 = Reply::factory()->create([
            'user_id' => $replier2->id,
            'thread_id' => $thread->id
        ]);

        $this->deleteJson($ownerReply1->path())
            ->assertStatus(200);

        $this->deleteJson($replierReply1->path())
            ->assertStatus(200);


        $this->actingAs($replier, 'api');

        $this->deleteJson($ownerReply2->path())
            ->assertStatus(403);

        $this->deleteJson($replierReply2->path())
            ->assertStatus(200);

        $this->deleteJson($replier2Reply1->path())
            ->assertStatus(403);

        // owner tries to delete his reply -> respond with 200
        // owner tries to delete replier's reply -> respond with 200
        // Replier tries to delete owner's reply -> respond with 401
        // Replier tries to delete his own reply -> respond with 200
        // Replier tries to delete another replier's reply -> respond with 401
    }
//
//    /** @test */
//    public function admin_can_reply() {
//
//    }
//
//    /** @test */
//    public function admin_can_make_thread() {
//
//    }
//
//    /** @test */
//    public function admin_can_delete_threads() {
//
//    }
//
//    /** @test */
//    public function admin_can_delete_replies() {
//
//    }
}
