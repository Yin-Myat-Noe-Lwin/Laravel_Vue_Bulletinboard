<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json(['posts' => PostResource::collection($posts)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        if($request->flg == 'confirm') {

            $post = Post::create($request->all());

            return response()->json(['message' => 'Post created successfully','post' =>  new PostResource($post)]);

        }

        return response()->json(['message' => 'Post confirmed successfully']);

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json(['post' => new PostResource($post) ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        if($request->flg == 'confirm'){

            $post->update($request->all());

            return response()->json(['message' => 'Post updated successfully','post' =>  new PostResource($post)]);

        }

        return response()->json(['message' => 'Post edit confirmed successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->update(['status' => 0]);

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);

    }
}
