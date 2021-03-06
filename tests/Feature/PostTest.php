<?php

declare(strict_types=1);

use App\Events\NewPostCreatedEvent;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewPostCreatedNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

test('user can create post', function () {
    Event::fake(NewPostCreatedEvent::class);
    Storage::fake('local');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('test.jpg');

    $this->actingAs($user)
         ->postJson('/api/v1/posts', [
             'title' => 'Test Post',
             'description' => 'This is a description',
             'image' => $file,
         ])
         ->assertCreated();

    $this->assertDatabaseCount('posts', 1);
    $this->assertDatabaseHas('posts', [
        'title' => 'Test Post',
        'description' => 'This is a description',
        'image' => "images/{$file->getClientOriginalName()}",
    ]);

    Storage::assertExists(Post::first()->image);

    Event::assertDispatched(NewPostCreatedEvent::class);
});

test('user cannot create post if not authenticated', function () {
    Storage::fake('local');

    $file = UploadedFile::fake()->image('test.jpg');

    $this->postJson('/api/v1/posts', [
        'title' => 'Test Post',
        'description' => 'This is a description',
        'image' => $file,
    ])
         ->assertUnauthorized();
});

test('user can view all post', function () {
    Post::factory()
        ->forAuthor()
        ->hasLikes(6)
        ->count(3)
        ->create();

    $this->getJson('/api/v1/posts')
         ->assertStatus(200)
         ->assertJsonCount(3, 'data');
});

test('user can view by id', function () {
    $post = Post::factory()
                ->forAuthor()
                ->hasLikes(6)
                ->create();

    $this->getJson("/api/v1/posts/{$post->id}")
         ->assertStatus(200)
         ->assertJsonPath('data.id', $post->id)
         ->assertJsonPath('data.title', $post->title);
});

test('user can like a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $response = $this->actingAs($user)
                     ->postJson("/api/v1/posts/{$post->id}/like")
                     ->assertOk();

    $this->assertDatabaseHas('likes', [
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);
});

test('user can unlike a post', function () {
    $like = Like::factory()->create();

    $this->actingAs($like->user)
         ->postJson("/api/v1/posts/{$like->post_id}/unlike")
         ->assertNoContent();

    $this->assertDatabaseCount('likes', 0);
});

test('user can remove a post', function () {
    Storage::fake('local');

    UploadedFile::fake()
                ->image('test.jpg')
                ->storeAs('images', 'test.jpg', 'local');

    Storage::assertExists('images/test.jpg');

    $post = Post::factory()->set('image', '/images/test.jpg')->create();

    $this->actingAs($post->author)
         ->deleteJson("/api/v1/posts/{$post->id}")
         ->assertNoContent();

    $this->assertDatabaseCount('posts', 0);

    Storage::assertMissing('images/test.jpg');
});

test('user cannot remove a post if they do not own the post', function () {
    Storage::fake('local');

    UploadedFile::fake()
                ->image('test.jpg')
                ->storeAs('images', 'test.jpg', 'local');

    Storage::assertExists('images/test.jpg');

    $user = User::factory()->create();

    $post = Post::factory()->set('image', '/images/test.jpg')->create();

    $this->actingAs($user)
         ->deleteJson("/api/v1/posts/{$post->id}")
         ->assertUnauthorized();
});

test('can remove post after 15 days', function () {
    Post::factory()->create();

    $this->travelTo(now()->addDays(10));
    $this->artisan('model:prune');

    $this->assertDatabaseCount('posts', 1);

    $this->travelTo(now()->addDays(5));

    $this->artisan('model:prune');

    $this->assertDatabaseCount('posts', 0);
});


test('sends notification to all users when post is created', function () {
    Notification::fake();

    $users = User::factory()->count(3)->create();

    $post = Post::factory()->create();

    event(new NewPostCreatedEvent($post->id));

    Notification::assertSentTo($users, NewPostCreatedNotification::class);
});
