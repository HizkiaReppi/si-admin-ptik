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
    <x-meta-data :title="$title" csrfToken="{{ csrf_token() }}" />

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
    <script src="{{ $baseUrl }}/assets/js/bootstrap.js"></script>
</body>

</html>
