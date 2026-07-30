<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

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