<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/bulma.css') }}">
    <link rel="stylesheet" href="{{ mix('css/blog.css') }}">
    @stack('css')
    <title>@yield('title') | matthewmincher.dev</title>
</head>

<body class="wrapper">
    <nav class="top">
        <div class="wrapper">
            <ul class="navbar-start">
                <li><a href="//www.matthewmincher.dev">Home</a></li>
                <li><a href="//www.matthewmincher.dev/cv">CV</a></li>
                <li><a class="active" aria-current="page" href="{{route('posts.index')}}">Blog</a></li>
            </ul>

            <ul class="navbar-end">
                <li><a href="//www.matthewmincher.dev/contact">Get in Touch</a></li>
            </ul>
        </div>
    </nav>

    <nav class="top-sub">
        <div class="wrapper">
            <ul class="navbar-start">
                <li><a href="{{route('posts.index')}}" class="{{(request()->routeIs('posts.*')) ? 'active' : ''}}">Posts</a></li>
                <li><a href="{{route('categories.index')}}" class="{{(request()->routeIs('categories.*')) ? 'active' : ''}}">Categories</a></li>
                <li><a href="#" class="{{(request()->routeIs('tags.*')) ? 'active' : ''}}">Tags</a></li>
            </ul>
        </div>
    </nav>

    <div class="wrapper-content">
        @yield('content')
    </div>

    <footer class="main">
        <div class="constrainedContent">
            <div class="columns is-gapless">
                <div class="column">
                    © Matthew Mincher 2021
                </div>
                <div class="column has-text-right">
                    <div>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="">Log in</a>
                        @endauth
                    </div>
                </div>
            </div>

        </div>
    </footer>
</body>

@stack('js')
</html>
