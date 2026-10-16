<div>
    <h2 class="text-xl font-semibold mb-6">Crear cuenta</h2>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label for="name" class="label">Nombre</label>
            <input wire:model.blur="name" id="name" type="text" autocomplete="name" autofocus
                   class="input">
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="label">Correo electrónico</label>
            <input wire:model.blur="email" id="email" type="email" autocomplete="email"
                   class="input">
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="label">Contraseña</label>
            <input wire:model="password" id="password" type="password" autocomplete="new-password"
                   class="input">
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="label">Confirmar contraseña</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" autocomplete="new-password"
                   class="input">
        </div>

        <button type="submit"
                class="btn-primary w-full"
                wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="register">Registrarme</span>
            <span wire:loading wire:target="register">Creando cuenta...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" wire:navigate class="font-medium text-indigo-600 hover:underline">Inicia sesión</a>
    </p>
</div>
