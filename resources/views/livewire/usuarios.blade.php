<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold">Usuarios</h1>
        <button wire:click="crear" class="btn-primary">+ Nuevo usuario</button>
    </div>

    @if (session('ok'))
        <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <input wire:model.live.debounce.300ms="buscar" type="search" placeholder="Buscar por nombre o correo..." class="input">

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3 font-medium">Usuario</th>
                    <th class="px-4 py-3 font-medium">Rol</th>
                    <th class="px-4 py-3 font-medium">Productos</th>
                    <th class="px-4 py-3 font-medium">Registrado</th>
                    <th class="px-4 py-3 text-right font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($usuarios as $usuario)
                    <tr wire:key="usuario-{{ $usuario->id }}" class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex size-8 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold uppercase">
                                    {{ $usuario->initials() }}
                                </span>
                                <div>
                                    <p class="font-medium">
                                        {{ $usuario->name }}
                                        @if ($usuario->id === auth()->id()) <span class="text-xs text-slate-400">(tú)</span> @endif
                                    </p>
                                    <p class="text-slate-500 text-xs">{{ $usuario->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if ($usuario->is_admin)
                                <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Admin</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">Usuario</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $usuario->productos_count }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <button wire:click="editar({{ $usuario->id }})" class="text-indigo-600 hover:underline">Editar</button>
                            @if ($usuario->id !== auth()->id())
                                <button wire:click="eliminar({{ $usuario->id }})"
                                        wire:confirm="¿Eliminar a {{ $usuario->name }}? También se borrarán sus productos."
                                        class="text-red-600 hover:underline">Eliminar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No se encontraron usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $usuarios->links() }}

    @if ($mostrarFormulario)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
             wire:keydown.escape.window="cancelar">
            <div class="card w-full max-w-lg p-6 shadow-xl" @click.outside="$wire.cancelar()">
                <h2 class="text-lg font-semibold mb-4">{{ $editandoId ? 'Editar usuario' : 'Nuevo usuario' }}</h2>

                <form wire:submit="guardar" class="space-y-4">
                    <div>
                        <label for="name" class="label">Nombre</label>
                        <input wire:model="name" id="name" type="text" class="input" autofocus>
                        @error('name') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="label">Correo electrónico</label>
                        <input wire:model="email" id="email" type="email" class="input">
                        @error('email') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="label">Contraseña</label>
                            <input wire:model="password" id="password" type="password" autocomplete="new-password" class="input"
                                   placeholder="{{ $editandoId ? 'Dejar vacía para no cambiar' : '' }}">
                            @error('password') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="label">Confirmar</label>
                            <input wire:model="password_confirmation" id="password_confirmation" type="password" autocomplete="new-password" class="input">
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="is_admin" type="checkbox" class="rounded border-slate-300"
                               @disabled($editandoId === auth()->id())>
                        Administrador
                        @if ($editandoId === auth()->id())
                            <span class="text-xs text-slate-400">(no puedes quitarte este rol)</span>
                        @endif
                    </label>

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
