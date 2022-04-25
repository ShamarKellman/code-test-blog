<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Events\NewPostCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection<PostResource>
     */
    public function index(): AnonymousResourceCollection
    {
        return PostResource::collection(
            Post::with([
                'likes' => function (Builder $query) {
                    $query->latest()->take(5);
                }, 'author',
            ])
                ->withCount('likes')
                ->latest()
                ->paginate()
        );
    }

    /**
     * @param  \App\Models\Post  $post
     *
     * @return PostResource
     */
    public function show(Post $post)
    {
        return PostResource::make(
            $post->load(['author', 'likes'])
                 ->loadCount('likes')
        );
    }

    /**
     * @param  \App\Http\Requests\StorePostRequest  $request
     *
     * @return PostResource
     */
    public function store(StorePostRequest $request)
    {
        $filename = $request->file('image')->getClientOriginalName();

        $path = $request->file('image')
                        ->storeAs('images', $filename, 'local');

        $post = auth()
            ->user()
            ->posts()
            ->create(array_merge($request->validated(), [
                'image' => $path,
            ]));

        event(new NewPostCreatedEvent($post->id));

        return PostResource::make($post);
    }

    /**
     * @param  \App\Models\Post  $post
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 401);

        Storage::disk('local')->delete($post->image);

        $post->delete();

        return response()->noContent();
    }
}
