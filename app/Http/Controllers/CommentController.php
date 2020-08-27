<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentCreated;
use App\Events\LikeEvent;
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

        // return view("comments.crud.index")->with("comments", $comments);
        return $comments;
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


        event(new CommentCreated($comment));

        $user = User::findOrFail($comment['user_id']);

        // check if the profile picture of the session user is a url
        if (filter_var($user->pfp, FILTER_VALIDATE_URL)) {

            // index it to session user
            $user['pfp_type'] = 'imageUrl';
        } else { // else it means it's a local file

            // index it to session user
            $user['pfp_type'] = 'localImage';
        }
        
        $comment['user'] = $user;
        $comment['replies'] = [];
        $comment['likes'] = [];
        
        // return redirect("/comments")->with('success', 'Comment Uploaded Successfully!');
        return $comment;
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
        return redirect('/comments');
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
        // fetch the user who made the comment
        $commentUser = User::findOrFail($comment->user_id);

        // index the user id who liked the comment into it
        $commentUser['liker_id'] = $userId;

        // index the comment id into it
        $commentUser['liked_comment'] = (string)$comment->id;

        event(new LikeEvent($commentUser));

        //remove the indexes created earlier
        unset($commentUser['liker_id'], $commentUser['liked_comment']);

        //apply like function
        $sessionUser = $commentUser->dynamicLike($comment, $userId, $object);
        // return redirect('/' . $object . 's');
        return $sessionUser;
    }

    public function unlike(Comment $comment, $userId, $object = 'comment')
    {
        $user = new User;
        $sessionUser = $user->dynamicUnlike($comment, $userId, $object);
        // return redirect('/' . $object . 's');
        return $sessionUser;
    }
}
