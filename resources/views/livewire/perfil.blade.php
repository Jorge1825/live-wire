<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-semibold">Mi perfil</h1>

    {{-- Datos personales --}}
    <section class="card p-6">
        <h2 class="font-semibold">Información personal</h2>
        <p class="text-sm text-slate-500 mb-4">Actualiza tu nombre y correo electrónico.</p>

        <form wire:submit="actualizarPerfil" class="space-y-4">
            <div>
                <label for="name" class="label">Nombre</label>
                <input wire:model="name" id="name" type="text" class="input">
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="label">Correo electrónico</label>
                <input wire:model="email" id="email" type="email" class="input">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="actualizarPerfil">Guardar</button>
                @if (session('perfil'))
                    <span class="text-sm text-green-700">{{ session('perfil') }}</span>
                @endif
            </div>
        </form>
    </section>

    {{-- Cambiar contraseña --}}
    <section class="card p-6">
        <h2 class="font-semibold">Cambiar contraseña</h2>
        <p class="text-sm text-slate-500 mb-4">Usa una contraseña de al menos 8 caracteres.</p>

        <form wire:submit="actualizarPassword" class="space-y-4">
            <div>
                <label for="current_password" class="label">Contraseña actual</label>
                <input wire:model="current_password" id="current_password" type="password" autocomplete="current-password" class="input">
                @error('current_password') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="label">Nueva contraseña</label>
                <input wire:model="password" id="password" type="password" autocomplete="new-password" class="input">
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="label">Confirmar nueva contraseña</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" autocomplete="new-password" class="input">
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="actualizarPassword">Actualizar contraseña</button>
                @if (session('password'))
                    <span class="text-sm text-green-700">{{ session('password') }}</span>
                @endif
            </div>
        </form>
    </section>

    {{-- Eliminar cuenta --}}
    <section class="card p-6 border-red-200" x-data="{ confirmar: false }">
        <h2 class="font-semibold text-red-700">Eliminar cuenta</h2>
        <p class="text-sm text-slate-500 mb-4">Se borrarán tu cuenta y todos tus productos. Esta acción no se puede deshacer.</p>

        <button type="button" x-show="!confirmar" @click="confirmar = true" class="btn-danger">Eliminar mi cuenta</button>

        <form x-show="confirmar" x-cloak wire:submit="eliminarCuenta" class="space-y-4">
            <div>
                <label for="password_eliminar" class="label">Escribe tu contraseña para confirmar</label>
                <input wire:model="password_eliminar" id="password_eliminar" type="password" autocomplete="current-password" class="input">
                @error('password_eliminar') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button type="button" @click="confirmar = false" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-danger">Eliminar definitivamente</button>
            </div>
        </form>
    </section>
</div>
