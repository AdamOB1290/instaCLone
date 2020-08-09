<div class="form-group">
    <label for="cover">Cover:</label>
    <input type="file" class="form-control-file" name="cover" id="cover" value="{{ old('cover') ?? $post->cover ?? '' }}">
    <p class="text-danger">{{$errors->first('cover')}}</p>
  </div>

  <div class="form-group">
      <label for="media_file">Content(Image or Video):</label>
      <input type="file" class="form-control-file" name="media_file" id="media_file" value="{{ old('media_file') ?? $post->media_file ?? ''  }}">
      <p class="text-danger">{{$errors->first('media_file')}}</p>
  </div>
  
  <div class="form-group">    
      <label for="title">Title:</label>
      <input type="text" class="form-control" name="title" id="title" value="{{ old('title') ?? $post->title ?? ''  }}"/>
      <p class="text-danger">{{$errors->first('title')}}</p>
  </div>

  <div class="form-group">    
      <label for="description">Description:</label>
      <textarea cols="30" rows="7" class="form-control" name="description" id="description" value="{{ old('description') ?? $post->description ?? ''  }}"></textarea>
      <p class="text-danger">{{$errors->first('description')}}</p>
  </div>