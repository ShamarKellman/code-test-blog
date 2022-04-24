<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;

class LikeController extends Controller
{
    /**
     * @param  Post  $post
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post)
    {
        auth()->user()->likes()->create([
            'post_id' => $post->id,
        ]);

        return response()->noContent(200);
    }

    /**
     * @param  \App\Models\Post  $post
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        auth()->user()->likes()->where('post_id', $post->id)->delete();

        return response()->noContent();
    }
}
