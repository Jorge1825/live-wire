<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    @php
        $enlaces = [
            ['ruta' => 'dashboard', 'texto' => 'Dashboard'],
            ['ruta' => 'productos', 'texto' => 'Productos'],
        ];

        if (auth()->user()->can('admin')) {
            $enlaces[] = ['ruta' => 'usuarios', 'texto' => 'Usuarios'];
        }
    @endphp

    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 font-semibold">
                    <span class="inline-flex size-8 items-center justify-center rounded-lg bg-indigo-600 text-white">L</span>
                    {{ config('app.name') }}
                </a>

                <div class="flex gap-1">
                    @foreach ($enlaces as $enlace)
                        <a href="{{ route($enlace['ruta']) }}" wire:navigate
                           @class([
                               'px-3 py-2 rounded-md text-sm font-medium',
                               'bg-indigo-50 text-indigo-700' => request()->routeIs($enlace['ruta']),
                               'text-slate-600 hover:bg-slate-100' => ! request()->routeIs($enlace['ruta']),
                           ])>
                            {{ $enlace['texto'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('perfil') }}" wire:navigate class="flex items-center gap-2 text-sm hover:text-indigo-700">
                    <span class="inline-flex size-8 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold uppercase">
                        {{ auth()->user()->initials() }}
                    </span>
                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-slate-500 hover:text-red-600">Salir</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>
</body>
</html>
