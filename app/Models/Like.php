<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
    ];

    /**
     * @return BelongsTo<Post, Like>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo<User, Like>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
