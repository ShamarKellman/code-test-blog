<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\NewPostCreatedEvent;
use App\Models\User;
use App\Notifications\NewPostCreatedNotification;
use Illuminate\Support\Facades\Notification;

class SendNewPostCreatedEmailListener
{
    /**
     * @param  NewPostCreatedEvent  $event
     *
     * @return void
     */
    public function handle(NewPostCreatedEvent $event): void
    {
        $users = User::all();

        Notification::send($users, new NewPostCreatedNotification($event->postId));
    }
}
