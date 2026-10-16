# Livewire App: Laravel 13 + Livewire 3 + PostgreSQL

Proyecto de ejemplo para enseñar Livewire. Incluye:

- **Autenticación:** registro, inicio de sesión (con "recordarme" y límite de intentos) y cierre de sesión.
- **Perfil:** editar datos personales, cambiar la contraseña y eliminar la cuenta.
- **CRUD de productos:** cada usuario gestiona sus propios productos, con búsqueda en vivo, ordenación, paginación y un modal para crear y editar.
- **Gestión de usuarios:** CRUD completo solo para administradores (Gate `admin`).
- **Dashboard** con estadísticas.

## Requisitos

- Docker Desktop
- Node.js 20 o superior (solo para compilar CSS y JS)

## Puesta en marcha

```bash
# 1. Levantar PHP 8.4 + PostgreSQL 17
docker compose up -d --build

# 2. Dependencias PHP (solo la primera vez: se instalan en el volumen "vendor")
docker compose exec app composer install
docker compose restart app

# 3. Crear las tablas y los datos de prueba
docker compose exec app php artisan migrate:fresh --seed

# 4. Compilar Tailwind (o `npm run dev` mientras desarrollas)
npm install
npm run build
```

Abre http://localhost:8000

### Usuarios de prueba (creados por el seeder)

| Correo | Rol |
|---|---|
| admin@example.com | Administrador |
| user@example.com | Usuario |

Contraseña de ambos: `password`

## Comandos útiles

```bash
docker compose exec app php artisan make:livewire NombreComponente
docker compose exec app php artisan migrate
docker compose exec app php artisan tinker
docker compose logs -f app
docker compose down          # detener
docker compose down -v       # detener y BORRAR la base de datos
```

Para conectarte a PostgreSQL desde un cliente externo (DBeaver, TablePlus, pgAdmin):
host `localhost`, puerto `5432`, base de datos `livewire_app`, usuario `laravel`, contraseña `secret`.

## Estructura

```
app/
├── Livewire/
│   ├── Auth/
│   │   ├── Login.php        # Auth::attempt + RateLimiter
│   │   └── Register.php     # Validación en vivo con wire:model.blur
│   ├── Dashboard.php
│   ├── Productos.php        # CRUD (WithPagination, #[Locked], #[Url])
│   ├── Usuarios.php         # CRUD solo para admin (authorize en boot())
│   └── Perfil.php           # Varios formularios en un componente
├── Models/
│   ├── User.php             # is_admin + relación productos()
│   └── Producto.php
└── Providers/AppServiceProvider.php   # Gate::define('admin')

resources/views/
├── components/layouts/
│   ├── app.blade.php        # Layout con navegación (usuarios autenticados)
│   └── guest.blade.php      # Layout para login y registro
└── livewire/                # Una vista por componente

routes/web.php               # Middlewares guest / auth / can:admin
lang/es/                     # Mensajes de validación en español
```

## Conceptos de Livewire que muestra cada archivo

| Concepto | Dónde |
|---|---|
| Componentes de página completa, `#[Layout]` y `#[Title]` | Todos los componentes |
| `wire:model`, `.live.debounce`, `.blur` | Productos (búsqueda), Register |
| `wire:submit`, `wire:click` | Todos los formularios |
| `wire:loading`, `wire:target`, `wire:loading.attr` | Botones de guardar |
| `wire:confirm` | Eliminar producto o usuario |
| `wire:key` | Todas las listas |
| `wire:navigate` (navegación tipo SPA) | Menú y enlaces |
| `wire:keydown.escape` y `$wire` desde Alpine | Modales |
| `#[Validate]` y `rules()` | Productos y Usuarios |
| `#[Locked]` (seguridad) | `editandoId`, `ordenarPor` |
| `#[Url]` (estado en la URL) | Búsqueda |
| Hooks `mount()`, `boot()`, `updated*()` | Perfil, Usuarios, Productos |
| Autorización por request | `Usuarios::boot()` |
| Alpine.js (`x-data`, `x-show`) | Eliminar cuenta en Perfil |

## Rendimiento en Windows y macOS

Leer miles de archivos desde una carpeta montada de Windows o macOS es muy lento (unos 6 s por request). Por eso:

- `vendor/` vive en un **volumen de Docker** (`docker-compose.yml`). Para que el editor tenga autocompletado, ejecuta también `composer install` en tu máquina, si tienes PHP.
- **OPcache** está activado (`docker-php.ini`).

Con estos dos cambios cada request tarda unos 0,25 s.

> Nota: las búsquedas usan `ilike`, que es específico de PostgreSQL (no distingue mayúsculas de minúsculas).
