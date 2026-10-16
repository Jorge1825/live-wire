<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-semibold">Hola, {{ auth()->user()->name }}</h1>
        <p class="text-slate-500">Resumen de tu inventario</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Productos</p>
            <p class="text-3xl font-semibold mt-1">{{ $totalProductos }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Unidades en stock</p>
            <p class="text-3xl font-semibold mt-1">{{ number_format($totalStock) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Valor del inventario</p>
            <p class="text-3xl font-semibold mt-1">${{ number_format($valorInventario, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            @if ($totalUsuarios !== null)
                <p class="text-sm text-slate-500">Usuarios registrados</p>
                <p class="text-3xl font-semibold mt-1">{{ $totalUsuarios }}</p>
            @else
                <p class="text-sm text-slate-500">Sin stock</p>
                <p class="text-3xl font-semibold mt-1 {{ $sinStock ? 'text-red-600' : '' }}">{{ $sinStock }}</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <h2 class="font-semibold">Últimos productos</h2>
            <a href="{{ route('productos') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">Ver todos →</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($ultimos as $producto)
                <li wire:key="ultimo-{{ $producto->id }}" class="px-5 py-3 flex justify-between text-sm">
                    <span>{{ $producto->nombre }}</span>
                    <span class="text-slate-500">${{ number_format($producto->precio, 2) }} · {{ $producto->stock }} uds.</span>
                </li>
            @empty
                <li class="px-5 py-6 text-center text-sm text-slate-500">
                    Aún no tienes productos.
                    <a href="{{ route('productos') }}" wire:navigate class="text-indigo-600 hover:underline">Crea el primero</a>
                </li>
            @endforelse
        </ul>
    </div>
</div>
