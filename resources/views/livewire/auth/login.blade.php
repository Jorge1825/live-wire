<div>
    <h2 class="text-xl font-semibold mb-6">Iniciar sesión</h2>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="label">Correo electrónico</label>
            <input wire:model="email" id="email" type="email" autocomplete="email" autofocus
                   class="input">
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="label">Contraseña</label>
            <input wire:model="password" id="password" type="password" autocomplete="current-password"
                   class="input">
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input wire:model="remember" type="checkbox" class="rounded border-slate-300">
            Recordarme
        </label>

        <button type="submit"
                class="btn-primary w-full"
                wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="login">Entrar</span>
            <span wire:loading wire:target="login">Verificando...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}" wire:navigate class="font-medium text-indigo-600 hover:underline">Regístrate</a>
    </p>
</div>
