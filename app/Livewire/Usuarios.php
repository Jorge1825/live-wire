<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Usuarios')]
class Usuarios extends Component
{
    use WithPagination;

    #[Locked]
    public ?int $editandoId = null;

    public bool $mostrarFormulario = false;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $is_admin = false;

    #[Url(except: '')]
    public string $buscar = '';

    // La ruta ya está protegida con can:admin, pero cada request
    // de Livewire es independiente: volvemos a comprobarlo aquí.
    public function boot(): void
    {
        $this->authorize('admin');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->editandoId),
            ],
            // Obligatoria al crear, opcional al editar (vacía = no cambiar)
            'password' => [
                $this->editandoId ? 'nullable' : 'required',
                'string', 'confirmed', Password::defaults(),
            ],
            'is_admin' => ['boolean'],
        ];
    }

    public function updatedBuscar(): void
    {
        $this->resetPage();
    }

    public function crear(): void
    {
        $this->cancelar();
        $this->mostrarFormulario = true;
    }

    public function editar(int $id): void
    {
        $usuario = User::findOrFail($id);

        $this->cancelar();
        $this->editandoId = $usuario->id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->is_admin = $usuario->is_admin;
        $this->mostrarFormulario = true;
    }

    public function guardar(): void
    {
        $datos = $this->validate();

        if (blank($datos['password'])) {
            unset($datos['password']);
        }

        // Un admin no puede quitarse a sí mismo el rol de administrador
        if ($this->editandoId === auth()->id()) {
            $datos['is_admin'] = true;
        }

        if ($this->editandoId) {
            User::findOrFail($this->editandoId)->update($datos);
            session()->flash('ok', 'Usuario actualizado.');
        } else {
            User::create($datos);
            session()->flash('ok', 'Usuario creado.');
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta desde aquí. Usa tu perfil.');

            return;
        }

        User::findOrFail($id)->delete();
        session()->flash('ok', 'Usuario eliminado.');
    }

    public function cancelar(): void
    {
        $this->reset(['editandoId', 'name', 'email', 'password', 'password_confirmation', 'is_admin', 'mostrarFormulario']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.usuarios', [
            'usuarios' => User::query()
                ->withCount('productos')
                ->when($this->buscar, fn ($q) => $q->where(fn ($q) => $q
                    ->where('name', 'ilike', "%{$this->buscar}%")
                    ->orWhere('email', 'ilike', "%{$this->buscar}%")))
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
