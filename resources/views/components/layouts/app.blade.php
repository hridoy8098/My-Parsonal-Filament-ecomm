<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'My Ecommers' }}</title>
        @vite(['resources/css/app.css','resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
       <main class="bg-slate-200 dark:bg-slate-700">
        @livewire('Partials.navber')
       {{ $slot }}
       </main>

       @livewire('Partials.footer')
        @livewireScripts

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       <x-livewire-alert::scripts />

    </body>
</html>
