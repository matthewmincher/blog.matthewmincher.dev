@extends('layouts.main')

@section('title', 'Blog → ' . $tag->title)

@section('content')
    <div class="constrainedContent pt-4 is-relative">
        <div class="columns">
            <div class="column is-four-fifths">
                <h1 class="title">{{$tag->title}}</h1>
                <h3 class="subtitle is-6">{{$posts->total()}} @choice('post|posts', $posts->total())</h3>
                <p>{{$tag->content}}</p>
            </div>
            <div class="column has-text-right">
                @can('update', $tag)
                    <a href="{{route('tags.edit', ['blog_tag' => $tag])}}" class="button is-small is-light">
                    <span class="icon is-small">
                      <i class="fas fa-pencil-alt"></i>
                    </span>
                    </a>
                @endcan
                @can('delete', $tag)
                    <form class="is-inline-block" method="POST" action="{{route('tags.destroy', ['blog_tag' => $tag])}}">
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

        <hr />

        @forelse($posts as $post)
            @include('posts.partials.preview')
        @empty
            <article class="message">
                <div class="message-body">
                    <h2 class="subtitle">No posts</h2>
                    Nothing to display right now
                </div>
            </article>
        @endforelse

        {{$posts->links('posts.partials.paging')}}
    </div>
@endsection
