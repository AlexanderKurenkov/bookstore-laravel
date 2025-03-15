<!-- resources/views/components/layout.blade.php -->
<!DOCTYPE html>
<html lang="en">
<x-header />

<body class="d-flex flex-column min-vh-100"> <!-- <body> is a flex container -->

    <x-navbar />

    <main class="container flex-grow-1"> <!-- content takes available space -->
        {{ $slot }}
    </main>

    <x-footer />

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
