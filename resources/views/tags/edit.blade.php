@extends('layouts.main')

@section('title', 'Edit Tag')

@section('content')
    <div class="constrainedContent">
        <h1 class="title mt-4">
            Edit Tag
        </h1>

        @error('generic')
        <div class="notification is-danger">
            {{$message}}
        </div>
        @enderror

        <form method="post" action="{{ route('tags.update', ['blog_tag' => $tag]) }}">
            @csrf
            @method('PUT')

            @include('tags.partials.form')
        </form>
    </div>
@endsection
