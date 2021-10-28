@extends('layouts.main')

@section('title', 'Blog → ' . $post->title)

@section('content')
    <div class="constrainedContent pt-4 is-relative">
        <div class="columns">
            <div class="column is-four-fifths">
                <h1 class="title">{{$post->title}}</h1>
                <h2 class="subtitle is-6">
                    Posted by {{$post->user->name}}

                    @if($post->created_at > now()->subDay())
                        {{$post->created_at->diffForHumans()}}
                    @else
                        on <span title="{{$post->created_at->toDayDateTimeString()}}">{{$post->created_at->format('jS M \a\t g:ia')}}</span>
                    @endif
                </h2>
            </div>
            <div class="column has-text-right">
                @can('create', \App\Models\BlogPost::class)
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
            {!! Markdown::parse($post->content) !!}
        </div>

        @include('posts.partials.post_footer')
    </div>
@endsection
