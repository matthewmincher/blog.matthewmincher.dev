<div class="box">
    <div class="columns is-mobile">
        <div class="column is-four-fifths">
            <h2 class="title is-5"><a href="{{route('categories.show', ['blog_category' => $category])}}">{{$category->title}}</a></h2>
            <h3 class="subtitle is-7">{{$category->posts_count}} @choice('post|posts', $category->published_posts_count)</h3>
        </div>
        <div class="column has-text-right">
            @can('update', $category)
                <a href="{{route('categories.edit', ['blog_category' => $category])}}" class="button is-small is-light">
                            <span class="icon is-small">
                              <i class="fas fa-pencil-alt"></i>
                            </span>
                </a>
            @endcan
        </div>
    </div>

    <div class="content">
        {{$category->content}}
    </div>
</div>
