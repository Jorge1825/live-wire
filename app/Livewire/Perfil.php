<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mi perfil')]
class Perfil extends Component
{
    // Datos personales
    public string $name = '';

    public string $email = '';

    // Cambio de contraseña
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    // Eliminar cuenta
    public string $password_eliminar = '';

    public function mount(): void
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function actualizarPerfil(): void
    {
        $user = auth()->user();

        $datos = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($datos);

        session()->flash('perfil', 'Datos actualizados.');
    }

    public function actualizarPassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update(['password' => $this->password]);

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('password', 'Contraseña actualizada.');
    }

    public function eliminarCuenta()
    {
        $this->validate([
            'password_eliminar' => ['required', 'string', 'current_password'],
        ]);

        $user = auth()->user();

        Auth::logout();
        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        return $this->redirectRoute('login');
    }

    public function render()
    {
        return view('livewire.perfil');
    }
}
