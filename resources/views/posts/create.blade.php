@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ mix('css/posts/edit.css') }}">
@endpush
@push('js')
    <script type="text/javascript">
        window.tagWhitelist = @json($tagNames);
    </script>
    <script type="module" src="{{ mix('js/posts/edit.js') }}" defer></script>
@endpush

@section('title', 'New Post')

@section('content')
    <div class="constrainedContent content">
        <h1 class="title mt-4">
            New Blog Post
        </h1>

        <form method="post" action="{{ route('posts.store') }}">
            @csrf

            @include('posts._form')
        </form>
    </div>
@endsection
