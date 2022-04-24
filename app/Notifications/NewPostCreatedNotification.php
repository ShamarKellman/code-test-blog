<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPostCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var null|Post
     */
    public null|Post $post;

    /**
     * @param  string  $postId
     */
    public function __construct(string $postId)
    {
        $this->post = Post::query()->with('author')->find($postId);
    }

    /**
     * @param $notifiable
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * @param $notifiable
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return [
            'id' => $this->post->id,
            'title' => $this->post->title,
            'author' => $this->post->author->username,
            'created_at' => $this->post->created_at,
        ];
    }

    /**
     * @param $notifiable
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'id' => $this->post->id,
            'title' => $this->post->title,
            'author' => $this->post->author->username,
            'created_at' => $this->post->created_at,
        ];
    }
}
