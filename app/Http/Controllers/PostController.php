<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view("posts.crud.index")->with("posts", $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("posts.crud.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $post = Post::create($this->validatedData());

        // $this->storeMediaFile($post);

        return redirect("/posts")->with('success', 'Post Uploaded Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view("posts.crud.show", compact('post'));
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

        $post->update($this->validatedData());

        $this->storeMediaFile($post);

        return redirect("/posts" . $post->id)->with('success', 'Post Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/posts')->with('success', 'Post deleted!');
    }

    protected  function validatedData()
    {

        return tap(request()->validate([
            'user_id' => 'required',
            'cover' => 'image|max:5000', #more image validation rules: https://laraveldaily.com/four-laravel-validation-rules-for-images-and-photos/
            'title' => 'max:255',
            'description' => '',
            // 'media_file' => 'required|mimetypes:mp4, jpeg, png, bmp, gif, svg, avi,jpg, png, jpeg, gif, svg| max:20000',#'mimetypes:video/mp4,video/jpeg,video/png,video/bmp,video/gif,video/svg,video/avi,image/jpg, image/png, image/jpeg, image/gif, image/svg| max:20000',
            'media_file' => 'required',
        ]), function () {
            if (request()->hasFile('media_file')) {
                request()->validate([
                    'media_file' => 'file|mimes:jpg,png,jpeg,gif,svg,mp4,jpeg,png,bmp,svg,avi,mkv,mpeg|max:20000 ',
                ]);

                // $fileRules = [
                //     'media_file' => 'file',
                // ];

                // //  if fileType is image
                // if ($this->input('fileType') == 'image') {
                //     $fileRules['file'] = 'image|mimetypes:image/jpg, image/png, image/jpeg, image/gif, image/svg|max:5000';
                // }

                // //if fileType is video
                // if ($this->input('fileType') == 'video') {
                //     $fileRules['file'] = 'mimetypes:video/mp4,video/jpeg,video/png,video/bmp,video/gif,video/svg,video/avi| max:20000';
                // }

                // return $fileRules;
            }
        });
    }

    // private function storeMediaFile($post)
    // {
    //     if (request()->has('media_file')) {
    //         $post->update([
    //             'media_file' => request()->media_file->store('uploads', 'public')
    //         ]);
    //     }
    // }


    // public function like(Post $post, $userId)
    // {

    //     $post->likes += 1;
    //     $post->update(array('likes' => $post->likes));
    //     $user = \App\User::findorFail($userId);
    //     $liked = $user->liked;

    //     array_push($liked['posts'], $post->id);

    //     $user->liked = $liked;

    //     $user->save();
    //     // return view("posts.crud.test", compact('liked'));
    //     return redirect('/posts');

    // }

    // public function unlike(Post $post, $userId){

    //     $post->likes -= 1;
    //     $post->update(array('likes' => $post->likes));
    //     $user = \App\User::findorFail($userId);
    //     $liked = $user->liked;

    //     if (($key = array_search($post->id, $liked['posts'])) !== false) {
    //         unset($liked['posts'][$key]);

    //     }

    //     $user->liked = $liked;

    //     $user->save();
    //     return redirect('/posts');
    // }

    public function like(Post $post, $userId, $object = 'post')
    {
        $user = new User;
        $user->dynamicLike($post, $userId, $object);
        return redirect('/' . $object . 's');
    }

    public function unlike(Post $post, $userId, $object = 'post')
    {
        $user = new User;
        $user->dynamicUnlike($post, $userId, $object);
        return redirect('/' . $object . 's');
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
            array_push($favorite, $post->id);
        }

        $user->favorites = $favorite;

        $user->save();

        // $user = new User;
        // $user->dynamicFollow($post->id, $userId, 'favorites');

        return redirect('/posts');
    }

    public function unfavorite(Post $post, $userId)
    {
        $user = \App\User::findorFail($userId);
        $favorite = $user->favorites;

        if (($key = array_search($post->id, $favorite)) !== false) {
            unset($favorite[$key]);
        }

        $user->favorites = $favorite;

        $user->save();

        // $user = new User;
        // $user->dynamicUnfollow($post->id, $userId, 'favorites');

        return redirect('/posts');
    }
}

  
        
        
        
        
