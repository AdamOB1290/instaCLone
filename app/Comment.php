<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }


    //recursive function to delete all parents( $parentId is the function parameter)
    public function delete_thread($comment, $parentId)
    {
        $new_parent_id = 0;

        //check for delete state of the parent of comment
        $parent_delete_state = Comment::where('id', $parentId)->first();

        if ($parent_delete_state->delete_state==1) { // if parent has been deleted

            //get the id of the parent's parent
            $new_parent_id = $parent_delete_state->parent_comment_id;
            
            //check for undeleted siblings
            $request_siblings = [
                'parent_comment_id' => $new_parent_id,
                'delete_state' => 0,
            ];
            $siblings_count = count(Comment::where($request_siblings)->get());

            // delete the current comment 
            $parent_delete_state->delete();

            // then feed it's parent_id (previously saved) to the recursive function again
            if ($siblings_count == 0) { // if no undeleted siblings
                if ($new_parent_id != 0) { // if current comment to be deleted has parent
                    $comment->delete_thread($new_parent_id);
                    
                }
            }
            
        }
        
    }

    // public function recursiveReplies($comments) {
    //     foreach ($comments as $comment) {
    //         $replies = Comment::where('parent_comment_id', $comment->id)->get();
    //         $repliesToIndex = $this->recursiveReplies($replies);
    //         $comment['replies'] = $repliesToIndex;
    //     }
    //     return $comments;
    // }
    
}
