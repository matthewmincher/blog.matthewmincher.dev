<div class="field">
    <label class="label">Title</label>
    <div class="control">
        <input id="title" name="title" class="input{{($errors->has('title')) ? ' is-danger' : ''}}" type="text" value="{{old('title')}}">
    </div>
    @error('title')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Category</label>
    <div class="control">
        <select id="blog_category_id" name="blog_category_id" class="input{{($errors->has('blog_category_id')) ? ' is-danger' : ''}}">
            @foreach($categories as $category)
                <option value="{{$category->id}}" {{old('blog_category_id') === $category->id ? 'selected' : ''}}>{{$category->title}}</option>
            @endforeach
        </select>
    </div>
    @error('blog_category_id')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Tags</label>

    <div class="control">
        <input id="tagInput" class="input" type="text" name="tags" value="{{old('tags')}}" />
    </div>
    @error('tags')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
    @error('tags.*')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Content</label>
    <div class="control">
        <textarea id="content" name="content" class="textarea" placeholder="" style="height: 350px; visibility: hidden">{{old('content')}}</textarea>
    </div>
    @error('content')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>



<div class="buttons">
    <button class="button is-primary" type="submit">Add Post</button>
</div>

