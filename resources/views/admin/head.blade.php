<html>
    <head>
        <title>{{ $common_data["title"] }}</title>
        @vite(['resources/js/app.js'])
        <script src="https://kit.fontawesome.com/3619ec3c10.js" crossorigin="anonymous"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="admin-page">


