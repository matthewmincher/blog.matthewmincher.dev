@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ mix('css/editpost.css') }}">
@endpush
@section('title', 'New Post')

@section('content')
    <div class="constrainedContent content">
        <h1 class="title mt-4">
            New Blog Post
        </h1>

        <form method="post">
            @csrf

            <div class="field">
                <label class="label">Title</label>
                <div class="control">
                    <input id="title" name="title" class="input" type="text" value="{{old('title')}}">
                </div>
            </div>

            <div class="field">
                <label class="label">Content</label>
                <div class="control">
                    <textarea id="body" name="body" class="textarea" placeholder=""></textarea>
                </div>
            </div>

            <div class="buttons">
                <a class="button is-primary">Primary</a>
                <a class="button is-link">Link</a>
            </div>

        </form>
    </div>
@endsection
