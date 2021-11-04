<article class="media">
    <figure class="media-left mr-2">
        <p class="image is-32x32">
            <img class="is-rounded" src="{{ $comment->user->picture_with_fallback }}" />
        </p>
    </figure>
    <div class="media-content">
        <div class="content">
            <p>
                <strong class="is-size-5">{{$comment->user->name}}</strong>

                <small class="ml-1 has-text-grey" title="{{$comment->created_at->toRssString()}}">
                    @if($comment->created_at > now()->subDay())
                        {{$comment->created_at->diffForHumans()}}
                    @else
                        <span title="{{$comment->created_at->toRssString()}}">{{$comment->created_at->format('jS M \a\t g:ia')}}</span>
                    @endif
                </small>
                <br />

                {{$comment->content}}
            </p>
        </div>
    </div>
    @can('delete', $comment)
        <form class="is-inline-block" method="POST" action="{{route('posts.comments.destroy', ['blog_post' => $post, 'comment' => $comment])}}">
            @csrf
            @method('DELETE')
            <button type="submit" class="button is-small is-light">
                <span class="icon is-small">
                  <i class="fas fa-trash"></i>
                </span>
            </button>
        </form>
    @endcan
</article>
