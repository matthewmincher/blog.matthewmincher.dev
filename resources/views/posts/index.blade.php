@extends('layouts.main')

@section('title', 'Blog')

@section('content')
    @forelse($posts as $post)
        <h2>{{$post->title}}</h2>
    @empty
        <h1>No posts</h1>
    @endforelse
@endsection
