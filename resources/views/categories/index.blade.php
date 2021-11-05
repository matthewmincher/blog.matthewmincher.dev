@extends('layouts.main')

@section('title', 'Blog → Categories')

@section('content')
    <div class="constrainedContent pt-4 is-relative">
        <div class="columns is-mobile">
            <div class="column is-four-fifths">
                <h1 class="title">All Categories (A-Z)</h1>
            </div>
            <div class="column has-text-right">
                @can('create', \App\Models\BlogCategory::class)
                <a href="{{route('categories.create')}}" class="button is-small is-primary">
                    <span class="icon is-small">
                      <i class="fas fa-plus"></i>
                    </span>
                </a>
                @endcan
            </div>
        </div>

        @forelse($categories as $category)
            @include('categories.partials.preview')
        @empty
            <article class="message">
                <div class="message-body">
                    <h2 class="subtitle">No categories found</h2>
                    Nothing to display right now
                </div>
            </article>
        @endforelse
    </div>
@endsection
