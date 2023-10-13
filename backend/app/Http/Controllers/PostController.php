<?php

    namespace App\Http\Controllers;

    use Illuminate\Support\Facades\Auth;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\PostsExport;
    use App\Imports\PostsImport;
    use App\Models\Post;
    use App\Http\Resources\PostResource;
    use Illuminate\Http\Request;
    use App\Http\Requests\CSVImportRequest;
    use App\Http\Requests\PostCreateRequest;
    use App\Http\Requests\PostUpdateRequest;

    class PostController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index(Request $request)
        {
            //search params for post data
            $searchQuery = $request->input('search');

             //get logged in user
             $user = Auth::user();

            if ($user->isAdmin()) {

                 //if logged in user is Admin, show all posts except deleted posts
                $postsQuery = Post::query();

            } else {

                //if User, show posts created by loggedin user
                $postsQuery = Post::where('create_user_id', $user->id);

            }

            if ($searchQuery) {

                //search post data with title and description
                $postsQuery->where(function ($q) use ($searchQuery) {
                    $q->where('title', 'like', "%$searchQuery%")
                    ->orWhere('description', 'like', "%$searchQuery%");
                });

            }

            //paginate posts with 5 posts per one page
            $posts = $postsQuery->paginate(5);

            $allPosts = Post::all();

            return response()->json(['posts' => $posts , 'allPosts' => $allPosts]);
        }

        public function showActivePosts(Request $request)
        {
            //search params for post data
            $searchQuery = $request->input('search');

            //get posts with active status
            $postsQuery = Post::where('status', 1);

            if ($searchQuery) {

                $postsQuery->where(function ($q) use ($searchQuery) {
                    $q->where('title', 'like', "%$searchQuery%")
                    ->orWhere('description', 'like', "%$searchQuery%");
                });

            }

            //paginate posts with 5 posts per one page
            $posts = $postsQuery->paginate(5);

            $allPosts = Post::all();

            return response()->json(['posts' => $posts , 'allPosts' => $allPosts]);
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(PostCreateRequest $request)
        {
            //if flg was confirm, create new post
            if($request->flg == 'confirm') {

                $post = Post::create($request->all());

                return response()->json(['message' => 'Post created successfully','post' =>  new PostResource($post)]);

            }

            return response()->json(['message' => 'Post create confirmed successfully']);
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
            //if flg was confirm, update post
            if($request->flg == 'confirm') {

                $post->update($request->all());

                return response()->json(['message' => 'Post updated successfully','post' =>  new PostResource($post)]);

            }

            return response()->json(['message' => 'Post update confirmed successfully']);
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Post $post)
        {
            //get logged in user
            $loggedinUser = Auth::user();

            //change post to inactive status and add logged in user id to deleted user id
            $post->update(['status' => 0 ,
                           'deleted_user_id' => $loggedinUser->id
            ]);

            $post->delete();

            return response()->json(['message' => 'Post deleted successfully']);
        }

        public function export()
        {
            //download post data
            return Excel::download(new PostsExport, 'posts.csv');
        }

        public function import(CSVImportRequest $request)
        {
            //import post data
            Excel::import(new PostsImport, $request->file('file')->store('files'));

            return response()->json(['message' => 'Posts imported successfully']);
        }
    }
