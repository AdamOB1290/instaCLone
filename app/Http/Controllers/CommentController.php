<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();

        return view("comments.crud.index")->with("comments", $comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($parentId = null, $postId = null, $userID = null)
    {
        if ($parentId == null) {
            $parentId = 0;
        }
        if ($postId == null) {
            $postId = \App\Post::all()->random()->id;
        }
        if ($userID == null) {
            $userID = \App\User::all()->random()->id;
        }

        // $parentId = 4;

        // dd($parentId);
        // echo $parentId;
        $comment = new Comment();

        return view("comments.crud.create", compact('comment', 'parentId', 'postId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = Comment::create($this->validatedData());


        return redirect("/comments")->with('success', 'Comment Uploaded Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        $replies = Comment::where('parent_comment_id', $comment->id)->get();;
        return view("comments.crud.show", compact('comment', 'replies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment, $userID = null)
    {
        if ($userID == null) {
            $userID = \App\User::all()->random()->id;
        }

        return view('comments.crud.update', compact("comment"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update($this->validatedData());

        return redirect('comments/' . $comment->id)->with('success', 'Comment Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect('/comments')->with('success', 'Comment deleted!');
    }

    protected  function validatedData()
    {

        return request()->validate([
            'user_id' => 'required',
            'post_id' => 'required',
            'parent_comment_id' => 'required',
            'content' => 'required|max:255',
        ]);
    }
}
