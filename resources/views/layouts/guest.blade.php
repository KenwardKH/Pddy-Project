<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <style>
            body .background {
                background-image: url('images/login_bg.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                align-items:center;
            }
            .form {
                background-color: #D0FFFE;
                padding: 25px 50px;
                width: 500px;
                height:500px;
                border-radius: 20px;
                box-shadow: 0 5px 20px 34px rgba(0, 0, 0, 0.25);
                text-align:center;
            }
        </style>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 background">
            
            <div class="form">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
