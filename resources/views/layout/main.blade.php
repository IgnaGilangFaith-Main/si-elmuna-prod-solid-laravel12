<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('asset/img/icon1.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('/bootstrap-5.1.3-dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @stack('css')
</head>

<body>
    <div class="min-vh-100 justify-content-between">
        {{-- Navbar  --}}
        <nav class="navbar navbar-dark navbar-expand-lg bg-dark sticky-top">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <a href="/" data-aos="fade" class="">
                        <img src="{{ asset('asset/img/elmuna2.png') }}" alt="Logo" width="40%">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            @if (auth()->check())
                                <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                            @else
                                <a class="nav-link" href="{{ url('/login') }}">Login</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container my-3">
            @yield('content')
        </div>

        {{-- Footer  --}}
        <footer class="py-5 bg-dark">
            <div>
                <p class="m-0 text-center text-white">Copyright &copy; Elmuna Kebumen {{ date('Y') }}</p>
            </div>
        </footer>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="{{ url('/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        AOS.init();
    </script>
    @stack('js')
</body>

</html>
