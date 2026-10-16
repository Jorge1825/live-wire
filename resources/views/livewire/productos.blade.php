<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold">Productos</h1>
        <button wire:click="crear" class="btn-primary">+ Nuevo producto</button>
    </div>

    @if (session('ok'))
        <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ session('ok') }}
        </div>
    @endif

    {{-- Búsqueda en vivo --}}
    <div class="relative">
        <input wire:model.live.debounce.300ms="buscar" type="search" placeholder="Buscar por nombre..." class="input">
        <span wire:loading wire:target="buscar" class="absolute right-3 top-2.5 text-xs text-slate-400">Buscando...</span>
    </div>

    {{-- Tabla --}}
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    @foreach (['nombre' => 'Nombre', 'precio' => 'Precio', 'stock' => 'Stock', 'created_at' => 'Creado'] as $campo => $titulo)
                        <th class="px-4 py-3 font-medium">
                            <button wire:click="ordenar('{{ $campo }}')" class="flex items-center gap-1 hover:text-slate-900">
                                {{ $titulo }}
                                @if ($ordenarPor === $campo)
                                    <span>{{ $direccion === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </button>
                        </th>
                    @endforeach
                    <th class="px-4 py-3 text-right font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($productos as $producto)
                    <tr wire:key="producto-{{ $producto->id }}" class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $producto->nombre }}</p>
                            @if ($producto->descripcion)
                                <p class="text-slate-500 text-xs truncate max-w-xs">{{ $producto->descripcion }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">${{ number_format($producto->precio, 2) }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-red-100 text-red-700' => $producto->stock === 0,
                                'bg-amber-100 text-amber-700' => $producto->stock > 0 && $producto->stock < 10,
                                'bg-green-100 text-green-700' => $producto->stock >= 10,
                            ])>{{ $producto->stock }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $producto->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <button wire:click="editar({{ $producto->id }})" class="text-indigo-600 hover:underline">Editar</button>
                            <button wire:click="eliminar({{ $producto->id }})"
                                    wire:confirm="¿Eliminar «{{ $producto->nombre }}»? Esta acción no se puede deshacer."
                                    class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                            {{ $buscar ? 'No hay productos que coincidan con la búsqueda.' : 'Aún no tienes productos.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $productos->links() }}

    {{-- Modal crear / editar --}}
    @if ($mostrarFormulario)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
             wire:keydown.escape.window="cancelar">
            <div class="card w-full max-w-lg p-6 shadow-xl" @click.outside="$wire.cancelar()">
                <h2 class="text-lg font-semibold mb-4">{{ $editandoId ? 'Editar producto' : 'Nuevo producto' }}</h2>

                <form wire:submit="guardar" class="space-y-4">
                    <div>
                        <label for="nombre" class="label">Nombre</label>
                        <input wire:model="nombre" id="nombre" type="text" class="input" autofocus>
                        @error('nombre') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="descripcion" class="label">Descripción <span class="text-slate-400">(opcional)</span></label>
                        <textarea wire:model="descripcion" id="descripcion" rows="3" class="input"></textarea>
                        @error('descripcion') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="precio" class="label">Precio</label>
                            <input wire:model="precio" id="precio" type="number" step="0.01" min="0" class="input">
                            @error('precio') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="stock" class="label">Stock</label>
                            <input wire:model="stock" id="stock" type="number" min="0" class="input">
                            @error('stock') <p class="error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cancelar" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="guardar">
                            <span wire:loading.remove wire:target="guardar">{{ $editandoId ? 'Actualizar' : 'Crear' }}</span>
                            <span wire:loading wire:target="guardar">Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
