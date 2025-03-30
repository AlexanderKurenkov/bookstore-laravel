<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Подключение файлов Bootstrap -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Подключение пользовательских стилей -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <link rel="icon" href="{{ asset('image/apple-touch-icon.png') }}">

    <title>{{ $title ?? 'Книгочей' }}</title>

    <!-- Подключение дополнительных файлов в загаловке страницы -->
    @stack('head')
</head>
