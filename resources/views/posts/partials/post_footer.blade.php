<div class="mt-5">
    <span class="icon-text">
        <span class="icon">
            <i class="fas fa-bookmark"></i>
        </span>
        <a href="{{route('categories.show', ['blog_category' => $post->category])}}">
            <span>
                {{$post->category->title}}
            </span>
        </a>
    </span>
</div>
@if($post->tags->isNotEmpty())
    <div>
        <span class="icon-text is-flex-wrap-nowrap">
            <span class="icon">
                <i class="fas fa-tags"></i>
            </span>
            <div class="tags">
                @foreach($post->tags as $tag)
                    <a class="tag" href="{{route('tags.show', ['blog_tag' => $tag])}}">{{$tag->title}}</a>
                @endforeach
            </div>

        </span>
    </div>
@endif
@if($post->comments_count > 0)
    <div>
        <span class="icon-text">
            <span class="icon">
                <i class="fas fa-comment"></i>
            </span>
            <span>
                {{$post->comments_count}} {{\Illuminate\Support\Str::plural('comment', $post->comments_count)}}
            </span>

        </span>
    </div>
@endif

