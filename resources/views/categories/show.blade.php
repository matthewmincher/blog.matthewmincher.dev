@extends('layouts.main')

@section('title', 'Blog → ' . $category->title)

@section('content')
    <div class="constrainedContent pt-4 is-relative">

        @error('generic')
        <div class="notification is-danger">
            {{$message}}
        </div>
        @enderror

        <div class="columns">
            <div class="column is-four-fifths">
                <h1 class="title">{{$category->title}}</h1>
                <h3 class="subtitle is-6">{{$posts->total()}} @choice('post|posts', $posts->total())</h3>
                <p>{{$category->content}}</p>
            </div>
            <div class="column has-text-right">
                @can('create', \App\Models\BlogCategory::class)
                    <a href="{{route('categories.edit', ['blog_category' => $category])}}" class="button is-small is-light">
                    <span class="icon is-small">
                      <i class="fas fa-pencil-alt"></i>
                    </span>
                    </a>
                @endcan
                @can('delete', $category)
                    <form class="is-inline-block" method="POST" action="{{route('categories.destroy', ['blog_category' => $category])}}">
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
