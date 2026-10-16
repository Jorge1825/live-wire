<?php

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Productos')]
class Productos extends Component
{
    use WithPagination;

    // Locked: el navegador no puede cambiar este valor
    #[Locked]
    public ?int $editandoId = null;

    public bool $mostrarFormulario = false;

    #[Validate('required|string|min:3|max:100')]
    public string $nombre = '';

    #[Validate('nullable|string|max:500')]
    public string $descripcion = '';

    #[Validate('required|numeric|min:0|max:99999999')]
    public $precio = '';

    #[Validate('required|integer|min:0')]
    public $stock = '';

    // Url: la búsqueda se guarda en la URL (?buscar=...)
    #[Url(except: '')]
    public string $buscar = '';

    #[Locked]
    public string $ordenarPor = 'created_at';

    #[Locked]
    public string $direccion = 'desc';

    public function updatedBuscar(): void
    {
        $this->resetPage();
    }

    public function ordenar(string $campo): void
    {
        if (! in_array($campo, ['nombre', 'precio', 'stock', 'created_at'])) {
            return;
        }

        $this->direccion = $this->ordenarPor === $campo && $this->direccion === 'asc' ? 'desc' : 'asc';
        $this->ordenarPor = $campo;
    }

    public function crear(): void
    {
        $this->cancelar();
        $this->mostrarFormulario = true;
    }

    public function editar(int $id): void
    {
        // Solo se buscan productos del usuario autenticado
        $producto = $this->consulta()->findOrFail($id);

        $this->editandoId = $producto->id;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion ?? '';
        $this->precio = $producto->precio;
        $this->stock = $producto->stock;
        $this->resetValidation();
        $this->mostrarFormulario = true;
    }

    public function guardar(): void
    {
        $datos = $this->validate();

        if ($this->editandoId) {
            $this->consulta()->findOrFail($this->editandoId)->update($datos);
            session()->flash('ok', 'Producto actualizado.');
        } else {
            auth()->user()->productos()->create($datos);
            session()->flash('ok', 'Producto creado.');
        }

        $this->cancelar();
    }

    public function eliminar(int $id): void
    {
        $this->consulta()->findOrFail($id)->delete();
        session()->flash('ok', 'Producto eliminado.');
    }

    public function cancelar(): void
    {
        $this->reset(['editandoId', 'nombre', 'descripcion', 'precio', 'stock', 'mostrarFormulario']);
        $this->resetValidation();
    }

    private function consulta()
    {
        return auth()->user()->productos();
    }

    public function render()
    {
        return view('livewire.productos', [
            'productos' => $this->consulta()
                ->when($this->buscar, fn ($q) => $q->where('nombre', 'ilike', "%{$this->buscar}%"))
                ->orderBy($this->ordenarPor, $this->direccion)
                ->paginate(10),
        ]);
    }
}
