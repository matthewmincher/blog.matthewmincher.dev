<div class="mt-6">
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
        <span class="icon-text">
            <span class="icon">
                <i class="fas fa-tags"></i>
            </span>
            <div class="tags">
                @foreach($post->tags as $tag)
                    <a class="tag">{{$tag->title}}</a>
                @endforeach
            </div>

        </span>
    </div>
@endif
