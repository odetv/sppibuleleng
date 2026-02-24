<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body
    x-data="{ 
        sidebarExpanded: true, 
        isHovered: false, 
        mobileSidebar: false 
      }">

    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            @include('layouts.navigation')

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>