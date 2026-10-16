<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <span class="inline-flex size-12 items-center justify-center rounded-xl bg-indigo-600 text-white text-xl font-bold">L</span>
            <h1 class="mt-3 text-2xl font-semibold">{{ config('app.name') }}</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
