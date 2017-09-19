<html>
    <head>
        <title>BWeb - @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        <b>This is a work in progress.</b> Please report bugs and feedback. Thank you.
                    </div>
                </div>
                @yield('content')
                <div class="col-12">
                    <p class="text-center mt-3">© Dylan Seidt 2017. BWeb <code>@php exec('git log --pretty="%h" -n1 HEAD 2> /dev/null', $output); echo $output[0]; @endphp</code><br>Renweb is a copyright of Wilcomp Software LLC and is not affiliated with this site.</p>
                </div>
            </div>
        </div>
    </body>
    <script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    </script>
</html>
