<div class="box" @if(!$post->published)style="background-color: #ffcece;"@endif>
    <div class="columns">
        <div class="column is-four-fifths">
            <h2 class="title is-5"><a href="{{route('posts.show', ['blog_post' => $post])}}">{{$post->title}}</a></h2>
            <h3 class="subtitle is-7">
                Posted by {{$post->user->name}}

                @if($post->created_at > now()->subDay())
                    <span title="{{$post->created_at->toRssString()}}">{{$post->created_at->diffForHumans()}}</span>
                @else
                    on <span title="{{$post->created_at->toRssString()}}">{{$post->created_at->format('jS M \a\t g:ia')}}</span>
                @endif
            </h3>
        </div>
        <div class="column has-text-right">
            @can('update', $post)
                <a href="{{route('posts.edit', ['blog_post' => $post])}}" class="button is-small is-light">
                            <span class="icon is-small">
                              <i class="fas fa-pencil-alt"></i>
                            </span>
                </a>
            @endcan
                @can('delete', $post)
                    <form class="is-inline-block" method="POST" action="{{route('posts.destroy', ['blog_post' => $post])}}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button is-small is-light">
                            <span class="icon is-small">
                              <i class="fas fa-trash"></i>
                            </span>
                        </button>
                    </form>
                @endcan
        </div>
    </div>

    <div class="content">
        {!! Markdown::parse(\Illuminate\Support\Str::before($post->content, '----')) !!}
    </div>

    @include('posts.partials.post_footer')
</div>
