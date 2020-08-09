<p>Hidden 'User id' Input: {{ session('user_id') }}</p>
<input type="hidden" name='user_id' value="{{session('user_id')}}">
<p>Hidden 'Parent id' Input: {{ $parentId ?? $comment->parent_comment_id  }}</p>
<input type="hidden" name="parent_comment_id" id="parent_comment_id" value="{{ $parentId ?? $comment->parent_comment_id  }}">
<p>Hidden 'Post id' Input: {{ $comment->post_id ?? $postId }}</p>
<input type="hidden" name="post_id" id="post_id" value="{{ $comment->post_id ?? $postId }}">

<div class="form-group">    
    <label for="content">Content:</label>
    <textarea cols="30" rows="7" class="form-control" name="content" id="content" >{{ old('content') ?? $comment->content ?? '' }}</textarea>
    <p class="text-danger">{{$errors->first('content')}}</p>
</div>
