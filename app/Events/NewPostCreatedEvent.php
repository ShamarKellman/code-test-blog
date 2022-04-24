<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class NewPostCreatedEvent
{
    use Dispatchable;

    /**
     * @param  string  $postId
     */
    public function __construct(
        public string $postId
    ) {
    }
}
