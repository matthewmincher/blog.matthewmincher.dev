@extends('layouts.main')

@section('title', 'New Tag')

@section('content')
    <div class="constrainedContent">
        <h1 class="title mt-4">
            New Tag
        </h1>

        @error('generic')
        <div class="notification is-danger">
            {{$message}}
        </div>
        @enderror

        <form method="post" action="{{ route('tags.store') }}">
            @csrf

            @include('tags.partials.form')
        </form>
    </div>
@endsection
