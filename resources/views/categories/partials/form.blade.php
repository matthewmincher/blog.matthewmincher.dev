<div class="field">
    <label class="label">Title</label>
    <div class="control">
        <input id="title" name="title" class="input{{($errors->has('title')) ? ' is-danger' : ''}}" type="text" value="{{old('title', $category->title ?? '')}}">
    </div>
    @error('title')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Content</label>
    <div class="control">
        <textarea id="content" name="content" class="textarea" placeholder="">{{old('content', $category->content ?? '')}}</textarea>
    </div>
    @error('content')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<hr />

<div class="buttons">
    <button class="button is-primary" type="submit">Save Category</button>
</div>

