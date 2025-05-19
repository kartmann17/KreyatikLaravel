<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(isset($seoData))
    <title>{{ $seoData->title }}</title>
    <meta name="description" content="{{ $seoData->description }}">
    <meta name="author" content="{{ $seoData->author }}">
    <meta name="robots" content="{{ $seoData->robots }}">
    <link rel="canonical" href="{{ $seoData->canonical_url }}" />
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Macondo&display=swap" rel="stylesheet">
    {!! CookieConsent::styles() !!}

    @vite(['resources/css/app.css', 'resources/js/app.js']);

</head>

<body class="h-full">
    {!! CookieConsent::scripts() !!}
    <div class="site-wrapper">
        @include('components.nav')

        <div class="site-content">
            <!-- Content will be here -->
        </div>
    </div>