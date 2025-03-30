<!DOCTYPE html>
<html lang="en">
<x-header />

<body class="d-flex flex-column min-vh-100">

    <x-navbar />

    <main class="container flex-grow-1">
        {{ $slot }}
    </main>

    <x-footer />
</body>
</html>
