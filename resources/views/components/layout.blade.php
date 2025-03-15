<!-- resources/views/layout.blade.php -->
<!DOCTYPE html>
<html lang="en">
<x-header />

<body>
    <x-navbar />

    <div class="container">
        {{ $slot }}
    </div>

    <x-footer />

    <div>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>
    </div>
</body>
</html>
