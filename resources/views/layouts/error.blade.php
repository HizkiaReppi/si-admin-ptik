<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $baseUrl = config('app.url');

    $baseUrl = explode('://', $baseUrl)[1];

    if (request()->secure()) {
        $baseUrl = 'https://' . $baseUrl;
    } else {
        $baseUrl = 'http://' . $baseUrl;
    }
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Admin PTIK" />

    <title>{{ $title }} - Admin PTIK</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    {{-- @vite(['resources/vendor/css/core.css', 'resources/vendor/css/theme-default.css', 'resources/vendor/css/pages/page-misc.css']) --}}
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/vendor/css/core.css">
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/vendor/css/theme-default.css">
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/vendor/css/pages/page-misc.css">
</head>

<body>
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            {{ $slot }}
        </div>
    </div>

    {{-- @vite(['resources/vendor/js/bootstrap.js']) --}}
    <script src="{{ $baseUrl }}/assets/js/app.js"></script>
</body>

</html>
