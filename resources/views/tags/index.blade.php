@extends('layouts.main')

@section('title', 'Blog → Tags')

@section('content')
    <div class="constrainedContent pt-4 is-relative">
        <div class="columns">
            <div class="column is-four-fifths">
                <h1 class="title">All Tags</h1>
            </div>
            <div class="column has-text-right">
                @can('create', \App\Models\BlogTag::class)
                <a href="{{route('tags.create')}}" class="button is-small is-primary">
                    <span class="icon is-small">
                      <i class="fas fa-plus"></i>
                    </span>
                </a>
                @endcan
            </div>
        </div>

        @if($tags->isNotEmpty())
            <div class="field is-grouped is-grouped-multiline is-justify-content-center">
            @foreach($tags as $tag)
                <div class="control">
                    <div class="tags has-addons are-large">
                        <a class="tag" href="{{route('tags.show', ['blog_tag' => $tag])}}">{{$tag->title}}</a>
                        <span class="tag is-dark">{{$tag->posts_count}}</span>
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <article class="message">
                <div class="message-body">
                    <h2 class="subtitle">No tags found</h2>
                    Nothing to display right now
                </div>
            </article>
        @endif


        @forelse($tags as $tag)

        @empty

        @endforelse
    </div>
@endsection
