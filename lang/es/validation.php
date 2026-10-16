<?php

// Mensajes en español para las reglas usadas en el proyecto.
// Las reglas que no estén aquí usan el mensaje en inglés (fallback).

return [
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'lowercase' => 'El campo :attribute debe estar en minúsculas.',
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'string' => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'numeric' => 'El campo :attribute debe ser un número.',
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'Este :attribute ya está registrado.',

    'attributes' => [
        'name' => 'nombre',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'current_password' => 'contraseña actual',
        'password_eliminar' => 'contraseña',
        'is_admin' => 'administrador',
        'nombre' => 'nombre',
        'descripcion' => 'descripción',
        'precio' => 'precio',
        'stock' => 'stock',
    ],
];
