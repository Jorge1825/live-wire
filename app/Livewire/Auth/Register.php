<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Crear cuenta')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    // Validación en tiempo real al salir de cada campo (wire:model.blur)
    public function updated(string $propiedad): void
    {
        if (in_array($propiedad, ['name', 'email'])) {
            $this->validateOnly($propiedad);
        }
    }

    public function register()
    {
        $datos = $this->validate();

        // El cast 'hashed' del modelo User cifra la contraseña automáticamente
        $user = User::create($datos);

        event(new Registered($user));

        Auth::login($user);
        session()->regenerate();

        return $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
