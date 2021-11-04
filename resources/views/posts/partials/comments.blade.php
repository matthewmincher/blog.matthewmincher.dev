<div class="commentsDivider">
    <hr />

    <span class="icon is-large">
        <i class="far fa-comment fa-2x"></i>
    </span>
</div>

<div class="postComments">
    @forelse($post->comments as $comment)
        @include('posts.partials.post_comment')
    @empty
        <div class="has-text-centered has-text-grey">
            No comments yet...
        </div>
    @endforelse
</div>

@can('create', \App\Models\Comment::class)
<hr />

<form id="commentform" class="" method="POST" action="{{route('posts.comments.store', ['blog_post' => $post]) . '#commentform'}}">
    @csrf

    <div class="field is-grouped">
        <div class="control is-expanded">
            <textarea
                name="content" data-autogrow="5"  rows="1" placeholder="Leave a comment..." minlength="10" required maxlength="2500"
                @class(['textarea', 'has-fixed-size', 'is-danger' => $errors->has('content')])>{{old('content')}}</textarea>
            <div class="columns">
                <div class="column">
                    @error('content')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="column">
                    <p class="help has-text-grey has-text-right">
                        Commenting as {{auth()->user()->name}}
                    </p>
                </div>
            </div>

        </div>
        <div class="control">
            <button type="submit" class="button" style="width: 48px; height: 48px">
                <span class="icon">
                  <i class="fas fa-paper-plane"></i>
                </span>
            </button>
        </div>

    </div>

</form>
@else
    <div class="has-text-centered has-text-grey mt-5">
        <div class="level is-inline-flex">
            <a class="button level-item" href="{{route('login')}}">Log in</a>
            <span class="level-item ml-2">to leave a comment</span>
        </div>

    </div>
@endcan
