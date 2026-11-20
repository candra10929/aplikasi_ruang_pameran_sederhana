<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    @stack('head')
</head>
<body>
    @include('partials.navbar')

    <main class="kp-main">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.modals')
    @include('partials.flash')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/kitapamer.js') }}?v={{ @filemtime(public_path('js/kitapamer.js')) }}"></script>
    @stack('scripts')
</body>
</html>
