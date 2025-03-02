<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
</head>

<body class="d-flex flex-column min-vh-100">
    <x-navbar />

    <main class="d-flex align-items-center justify-content-center flex-grow-1">
        <div class="row" style="margin-top:10px">
            <div class="text-center">
                <h2>Возникла непредвиденная ошибка</h2>
                <br>
                <h4>
                    <a href="{{ route('index') }}" class="text-decoration-none">На главную</a>
                </h4>
            </div>
        </div>
    </main>

    <x-footer />
</body>

</html>
