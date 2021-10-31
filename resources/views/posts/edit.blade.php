@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ mix('css/posts/edit.css') }}">
@endpush
@push('js')
    <script type="text/javascript">
        window.tagWhitelist = @json($tagNames);
    </script>
    <script type="module" src="{{ mix('js/posts/edit.js') }}"></script>
@endpush

@section('title', 'Edit Post')

@section('content')
    <div class="constrainedContent">
        <h1 class="title mt-4">
            Edit Post
        </h1>

        <form method="post" action="{{ route('posts.update', ['blog_post' => $post]) }}">
            @csrf
            @method('PUT')

            @include('posts.partials.form')
        </form>
    </div>
@endsection
