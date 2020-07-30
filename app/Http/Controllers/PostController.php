<?php

namespace App\Http\Controllers;

use App\Post;
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
        $post = new Post();

        return view("posts.crud.create", compact('post'));
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
    public function update(Request $request, Post $post)
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

}
