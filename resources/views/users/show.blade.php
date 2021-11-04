@extends('layouts.main')

@section('title', 'Blog → User')

@section('content')
    <div class="constrainedContent pt-4">
        <h1 class="title">User Page</h1>
        <h2 class="subtitle">{{$user->name}}</h2>

        <form action="{{route('users.update', ['user' => $user])}}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label class="label" for="email">Email address</label>
                <div class="field-body">
                    <div class="field is-expanded">
                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input @class(['input', 'is-danger' => $errors->has('email')]) type="text" name="email" id="email" value="{{old('email', $user->email ?? '')}}">
                            </div>
                            <div class="control">
                                <button type="submit" @class(['button', 'is-danger' => $errors->has('email')])>
                                    Save
                                </button>
                            </div>
                        </div>
                        @error('email')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </form>

        <hr />

        <form action="{{route('users.preferences.store', ['user' => $user])}}" method="POST">
            @csrf

            <label class="label">Notifications</label>
            <div class="field is-grouped is-grouped-multiline">
                @foreach($preferences as $key => $value)
                    <div class="control">
                        <label class="checkbox">
                            <input type="checkbox" name="{{$key}}" {{$value ? 'checked' : ''}}>
                            {{__("userpreferences.$key")}}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="control">
                <button class="button" type="submit">Save</button>
            </div>


        </form>

    </div>
@endsection
