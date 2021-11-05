@extends('layouts.main')

@section('title', 'Blog')

@section('content')
    <div class="constrainedContent pt-4 is-relative">
        <div class="columns is-mobile">
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
