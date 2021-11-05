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
                <li><a href="{{route('tags.index')}}" class="{{(request()->routeIs('tags.*')) ? 'active' : ''}}">Tags</a></li>
            </ul>
        </div>
    </nav>

    <div class="wrapper-content">
        @yield('content')
    </div>

    <footer class="main">
        <div class="constrainedContent">
            <div class="columns is-gapless is-mobile">
                <div class="column">
                    <span class="icon-text is-flex-wrap-nowrap">
                        <span class="icon">
                            <i class="fab fa-github"></i>
                        </span>
                         <a href="https://github.com/matthewmincher/blog.matthewmincher.dev" target="_blank" rel="noreferrer">View on Github</a>
                    </span>
                    <br />
                    <span class="icon-text is-flex-wrap-nowrap">
                        <span class="icon">
                            <i class="fas fa-copyright"></i>
                        </span>
                        Matthew Mincher 2021
                    </span>
                </div>
                <div class="column has-text-right">
                    <div>
                        @auth
                            <span class="icon-text is-flex-wrap-nowrap">
                                <a href="{{ route('logout') }}" class="">Log out</a>
                                <span class="icon">
                                    <i class="fas fa-power-off"></i>
                                </span>
                            </span>
                            <br />
                            <span class="icon-text is-flex-wrap-nowrap">
                                @if(auth()->user()->is_author)
                                    <a href="{{  route('users.show', ['user' => auth()->user()])  }}">{{auth()->user()->name}}</a>
                                @else
                                    {{auth()->user()->name}}
                                @endif
                                <span class="icon">
                                    <i class="fas fa-user"></i>
                                </span>
                            </span>


                        @else
                            <span class="icon-text is-flex-wrap-nowrap">
                                <a href="{{ route('login') }}" class="">Log in</a>
                                <span class="icon">
                                    <i class="fas fa-power-off"></i>
                                </span>
                            </span>
                        @endauth
                    </div>
                </div>
            </div>

        </div>
    </footer>
</body>

<script type="text/javascript" src="{{mix('js/posts/shared.js')}}"></script>
@stack('js')
</html>
