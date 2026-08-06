<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="32x32"  href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">

    <title>
        {{ isset($title) ? $title . ' | ' : '' }}{{ config('app.name') }}
    </title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

</head>

<body class="bg-body-tertiary">

    <div class="wrapper">

        @include('components.sidebar')

        <div class="main">

            @include('components.header')

            <main class="content p-4">
                {{ $slot }}
            </main>

        </div>

    </div>

    @livewireScriptConfig
</body>
</html>