@extends('layouts.main')

@section('title', 'Blog')

@section('content')
    <div class="constrainedContent pt-4 is-relative">
        <div class="columns">
            <div class="column is-four-fifths">
                <h1 class="title">Blog Posts</h1>
            </div>
            <div class="column has-text-right">
                @can('create', \App\Models\BlogPost::class)
                <a href="{{route('posts.create')}}" class="button is-small is-primary">
                    <span class="icon is-small">
                      <i class="fas fa-plus"></i>
                    </span>
                </a>
                @endcan
            </div>
        </div>

        @forelse($posts as $post)
            <div class="box">
                <div class="columns">
                    <div class="column is-four-fifths">
                        <h2 class="title is-5"><a href="{{route('posts.show', ['blog_post' => $post])}}">{{$post->title}}</a></h2>
                        <h3 class="subtitle is-7">
                            Posted by {{$post->user->name}}

                            @if($post->created_at > now()->subDay())
                                {{$post->created_at->diffForHumans()}}
                            @else
                                on <span title="{{$post->created_at->toDayDateTimeString()}}">{{$post->created_at->format('jS M \a\t g:ia')}}</span>
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
                    </div>
                </div>

                <div class="content">
                    {!! Markdown::parse($post->content) !!}
                </div>


                <div class="mt-6">
                    <span class="icon-text">
                        <span class="icon">
                            <i class="fas fa-bookmark"></i>
                        </span>
                        <a href="#">
                            <span>
                                {{$post->category->title}}
                            </span>
                        </a>
                    </span>
                </div>
                @if($post->tags)
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
            </div>
        @empty
            <article class="message">
                <div class="message-body">
                    <h2 class="subtitle">No posts</h2>
                    Nothing to display right now
                </div>
            </article>
        @endforelse

        {{$posts->links('posts._paging')}}
    </div>
@endsection
