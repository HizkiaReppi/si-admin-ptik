@props(['title', 'description' => 'Sistem Informasi Admin PTIK', 'csrfToken'])

<!-- Essential Meta Tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ $csrfToken }}">
<meta name="description" content="{{ $description }}" />
<meta name="keywords" content="Unima, Fakultas Teknik, PTIK, Manado, Tondano">
<meta name="author" content="Admin PTIK">

<!-- Open Graph Meta Tags for Social Sharing -->
<meta property="og:title" content="{{ $title }} - Admin PTIK">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }} - Admin PTIK">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:site" content="@AdminPTIK">

<!-- Mobile App Configuration -->
<link rel="manifest" href="/assets/manifest.json">
<meta name="theme-color" content="#ffffff">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/assets/images/ms-icon-144x144.png">

<!-- Title -->
<title>{{ $title }} - Admin PTIK</title>

<!-- Favicon and App Icons -->
<link rel="apple-touch-icon" sizes="57x57" href="/assets/images/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/assets/images/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/assets/images/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/assets/images/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/assets/images/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/assets/images/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/assets/images/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/assets/images/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192" href="/assets/images/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/assets/images/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
