<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $productos = auth()->user()->productos();

        return view('livewire.dashboard', [
            'totalProductos' => (clone $productos)->count(),
            'totalStock' => (clone $productos)->sum('stock'),
            'valorInventario' => (clone $productos)->sum(DB::raw('precio * stock')),
            'sinStock' => (clone $productos)->where('stock', 0)->count(),
            'ultimos' => (clone $productos)->latest()->take(5)->get(),
            'totalUsuarios' => auth()->user()->can('admin') ? User::count() : null,
        ]);
    }
}
