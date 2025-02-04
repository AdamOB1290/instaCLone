<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\LikeEvent;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Events\PostCreated;
use App\Notification;
use finfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // check if the profile picture of the session user is a url
        // if (filter_var($sessionUser->pfp, FILTER_VALIDATE_URL)) {

        //     // index it to session user
        //     $sessionUser['pfp_type'] = 'imageUrl';
        // } else { // else it means it's a local file

        //     // index it to session user
        //     $sessionUser['pfp_type'] = 'localImage';
        // }

        // foreach ($followedUsers as $followedUser) {
        //     if (filter_var($followedUser->pfp, FILTER_VALIDATE_URL)) {

        //         // index it to session user
        //         $followedUser['pfp_type'] = 'imageUrl';
        //     } else { // else it means it's a local file
    
        //         // index it to session user
        //         $followedUser['pfp_type'] = 'localImage';
        //     }
        // }
        
        $sessionUser = User::findOrFail(Auth::user()->id);

        if (isset(Auth::user()->followed)) {
            $followedUsersIds = Auth::user()->followed;
            $followedUsers = User::findOrFail($followedUsersIds);
            $sessionUser['followed_users'] = $followedUsers;
        } else {
            $followedUsersIds = null;
            $sessionUser['followed_users'] = null;
        }
        
        


        

        $request->session()->put('session_user', $sessionUser);
        
        
        
        
        // if session user has followed other users 
        if (isset($followedUsersIds) && (count($followedUsersIds) > 0)) {

            $posts = [];
            $storyUsers = [];

            // loop through the followed users ids
            foreach ($followedUsersIds as $followedUserId ) {

            // fetch the posts of every followed user
            $followedPosts = Post::select()->where('user_id', $followedUserId)->with('user')->with('comments')->get();
            
            // push the posts of the followed user into the array posts
            array_push($posts, ...$followedPosts);

            }

            // we're sending the username with each comment made on a post
            // loop through posts
            foreach ($posts as $post) {

                // check if the media file of each post is a url
                // if (filter_var($post->media_file, FILTER_VALIDATE_URL)) {
                    
                    // $buffer = file_get_contents($post->media_file);
                    // $finfo = new finfo(FILEINFO_MIME_TYPE);
                    // $mediaType= '$finfo->buffer($buffer)';
                    
                    
                    // if(strstr($mediaType, "image/")) {
                    //     // index it to post
                    //     $post['media_type'] = 'imageUrl';
                    // } else if(strstr($mediaType, "video/")) {
                    //     // index it to post
                    //     $post['media_type'] = 'videoUrl';
                    // }
                // } else { // else it means it's a local file

                //     // check if the local file is an image
                //     if (strstr(mime_content_type('storage/' . $post->media_file), "image/")) {

                //         // index it to post
                //         $post['media_type'] = 'localImage';

                //         // else check if the local file is a video
                //     } elseif (strstr(mime_content_type('storage/' . $post->media_file), "video/")) {

                //         // index it to post
                //         $post['media_type'] = 'localVideo';
                //     }
                // }

                // check if the profile picture of the post user is a url
                // if (filter_var($post->user->pfp, FILTER_VALIDATE_URL)) {

                //     // index it to post user
                //     $post->user['pfp_type'] = 'imageUrl';
                // } else { // else it means it's a local file

                //     // index it to post user
                //     $post->user['pfp_type'] = 'localImage';
                // }
                
                if ($post->type == 'story' || $post->type == 'post/story' || $post->type == 'story/post') {
                   if (array_search($post->user, $storyUsers) === false) {
                       array_push($storyUsers, $post->user);
                   }  
                }
                

                // loop through the comments of every post
                foreach ($post->comments as $comment) {

                    // fetch the username for every comment
                    $username = User::findOrFail($comment->user_id)->username;

                    // index the username to the comment
                    $comment['username'] = $username;
                    
                }
                $post['session_user']= $sessionUser;
            }
            $posts[0]['story_users'] = $storyUsers;
             
            // return view('posts.crud.index', compact('posts', 'followedUserIds'));
            // return response()->json(['posts'=> $posts]);
            
            return $posts;
            

            // if session user has not followed other users 
        } else {
            $users = User::all();
            foreach ($users as $user) {
                // check if the profile picture of the post user is a url
                // if (filter_var($user->pfp, FILTER_VALIDATE_URL)) {

                //     // index it to post user
                //     $user['pfp_type'] = 'imageUrl';
                // } else { // else it means it's a local file

                //     // index it to post user
                //     $user['pfp_type'] = 'localImage';
                // }

                // foreach ($user->posts as $post) {
                //     if (filter_var($post->media_file, FILTER_VALIDATE_URL)) {

                //         // index it to post
                //         $post['media_type'] = 'imageUrl';

                //         // else it means it's a local file, check if the local file is an image
                //     } elseif (strstr(mime_content_type('storage/' . $post->media_file), "image/")) {

                //         // index it to post
                //         $post['media_type'] = 'localImage';
                //     }
                // }

                $user['top_posts'] = $user->posts;
                $user['session_user']= $sessionUser;
            } 
            
            return $users;

        
        };
        

       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {   
        // return view("posts.crud.create", compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $post = Post::create($this->validatedData());
        $post = Post::create(request()->validate([
            'user_id' => 'required',
            'description' => '',
            'media_file' => 'required',
            'media_type' => 'required',
            // 'media_file' => 'required|mimes:jpg,png,jpeg,gif,svg,mp4,jpeg,png,bmp,svg,avi,mkv,mpeg|max:50000 ',
            'type' => 'required',
            ]
        ));

        
        $this->storeMediaFile($post);

        // event(new PostCreated($post));

        $user = User::findOrFail(Auth::user()->id);

        $post['user'] = $user;
        $post['comments'] = [];
        
        // if (filter_var($post->media_file, FILTER_VALIDATE_URL)) {

        //     if(is_array(getimagesize($post->media_file))) {
        //         // index it to post
        //         $post['media_type'] = 'imageUrl';
        //     } 
        //     // else {
        //     //     // index it to post
        //     //     $post['media_type'] = 'videoUrl';
        //     // }
        // } else { // else it means it's a local file

        //     // check if the local file is an image
        //     if (strstr(mime_content_type('storage/' . $post->media_file), "image/")) {

        //         // index it to post
        //         $post['media_type'] = 'localImage';

        //         // else check if the local file is a video
        //     } elseif (strstr(mime_content_type('storage/' . $post->media_file), "video/")) {

        //         // index it to post
        //         $post['media_type'] = 'localVideo';
        //     }
        // }

        // check if the profile picture of the post user is a url
        // if (filter_var($post->user->pfp, FILTER_VALIDATE_URL)) {

        //     // index it to post user
        //     $post->user['pfp_type'] = 'imageUrl';
        // } else { // else it means it's a local file

        //     // index it to post user
        //     $post->user['pfp_type'] = 'localImage';
        // }
        
        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $postId)
    {
        $sessionUser = User::findOrFail(Auth::user()->id);

        if (isset(Auth::user()->followed)) {
            $followedUsersIds = Auth::user()->followed;
            $followedUsers = User::findOrFail($followedUsersIds);
            $sessionUser['followed_users'] = $followedUsers;
        } else {
            $followedUsersIds = null;
            $sessionUser['followed_users'] = null;
        }

        $request->session()->put('session_user', $sessionUser);

        


        $post = Post::findOrFail($postId);
        $user = User::findOrFail($post->user_id);
        
        foreach ($post->comments as $comment) {
            // check if the profile picture of the post user is a url
            // if (filter_var($comment->user->pfp, FILTER_VALIDATE_URL)) {

            //     // index it to comment user
            //     $comment->user['pfp_type'] = 'imageUrl';
            // } else { // else it means it's a local file

            //     // index it to comment user
            //     $comment->user['pfp_type'] = 'localImage';
            // }
            $comment['user'] = $comment->user;
   
        }
        

        // $commentModel = new Comment;
        // $comments = $commentModel->recursiveReplies();
        
        $post['comments']= $post->comments;
        $post['session_user']= $sessionUser;
        $post['user']=$user;

        // return view("posts.crud.show", compact('post'));

        
        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.crud.update', compact("post"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post)
    {
        
        $post->update(request()->validate([
            'description' => '',
            'type' => '',
            ]
        ));
        // event(new PostCreated($post));

        // return redirect("posts/" . $post->id)->with('success', 'Post Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        // check if it's both a post and a story
        if ($post->type == 'post/story' || $post->type == 'story/post') {
            // check if we want to delete post
            if ($request->type == 'post') {

                // update to story
                $post->update(array('type' => 'story'));

                // check if we want to delete story
            } else if ($request->type == 'story') {

                // update to post
                $post->update(array('type' => 'post'));
            }
        } else {
            $post->delete();
        }

        // return redirect('/posts')->with('success', 'Post deleted!');
        return $request;
    }

    // optional validation function not used, ( unproccessed entity error with vue js)
    protected  function validatedData()
    {
        return tap(request()->validate([
            'user_id' => 'required',
            // 'cover' => 'image|max:5000', #more image validation rules: https://laraveldaily.com/four-laravel-validation-rules-for-images-and-photos/
            // 'title' => 'max:255',
            'description' => 'required',
            'media_file' => 'required|file|mimes:jpg,png,jpeg,gif,svg,mp4,jpeg,png,bmp,svg,avi,mkv,mpeg|max:20000 ',
            'type' => '',
        ]), 
        function () {
            if (request()->hasFile('media_file')) {
                request()->validate([
                    'media_file' => 'file|mimes:jpg,png,jpeg,gif,svg,mp4,jpeg,png,bmp,svg,avi,mkv,mpeg|max:20000 ',
                ]);
            }
        }
    );

    }

    private function storeMediaFile($post)
    {
        if (request()->has('media_file')) {
            $post->update([
                'media_file' => request()->media_file
                // store('uploads', 'public')
            ]);
        }
    }


    public function like(Post $post, $userId, $object = 'post')
    {
        // fetch the user who made the post
        $postUser = User::findOrFail($post->user_id);

        // index the user id who liked the post into it
        $postUser['liker_id'] = $userId;

        // index the post id into it
        $postUser['liked_post'] = (string)$post->id;

        // if (Notification::pluck('notification_id')->last() != null) {
        //     $newNotifId = Notification::pluck('notification_id')->last() + 1;
        // } else {
        //     $newNotifId = 1;
        // }

        $data_notifications=[
            'object_id' => $post->id,
            'notification_message' => " has liked your post!",
        ];
        // FOR REAL TIME NOTIFICATION
        $notification =
        [
            // 'notification_id' => $newNotifId,
            'read' => 0,
            'data' => $data_notifications,
            'notifier' => User::findOrFail($userId),
        ];

        $postUser['real_time_notification'] = json_encode($notification);
        
        // event(new LikeEvent(($postUser)));
        broadcast(new LikeEvent($postUser)); 



        //remove the indexes created earlier
        unset($postUser['liker_id'], $postUser['liked_post']);

        //apply like function
        $sessionUser = $postUser->dynamicLike($post, $userId, $object);
        return $sessionUser;

        // return redirect('/' . $object . 's');

    }

    public function unlike(Post $post, $userId, $object = 'post')
    {
        $user = new User;
        $sessionUser = $user->dynamicUnlike($post, $userId, $object);
        return $sessionUser;

        // return redirect('/' . $object . 's');
    }

    public function favorite(Post $post, $userId)
    {
        // Fetch from database the user who will receive the id
        $user = \App\User::findorFail($userId);

        // Fetch the user's attribute who will store the id and assign it to a variable $favorite
        $favorite = $user->favorites;

    
        if ($favorite == null) {
            $favorite = [$post->id];
        } else {
            if ( array_search($post->id, $favorite) === false ) {
            array_push($favorite, $post->id);
            }
        
        }
        $user->favorites = $favorite;

        $user->save();


        // $user = new User;
        // $user->dynamicFollow($post->id, $userId, 'favorites');

        // return redirect('/posts');
    }

    public function unfavorite(Post $post, $userId)
    {
        $user = \App\User::findorFail($userId);
        $favorite = $user->favorites;


        // we need to use !== because we need to check for the datatype as well
        if (($key = array_search($post->id, $favorite)) !== false) {
            array_splice ($favorite, $key, 1);
            $user->favorites = $favorite;

            $user->save();
        }

        

        // $user = new User;
        // $user->dynamicUnfollow($post->id, $userId, 'favorites');

        // return redirect('/posts');
    }
}
