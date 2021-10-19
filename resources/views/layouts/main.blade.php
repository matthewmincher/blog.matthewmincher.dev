<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ mix('css/blog.css') }}">
    <title>@yield('title') | matthewmincher.dev</title>
</head>

<body class="container">
    <nav class="top">
        <div class="container">
            <ul class="navbar-start">
                <li><a href="//www.matthewmincher.dev">Home</a></li>
                <li><a href="//www.matthewmincher.dev/cv">CV</a></li>
                <li><a class="active" aria-current="page" href="/posts/">Blog</a></li>
            </ul>

            <ul class="navbar-end">
                <li><a href="//www.matthewmincher.dev/contact">Get in Touch</a></li>
            </ul>
        </div>
    </nav>

    <div class="content">
        @yield('content')
    </div>

    <footer class="main">
        <div class="constrainedContent">
            © Matthew Mincher 2021
        </div>
    </footer>
</body>


</html>
