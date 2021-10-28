@extends('layouts.main')

@section('title', 'New Category')

@section('content')
    <div class="constrainedContent">
        <h1 class="title mt-4">
            New Category
        </h1>

        <form method="post" action="{{ route('categories.store') }}">
            @csrf

            @include('categories.partials.form')
        </form>
    </div>
@endsection
