<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;
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
    public function create($parentId = null, $postId = null)
    {
        if ($parentId == null) {
            $parentId = 0;
        }
        if ($postId == null) {
            $postId = \App\Post::all()->random()->id;
        }


        // $parentId = 4;

        // dd($parentId);
        // echo $parentId;
        // $comment = new Comment();

        return view("comments.crud.create", compact('parentId', 'postId'));
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
        $replies = Comment::where('parent_comment_id', $comment->id)->get();
        return view("comments.crud.show", compact('comment', 'replies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
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
        // $comment->delete();
        // return redirect('/comments')->with('success', 'Comment deleted!');

        //check for children
        $children = Comment::where('parent_comment_id', $comment->id)->get();
        $count = count($children);

        if ($count == 0) { //if it has no children
            //check for parent
            $parent_id = Comment::where('id', $comment->id)->pluck('parent_comment_id');

            if ($parent_id[0] != 0) { //if it has a parent

                //check for siblings with undeleted status ( meaning delete_state = 0)
                $request_siblings = [
                    'parent_comment_id' => $parent_id,
                    'delete_state' => 0,
                ];
                $siblings = Comment::where($request_siblings)->get();
                $count = count($siblings);

                if ($count == 1) { //if there are no undeleted siblings

                    // run recursive function ( located in Comment model ) to delete the comment thread :

                    $comment->delete_thread($comment, $parent_id[0]);

                    // delete the current comment 
                    $comment->delete();
                } else {
                    // delete the current comment 
                    $comment->delete();
                }
            } else {
                // delete the current comment 
                $comment->delete();
            }
        } else { // update the current comment instead of delete ( since it has a child)
            $comment->where('id', $comment->id)->update(['delete_state' => 1]);
        }
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

    public function like(Comment $comment, $userId, $object = 'comment')
    {
        $user = new User;
        $user->dynamicLike($comment, $userId, $object);
        return redirect('/' . $object . 's');
    }

    public function unlike(Comment $comment, $userId, $object = 'comment')
    {
        $user = new User;
        $user->dynamicUnlike($comment, $userId, $object);
        return redirect('/' . $object . 's');
    }
}
