@extends('layouts.main')

@section('title', 'Edit Category')

@section('content')
    <div class="constrainedContent">
        <h1 class="title mt-4">
            Edit Category
        </h1>

        <form method="post" action="{{ route('categories.update', ['blog_category' => $category]) }}">
            @csrf
            @method('PUT')

            @include('categories.partials.form')
        </form>
    </div>
@endsection
