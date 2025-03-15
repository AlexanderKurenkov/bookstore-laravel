<!-- resources/views/components/header.blade.php -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <link rel="icon" href="{{ asset('image/apple-touch-icon.png') }}">

    <title>{{ $title ?? 'Книгочей' }}</title>
</head>
