<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
</head>
<body>
    <main class="kp-auth">
        <div class="kp-auth__box">
            @yield('content')
        </div>
    </main>

    @include('partials.flash')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/kitapamer.js') }}?v={{ @filemtime(public_path('js/kitapamer.js')) }}"></script>
</body>
</html>
