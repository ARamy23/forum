<?php

namespace Tests\Feature;

use App\Models\Thread;
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

//    /** @test */
//    public function any_user_can_browse_replies() {
//
//    }
//
//    /** @test */
//    public function only_authenticated_users_can_make_threads() {
//
//    }
//
//    /** @test */
//    public function only_authenticated_users_can_make_replies() {
//
//    }
//
//    /** @test */
//    public function only_thread_owner_can_delete_his_thread() {
//
//    }
//
//    /** @test */
//    public function only_reply_owner_or_thread_owner_can_delete_his_reply() {
//
//    }
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
