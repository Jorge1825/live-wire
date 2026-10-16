# Guía paso a paso: tu primera app con Laravel + Livewire + PostgreSQL

> **Para quién es:** para alguien que **nunca ha usado Laravel ni Livewire**.
> **Qué vas a construir:** una aplicación web con registro, inicio y cierre de sesión, perfil de usuario, un CRUD de productos y un panel de administración de usuarios.
> **Tiempo estimado:** de 4 a 6 horas, haciendo todo con calma.
> **Sistema:** Windows 10/11. En macOS o Linux los pasos son casi iguales.

---

## Elige cómo vas a trabajar

| | **Ruta A: con Docker** | **Ruta B: instalación local (sin Docker)** |
|---|---|---|
| Qué instalas | Docker Desktop + Node.js | PHP + Composer + PostgreSQL + Node.js |
| Ventajas | No ensucias Windows; todos los alumnos tienen el mismo entorno. | Más rápido y más sencillo de entender; no hay que aprender Docker. |
| Inconvenientes | Hay que aprender cuatro comandos de Docker; necesita más RAM. | Cada equipo puede tener versiones distintas; hay que activar extensiones de PHP. |
| Pasos | Sigue la guía en orden: 1 → 2 → 3 → 4 → 5... | Haz el **[Anexo: instalación local](#anexo-instalación-local-sin-docker)** y continúa en el **paso 5**. |

> **Si eliges la Ruta B (local)**, en el resto de la guía quita `docker compose exec app` del inicio de cada comando:
>
> | En la guía pone... | Tú escribes... |
> |---|---|
> | `docker compose exec app php artisan migrate` | `php artisan migrate` |
> | `docker compose exec app composer require ...` | `composer require ...` |
> | `docker compose ...` (cualquier otro) | No aplica, sáltalo. |

---

## Índice

0. [Conceptos básicos (lee esto primero)](#0-conceptos-básicos-lee-esto-primero)
1. [Instalar las herramientas](#1-instalar-las-herramientas)
2. [Crear el proyecto Laravel](#2-crear-el-proyecto-laravel)
3. [Configurar Docker (PHP + PostgreSQL)](#3-configurar-docker-php--postgresql)
4. [Arrancar la aplicación por primera vez](#4-arrancar-la-aplicación-por-primera-vez)
5. [Recorrido por las carpetas de Laravel](#5-recorrido-por-las-carpetas-de-laravel)
6. [Instalar Livewire](#6-instalar-livewire)
7. [Estilos con Tailwind CSS](#7-estilos-con-tailwind-css)
8. [Base de datos: migraciones, modelos y datos de prueba](#8-base-de-datos-migraciones-modelos-y-datos-de-prueba)
9. [Layouts (la plantilla de las páginas)](#9-layouts-la-plantilla-de-las-páginas)
10. [Crear los componentes y las rutas](#10-crear-los-componentes-y-las-rutas)
11. [Registro de usuarios](#11-registro-de-usuarios)
12. [Inicio de sesión](#12-inicio-de-sesión)
13. [Dashboard](#13-dashboard)
14. [CRUD de productos](#14-crud-de-productos)
15. [Gestión de usuarios (solo administradores)](#15-gestión-de-usuarios-solo-administradores)
16. [Perfil del usuario](#16-perfil-del-usuario)
17. [Traducir los mensajes al español](#17-traducir-los-mensajes-al-español)
18. [Probar todo](#18-probar-todo)
19. [Solución de problemas](#19-solución-de-problemas)
20. [Chuleta de comandos](#20-chuleta-de-comandos)
21. [Glosario](#21-glosario)
22. [Siguientes pasos](#22-siguientes-pasos)
- [Anexo: instalación local (sin Docker)](#anexo-instalación-local-sin-docker)

---

## 0. Conceptos básicos (lee esto primero)

| Término | Qué es, en una frase |
|---|---|
| **PHP** | El lenguaje de programación en el que está escrito Laravel. |
| **Laravel** | Un *framework* de PHP: un conjunto de herramientas ya hechas para crear webs (rutas, base de datos, login, validación...). |
| **Composer** | El instalador de paquetes de PHP (como una "tienda de librerías"). |
| **Livewire** | Un paquete de Laravel que hace las páginas **interactivas sin escribir JavaScript**. Escribes PHP y Livewire actualiza la página solo. |
| **Blade** | El sistema de plantillas de Laravel: HTML con superpoderes (`{{ $variable }}`, `@if`, `@foreach`). |
| **PostgreSQL** | La base de datos donde se guardan los usuarios y los productos. |
| **Docker** | Un programa que ejecuta "mini-computadoras" (contenedores) con PHP y PostgreSQL ya instalados. Así **no tienes que instalar PHP ni PostgreSQL en Windows**. |
| **Node.js / npm** | Se usan solo para compilar los estilos (CSS). |
| **Tailwind CSS** | Una forma de dar estilos escribiendo clases en el HTML (`bg-blue-600`, `p-4`, etc.). |

### Cómo funciona una petición en Laravel

```
Navegador ──► Ruta (routes/web.php) ──► Componente Livewire (app/Livewire/X.php)
                                               │
                                               ├─► Modelo (app/Models/X.php) ──► Base de datos
                                               │
                                               └─► Vista (resources/views/livewire/x.blade.php) ──► HTML al navegador
```

### Cómo funciona Livewire

1. El usuario hace clic en un botón con `wire:click="guardar"`.
2. Livewire envía una petición al servidor **sin recargar la página**.
3. Laravel ejecuta el método `guardar()` de tu clase PHP.
4. Livewire devuelve el HTML nuevo y actualiza **solo lo que cambió**.

---

## 1. Instalar las herramientas

> 🔀 **Pasos 1 a 4: solo para la Ruta A (Docker).** Si vas a trabajar sin Docker, ve al [Anexo](#anexo-instalación-local-sin-docker).

### 1.1 Docker Desktop
1. Descárgalo de https://www.docker.com/products/docker-desktop/
2. Instálalo con las opciones por defecto (usa **WSL 2** si te lo pregunta).
3. Reinicia el ordenador si te lo pide.
4. **Abre Docker Desktop** y espera a que abajo a la izquierda diga *Engine running*.

### 1.2 Node.js
1. Descarga la versión **LTS** de https://nodejs.org/
2. Instálala con las opciones por defecto.

### 1.3 Visual Studio Code (editor)
1. Descárgalo de https://code.visualstudio.com/
2. Instala estas extensiones (icono de cuadraditos en la barra izquierda):
   - **Laravel** (oficial)
   - **PHP Intelephense**
   - **Tailwind CSS IntelliSense**

### 1.4 Comprobar que todo funciona

Abre **PowerShell** (menú Inicio → escribe "PowerShell") y ejecuta, uno por uno:

```powershell
docker --version
node --version
npm --version
```

Cada comando debe mostrar un número de versión. Si alguno dice *"no se reconoce como un comando"*, reinstala esa herramienta y **cierra y vuelve a abrir PowerShell**.

> 💡 En toda la guía, los bloques `powershell` son comandos que debes **copiar y pegar en PowerShell** y pulsar Enter.

---

## 2. Crear el proyecto Laravel

### 2.1 Elegir una carpeta

```powershell
cd $HOME\Documents
mkdir proyectos
cd proyectos
```

### 2.2 Descargar Laravel

Como no tienes PHP instalado, usamos un contenedor de Docker que trae Composer:

```powershell
docker run --rm -v "${PWD}:/app" -w /app composer:latest create-project laravel/laravel livewire-app --no-install --no-scripts
```

**Qué hace este comando:**
- `docker run --rm`: ejecuta un contenedor y lo borra al terminar.
- `-v "${PWD}:/app"`: comparte tu carpeta actual con el contenedor.
- `composer:latest`: usa la imagen oficial de Composer.
- `create-project laravel/laravel livewire-app`: crea un proyecto Laravel en la carpeta `livewire-app`.
- `--no-install --no-scripts`: solo descarga los archivos del proyecto. Las librerías se instalarán después, dentro de Docker, porque así va mucho más rápido.

### 2.3 Abrir el proyecto en VS Code

```powershell
cd livewire-app
code .
```

En VS Code abre una terminal con **Terminal → New Terminal**. A partir de aquí **todos los comandos se ejecutan dentro de la carpeta `livewire-app`**.

---

## 3. Configurar Docker (PHP + PostgreSQL)

Vas a crear 4 archivos en la **raíz** del proyecto (al mismo nivel que `composer.json`). En VS Code: clic derecho en el panel izquierdo → **New File**.

### 3.1 `Dockerfile`: la receta del contenedor de PHP

```dockerfile
FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev libzip-dev unzip git \
    && docker-php-ext-install pdo_pgsql pgsql zip \
    && rm -rf /var/lib/apt/lists/*

COPY docker-php.ini /usr/local/etc/php/conf.d/zz-app.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
```

> Parte de PHP 8.4, instala la extensión para hablar con PostgreSQL (`pdo_pgsql`), añade Composer y, al arrancar, ejecuta el servidor de Laravel en el puerto 8000.

### 3.2 `docker-php.ini`: configuración de PHP

```ini
; Configuración PHP para desarrollo en Docker
; OPcache evita releer miles de archivos de vendor/ en cada request
; (los volúmenes montados desde Windows/macOS son lentos).
opcache.enable=1
opcache.enable_cli=1
opcache.validate_timestamps=1
opcache.revalidate_freq=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
realpath_cache_size=4096K
realpath_cache_ttl=600
memory_limit=512M
upload_max_filesize=20M
post_max_size=25M
```

### 3.3 `docker-compose.yml`: los dos contenedores juntos

```yaml
services:
  app:
    build: .
    container_name: livewire-app
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
      # vendor/ vive en un volumen de Docker: leerlo desde Windows/macOS es muy lento
      - vendor:/var/www/html/vendor
    depends_on:
      db:
        condition: service_healthy

  db:
    image: postgres:17
    container_name: livewire-db
    environment:
      POSTGRES_DB: livewire_app
      POSTGRES_USER: laravel
      POSTGRES_PASSWORD: secret
    ports:
      - "5432:5432"
    volumes:
      - pgdata:/var/lib/postgresql/data
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U laravel -d livewire_app"]
      interval: 5s
      timeout: 5s
      retries: 10

volumes:
  pgdata:
  vendor:
```

**Qué significa:**
- `app` es el contenedor de PHP. Tu carpeta del proyecto se comparte con él (`.:/var/www/html`), así que **cuando editas un archivo en VS Code, el contenedor ve el cambio al instante**.
- `db` es PostgreSQL 17, con la base de datos `livewire_app`, el usuario `laravel` y la contraseña `secret` (solo para desarrollo).
- `pgdata` guarda los datos de la base de datos aunque apagues Docker.
- `vendor` guarda las librerías de PHP dentro de Docker, que es mucho más rápido en Windows.

> ⚠️ **YAML es sensible a los espacios.** Usa espacios, nunca tabuladores, y respeta la sangría exacta.

### 3.4 `.dockerignore`

```
vendor
node_modules
.git
storage/logs
```

---

## 4. Arrancar la aplicación por primera vez

### 4.1 Crear el archivo `.env`

El archivo `.env` guarda la configuración privada (contraseñas, claves). Cópialo desde el ejemplo:

```powershell
copy .env.example .env
```

Abre `.env` en VS Code y **cambia estas líneas**:

```ini
APP_NAME="Livewire App"
APP_LOCALE=es

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=livewire_app
DB_USERNAME=laravel
DB_PASSWORD=secret
```

> En el archivo original, las líneas `DB_HOST`... empiezan con `#` (están comentadas). **Quita el `#`.**
> `DB_HOST=db` es el **nombre del servicio** en `docker-compose.yml`, no una dirección IP.

### 4.2 Construir la imagen e instalar las dependencias

```powershell
docker compose build
```
La primera vez tarda varios minutos porque descarga PHP.

```powershell
docker compose run --rm app composer install
```
Esto instala Laravel y sus librerías dentro del volumen `vendor`.

### 4.3 Encender los contenedores

```powershell
docker compose up -d
```
La opción `-d` los deja funcionando en segundo plano. Comprueba que están encendidos:

```powershell
docker compose ps
```
Deben aparecer `livewire-app` y `livewire-db` con estado **Up**.

### 4.4 Generar la clave de la aplicación y crear las tablas

```powershell
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

- `docker compose exec app ...` significa "ejecuta este comando **dentro** del contenedor `app`".
- `php artisan` es la herramienta de línea de comandos de Laravel.
- `key:generate` crea la clave de cifrado (`APP_KEY` en `.env`).
- `migrate` crea las tablas en PostgreSQL.

### 4.5 Instalar las dependencias de estilos

```powershell
npm install
npm run build
```

### 4.6 Ver la web

Abre **http://localhost:8000**. Debes ver la página de bienvenida de Laravel. 🎉

> 💡 **Atajo:** escribir `docker compose exec app php artisan` cada vez es largo. En PowerShell puedes crear un alias para la sesión actual:
> ```powershell
> function art { docker compose exec app php artisan @args }
> ```
> Después basta con escribir `art migrate`, `art make:livewire Algo`, etc.

---

## 5. Recorrido por las carpetas de Laravel

```
livewire-app/
├── app/
│   ├── Livewire/          ← tus componentes Livewire (lógica PHP)  [se crea en el paso 10]
│   ├── Models/            ← modelos: representan las tablas (User, Producto)
│   └── Providers/         ← configuración al arrancar (permisos, etc.)
├── database/
│   ├── migrations/        ← "planos" de las tablas de la base de datos
│   ├── factories/         ← generadores de datos falsos
│   └── seeders/           ← cargan datos de prueba
├── resources/
│   ├── css/app.css        ← estilos
│   └── views/             ← plantillas Blade (HTML)
├── routes/web.php         ← qué URL abre qué página
├── lang/                  ← traducciones                          [se crea en el paso 17]
├── public/                ← lo único accesible desde el navegador
├── .env                   ← configuración (¡no lo subas a GitHub!)
└── composer.json          ← lista de librerías PHP
```

---

## 6. Instalar Livewire

```powershell
docker compose exec app composer require livewire/livewire:^3.6
```

Eso es todo: Livewire se registra solo y añade su JavaScript automáticamente a las páginas que lo usen.

---

## 7. Estilos con Tailwind CSS

Laravel ya trae Tailwind configurado. Solo vamos a crear unas clases reutilizables para no repetir estilos.

### 7.1 Publicar las vistas de paginación

> En la Ruta B (local) este paso no es imprescindible, pero hazlo igualmente: así tu proyecto queda igual que el de la guía.

Como `vendor/` vive dentro de Docker, Tailwind (que corre en Windows) no puede leer las vistas de paginación de Laravel. Las copiamos al proyecto:

```powershell
docker compose exec app php artisan vendor:publish --tag=laravel-pagination
```

### 7.2 Reemplazar `resources/css/app.css`

Borra todo el contenido y pega esto:

```css
@import 'tailwindcss';

@source '../../storage/framework/views/*.php';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
}

/* Clases reutilizables para formularios y botones */
@layer components {
    .input {
        @apply w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500;
    }
    .label {
        @apply mb-1 block text-sm font-medium;
    }
    .error {
        @apply mt-1 text-sm text-red-600;
    }
    .btn-primary {
        @apply inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium disabled:opacity-50 bg-indigo-600 text-white hover:bg-indigo-700;
    }
    .btn-secondary {
        @apply inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium disabled:opacity-50 border border-slate-300 bg-white hover:bg-slate-50;
    }
    .btn-danger {
        @apply inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium disabled:opacity-50 bg-red-600 text-white hover:bg-red-700;
    }
    .card {
        @apply rounded-xl border border-slate-200 bg-white;
    }
}

/* Oculta elementos de Alpine.js hasta que cargan */
[x-cloak] {
    display: none !important;
}
```

### 7.3 Compilar mientras programas

Abre **una segunda terminal** en VS Code (botón `+` en el panel de la terminal) y deja corriendo:

```powershell
npm run dev
```

Así los estilos se recompilan solos cada vez que guardas un archivo. **No cierres esta terminal mientras trabajes.**

---

## 8. Base de datos: migraciones, modelos y datos de prueba

### 8.1 ¿Qué es una migración?
Es un archivo PHP que describe **cómo crear o modificar una tabla**. Sirve como control de versiones de la base de datos.

### 8.2 Añadir el campo "es administrador" a los usuarios

```powershell
docker compose exec app php artisan make:migration add_is_admin_to_users_table
```

Se crea un archivo en `database/migrations/` con la fecha en el nombre. Ábrelo y reemplaza su contenido por:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Se ejecuta con "php artisan migrate"
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });
    }

    // Se ejecuta con "php artisan migrate:rollback" (deshacer)
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
```

### 8.3 Crear el modelo `Producto` con su migración y su factory

```powershell
docker compose exec app php artisan make:model Producto -mf
```
`-m` crea también la migración y `-f` crea la *factory* (el generador de datos falsos).

**a) Migración:** abre `database/migrations/xxxx_create_productos_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();                                                      // id autoincremental
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();    // dueño del producto
            $table->string('nombre');
            $table->text('descripcion')->nullable();                           // opcional
            $table->decimal('precio', 10, 2);
            $table->integer('stock');
            $table->timestamps();                                              // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
```

> `cascadeOnDelete()` hace que, si se borra un usuario, se borren también sus productos.

**b) Modelo:** reemplaza `app/Models/Producto.php`:

```php
<?php

namespace App\Models;

use Database\Factories\ProductoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Fillable: campos que se pueden rellenar con create() o update()
#[Fillable(['nombre', 'descripcion', 'precio', 'stock'])]
class Producto extends Model
{
    /** @use HasFactory<ProductoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    // Un producto pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

> ⚠️ **Si tu versión de Laravel es la 12 o anterior**, el atributo `#[Fillable]` no existe. Usa en su lugar, dentro de la clase:
> `protected $fillable = ['nombre', 'descripcion', 'precio', 'stock'];`
> Para saber qué versión tienes: `docker compose exec app php artisan --version`

**c) Factory:** reemplaza `database/factories/ProductoFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nombre' => ucfirst(fake()->unique()->words(2, true)),
            'descripcion' => fake()->sentence(),
            'precio' => fake()->randomFloat(2, 1, 500),
            'stock' => fake()->numberBetween(0, 100),
        ];
    }
}
```

### 8.4 Actualizar el modelo `User`

Reemplaza `app/Models/User.php`:

```php
<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]   // nunca se envían al convertir a JSON
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',   // cifra la contraseña automáticamente al guardarla
            'is_admin' => 'boolean',
        ];
    }

    // Un usuario tiene muchos productos
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    // "Ana Pérez" → "AP" (para el avatar)
    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->take(2)
            ->map(fn (string $parte) => mb_substr($parte, 0, 1))
            ->implode('');
    }
}
```

### 8.5 Datos de prueba (seeder)

Reemplaza `database/seeders/DatabaseSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Usuarios de prueba (contraseña: "password"):
     *  - admin@example.com  → administrador
     *  - user@example.com   → usuario normal
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Usuario Demo',
            'email' => 'user@example.com',
        ]);

        Producto::factory(15)->for($admin)->create();
        Producto::factory(5)->for($user)->create();

        User::factory(8)->create();
    }
}
```

> La `UserFactory` que trae Laravel asigna la contraseña `password` por defecto.

### 8.6 Ejecutar todo

```powershell
docker compose exec app php artisan migrate:fresh --seed
```

> ⚠️ `migrate:fresh` **borra todas las tablas** y las vuelve a crear. Úsalo solo en desarrollo.

---

## 9. Layouts (la plantilla de las páginas)

Un *layout* es el "marco" común de todas las páginas: el `<head>`, el menú, etc. Livewire 3 busca por defecto `resources/views/components/layouts/app.blade.php`.

Crea las carpetas `resources/views/components/layouts/` y dentro estos dos archivos.

### 9.1 `guest.blade.php`: para el login y el registro

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <span class="inline-flex size-12 items-center justify-center rounded-xl bg-indigo-600 text-white text-xl font-bold">L</span>
            <h1 class="mt-3 text-2xl font-semibold">{{ config('app.name') }}</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
```

- `@vite(...)` carga el CSS y el JS compilados.
- `{{ $slot }}` es el lugar donde se inserta el contenido de cada página.
- `$title` viene del atributo `#[Title('...')]` de cada componente.

### 9.2 `app.blade.php`: para los usuarios con sesión iniciada (con menú)

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    @php
        $enlaces = [
            ['ruta' => 'dashboard', 'texto' => 'Dashboard'],
            ['ruta' => 'productos', 'texto' => 'Productos'],
        ];

        if (auth()->user()->can('admin')) {
            $enlaces[] = ['ruta' => 'usuarios', 'texto' => 'Usuarios'];
        }
    @endphp

    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 font-semibold">
                    <span class="inline-flex size-8 items-center justify-center rounded-lg bg-indigo-600 text-white">L</span>
                    {{ config('app.name') }}
                </a>

                <div class="flex gap-1">
                    @foreach ($enlaces as $enlace)
                        <a href="{{ route($enlace['ruta']) }}" wire:navigate
                           @class([
                               'px-3 py-2 rounded-md text-sm font-medium',
                               'bg-indigo-50 text-indigo-700' => request()->routeIs($enlace['ruta']),
                               'text-slate-600 hover:bg-slate-100' => ! request()->routeIs($enlace['ruta']),
                           ])>
                            {{ $enlace['texto'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('perfil') }}" wire:navigate class="flex items-center gap-2 text-sm hover:text-indigo-700">
                    <span class="inline-flex size-8 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold uppercase">
                        {{ auth()->user()->initials() }}
                    </span>
                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-slate-500 hover:text-red-600">Salir</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>
</body>
</html>
```

- `wire:navigate` cambia de página **sin recargarla entera** (más rápido, como una SPA).
- `@class([...])` añade clases CSS según una condición: aquí resalta el enlace de la página actual.
- `@csrf` añade un token de seguridad obligatorio en todos los formularios `POST`.

---

## 10. Crear los componentes y las rutas

### 10.1 Crear los componentes

Cada comando crea **2 archivos**: la clase PHP en `app/Livewire/` y la vista en `resources/views/livewire/`.

```powershell
docker compose exec app php artisan make:livewire Auth/Login
docker compose exec app php artisan make:livewire Auth/Register
docker compose exec app php artisan make:livewire Dashboard
docker compose exec app php artisan make:livewire Productos
docker compose exec app php artisan make:livewire Usuarios
docker compose exec app php artisan make:livewire Perfil
```

Resultado:

```
app/Livewire/Auth/Login.php        resources/views/livewire/auth/login.blade.php
app/Livewire/Auth/Register.php     resources/views/livewire/auth/register.blade.php
app/Livewire/Dashboard.php         resources/views/livewire/dashboard.blade.php
app/Livewire/Productos.php         resources/views/livewire/productos.blade.php
app/Livewire/Usuarios.php          resources/views/livewire/usuarios.blade.php
app/Livewire/Perfil.php            resources/views/livewire/perfil.blade.php
```

### 10.2 Definir el permiso de administrador

Abre `app/Providers/AppServiceProvider.php` y déjalo así:

```php
<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Solo los administradores pueden gestionar usuarios
        Gate::define('admin', fn (User $user) => $user->is_admin);
    }
}
```

Un **Gate** es una regla de permiso con nombre. Después puedes preguntar `auth()->user()->can('admin')` o proteger una ruta con `->middleware('can:admin')`.

### 10.3 Las rutas: `routes/web.php`

Reemplaza todo el archivo:

```php
<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Perfil;
use App\Livewire\Productos;
use App\Livewire\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rutas para visitantes (si ya iniciaste sesión, te redirige al dashboard)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Rutas protegidas (si no iniciaste sesión, te redirige a /login)
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/productos', Productos::class)->name('productos');
    Route::get('/perfil', Perfil::class)->name('perfil');

    Route::get('/usuarios', Usuarios::class)
        ->middleware('can:admin')
        ->name('usuarios');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
```

**Conceptos:**
- `Route::get('/productos', Productos::class)`: la URL `/productos` muestra el componente `Productos` como página completa.
- `->name('productos')`: da un nombre a la ruta para usarlo con `route('productos')`.
- **Middleware**: un filtro que se ejecuta antes de la página. `guest` permite entrar solo a quien no ha iniciado sesión, `auth` solo a quien sí la ha iniciado y `can:admin` solo a los administradores.

Comprueba las rutas:

```powershell
docker compose exec app php artisan route:list --except-vendor
```

> Ahora mismo, si visitas http://localhost:8000 te redirige a `/login` y verás una página casi vacía. Es normal: falta escribir los componentes.

---

## 11. Registro de usuarios

### 11.1 La clase: `app/Livewire/Auth/Register.php`

```php
<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]   // usa el layout sin menú
#[Title('Crear cuenta')]                // título de la pestaña del navegador
class Register extends Component
{
    // Las propiedades públicas se conectan con los inputs mediante wire:model
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

    // Se ejecuta automáticamente cuando cambia cualquier propiedad
    public function updated(string $propiedad): void
    {
        if (in_array($propiedad, ['name', 'email'])) {
            $this->validateOnly($propiedad);   // valida solo ese campo
        }
    }

    // Se ejecuta al enviar el formulario (wire:submit="register")
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
```

**Reglas de validación usadas:**
| Regla | Significado |
|---|---|
| `required` | Obligatorio. |
| `email` | Debe tener formato de correo. |
| `unique:users,email` | No puede existir ya en la tabla `users`. |
| `confirmed` | Debe coincidir con el campo `password_confirmation`. |
| `Password::defaults()` | Mínimo 8 caracteres. |

### 11.2 La vista: `resources/views/livewire/auth/register.blade.php`

```blade
<div>
    <h2 class="text-xl font-semibold mb-6">Crear cuenta</h2>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label for="name" class="label">Nombre</label>
            <input wire:model.blur="name" id="name" type="text" autocomplete="name" autofocus class="input">
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="label">Correo electrónico</label>
            <input wire:model.blur="email" id="email" type="email" autocomplete="email" class="input">
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="label">Contraseña</label>
            <input wire:model="password" id="password" type="password" autocomplete="new-password" class="input">
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="label">Confirmar contraseña</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" autocomplete="new-password" class="input">
        </div>

        <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="register">Registrarme</span>
            <span wire:loading wire:target="register">Creando cuenta...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" wire:navigate class="font-medium text-indigo-600 hover:underline">Inicia sesión</a>
    </p>
</div>
```

**Directivas de Livewire usadas:**
| Directiva | Qué hace |
|---|---|
| `wire:submit="register"` | Al enviar el formulario llama a `register()` sin recargar la página. |
| `wire:model="password"` | Conecta el input con `$password`. |
| `wire:model.blur="email"` | Igual, pero sincroniza al **salir** del campo, lo que activa la validación en vivo. |
| `wire:loading` / `wire:loading.remove` | Muestra u oculta un elemento mientras se procesa la petición. |
| `wire:target="register"` | Limita el `wire:loading` a esa acción concreta. |
| `wire:loading.attr="disabled"` | Desactiva el botón mientras carga, para evitar el doble clic. |
| `@error('campo')` | Muestra el mensaje de error de ese campo (`$message`). |

> ⚠️ **Regla de oro de Livewire:** la vista de un componente debe tener **un solo elemento raíz** (aquí, el `<div>` exterior).

**Pruébalo:** ve a http://localhost:8000/register y crea una cuenta.

---

## 12. Inicio de sesión

### 12.1 `app/Livewire/Auth/Login.php`

```php
<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Iniciar sesión')]
class Login extends Component
{
    // Otra forma de declarar reglas: el atributo #[Validate] junto a la propiedad
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        // Máximo 5 intentos fallidos por email + IP
        $clave = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($clave, 5)) {
            $segundos = RateLimiter::availableIn($clave);

            throw ValidationException::withMessages([
                'email' => "Demasiados intentos. Inténtalo de nuevo en {$segundos} segundos.",
            ]);
        }

        // Auth::attempt comprueba el email y la contraseña contra la base de datos
        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($clave);
            // La contraseña es una propiedad pública: no la devolvemos al navegador
            $this->reset('password');

            throw ValidationException::withMessages([
                'email' => 'Estas credenciales no coinciden con nuestros registros.',
            ]);
        }

        RateLimiter::clear($clave);
        session()->regenerate();   // seguridad: nueva sesión tras iniciar sesión

        // Vuelve a la página que el usuario intentaba abrir, o al dashboard
        return $this->redirectIntended(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
```

### 12.2 `resources/views/livewire/auth/login.blade.php`

```blade
<div>
    <h2 class="text-xl font-semibold mb-6">Iniciar sesión</h2>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="label">Correo electrónico</label>
            <input wire:model="email" id="email" type="email" autocomplete="email" autofocus class="input">
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="label">Contraseña</label>
            <input wire:model="password" id="password" type="password" autocomplete="current-password" class="input">
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input wire:model="remember" type="checkbox" class="rounded border-slate-300">
            Recordarme
        </label>

        <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="login">Entrar</span>
            <span wire:loading wire:target="login">Verificando...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}" wire:navigate class="font-medium text-indigo-600 hover:underline">Regístrate</a>
    </p>
</div>
```

> El **cierre de sesión** ya está hecho: es la ruta `POST /logout` del paso 10 y el botón "Salir" del layout.

---

## 13. Dashboard

### 13.1 `app/Livewire/Dashboard.php`

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    // Sin #[Layout]: usa el layout por defecto (components.layouts.app)
    public function render()
    {
        $productos = auth()->user()->productos();   // solo los productos del usuario

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
```

> Se usa `clone` porque cada consulta modifica el *query builder*. Si no se clonara, las condiciones se acumularían de una consulta a la siguiente.

### 13.2 `resources/views/livewire/dashboard.blade.php`

```blade
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-semibold">Hola, {{ auth()->user()->name }}</h1>
        <p class="text-slate-500">Resumen de tu inventario</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
            <p class="text-sm text-slate-500">Productos</p>
            <p class="text-3xl font-semibold mt-1">{{ $totalProductos }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-slate-500">Unidades en stock</p>
            <p class="text-3xl font-semibold mt-1">{{ number_format($totalStock) }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-slate-500">Valor del inventario</p>
            <p class="text-3xl font-semibold mt-1">${{ number_format($valorInventario, 2) }}</p>
        </div>
        <div class="card p-5">
            @if ($totalUsuarios !== null)
                <p class="text-sm text-slate-500">Usuarios registrados</p>
                <p class="text-3xl font-semibold mt-1">{{ $totalUsuarios }}</p>
            @else
                <p class="text-sm text-slate-500">Sin stock</p>
                <p class="text-3xl font-semibold mt-1 {{ $sinStock ? 'text-red-600' : '' }}">{{ $sinStock }}</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <h2 class="font-semibold">Últimos productos</h2>
            <a href="{{ route('productos') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">Ver todos →</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($ultimos as $producto)
                <li wire:key="ultimo-{{ $producto->id }}" class="px-5 py-3 flex justify-between text-sm">
                    <span>{{ $producto->nombre }}</span>
                    <span class="text-slate-500">${{ number_format($producto->precio, 2) }} · {{ $producto->stock }} uds.</span>
                </li>
            @empty
                <li class="px-5 py-6 text-center text-sm text-slate-500">
                    Aún no tienes productos.
                    <a href="{{ route('productos') }}" wire:navigate class="text-indigo-600 hover:underline">Crea el primero</a>
                </li>
            @endforelse
        </ul>
    </div>
</div>
```

**Pruébalo:** inicia sesión con `admin@example.com` y la contraseña `password`.

---

## 14. CRUD de productos

CRUD significa **C**reate (crear), **R**ead (leer), **U**pdate (actualizar) y **D**elete (borrar).

### 14.1 `app/Livewire/Productos.php`

```php
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
    use WithPagination;   // paginación sin recargar la página

    // Locked: el navegador NO puede cambiar este valor (seguridad)
    #[Locked]
    public ?int $editandoId = null;

    public bool $mostrarFormulario = false;

    // --- Campos del formulario ---
    #[Validate('required|string|min:3|max:100')]
    public string $nombre = '';

    #[Validate('nullable|string|max:500')]
    public string $descripcion = '';

    #[Validate('required|numeric|min:0|max:99999999')]
    public $precio = '';

    #[Validate('required|integer|min:0')]
    public $stock = '';

    // --- Búsqueda y orden ---
    // Url: la búsqueda se guarda en la URL (?buscar=...)
    #[Url(except: '')]
    public string $buscar = '';

    #[Locked]
    public string $ordenarPor = 'created_at';

    #[Locked]
    public string $direccion = 'desc';

    // Hook: se ejecuta cuando cambia $buscar → vuelve a la página 1
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
            auth()->user()->productos()->create($datos);   // asigna user_id automáticamente
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

    // Consulta base: SOLO los productos del usuario actual
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
```

**Puntos importantes:**
- **Seguridad:** `editar`, `guardar` y `eliminar` buscan siempre dentro de `auth()->user()->productos()`. Así, un usuario **no puede tocar los productos de otro** aunque cambie el `id`.
- **`#[Locked]`:** las propiedades públicas viajan al navegador y un usuario malicioso podría modificarlas. `Locked` lo impide.
- **`ilike`:** búsqueda sin distinguir mayúsculas. Solo existe en PostgreSQL; en MySQL se usa `like`.
- **`session()->flash()`:** guarda un mensaje que solo se muestra una vez.

### 14.2 `resources/views/livewire/productos.blade.php`

```blade
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold">Productos</h1>
        <button wire:click="crear" class="btn-primary">+ Nuevo producto</button>
    </div>

    @if (session('ok'))
        <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ session('ok') }}
        </div>
    @endif

    {{-- Búsqueda en vivo --}}
    <div class="relative">
        <input wire:model.live.debounce.300ms="buscar" type="search" placeholder="Buscar por nombre..." class="input">
        <span wire:loading wire:target="buscar" class="absolute right-3 top-2.5 text-xs text-slate-400">Buscando...</span>
    </div>

    {{-- Tabla --}}
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    @foreach (['nombre' => 'Nombre', 'precio' => 'Precio', 'stock' => 'Stock', 'created_at' => 'Creado'] as $campo => $titulo)
                        <th class="px-4 py-3 font-medium">
                            <button wire:click="ordenar('{{ $campo }}')" class="flex items-center gap-1 hover:text-slate-900">
                                {{ $titulo }}
                                @if ($ordenarPor === $campo)
                                    <span>{{ $direccion === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </button>
                        </th>
                    @endforeach
                    <th class="px-4 py-3 text-right font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($productos as $producto)
                    <tr wire:key="producto-{{ $producto->id }}" class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $producto->nombre }}</p>
                            @if ($producto->descripcion)
                                <p class="text-slate-500 text-xs truncate max-w-xs">{{ $producto->descripcion }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">${{ number_format($producto->precio, 2) }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-red-100 text-red-700' => $producto->stock === 0,
                                'bg-amber-100 text-amber-700' => $producto->stock > 0 && $producto->stock < 10,
                                'bg-green-100 text-green-700' => $producto->stock >= 10,
                            ])>{{ $producto->stock }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $producto->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <button wire:click="editar({{ $producto->id }})" class="text-indigo-600 hover:underline">Editar</button>
                            <button wire:click="eliminar({{ $producto->id }})"
                                    wire:confirm="¿Eliminar «{{ $producto->nombre }}»? Esta acción no se puede deshacer."
                                    class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                            {{ $buscar ? 'No hay productos que coincidan con la búsqueda.' : 'Aún no tienes productos.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $productos->links() }}

    {{-- Modal crear / editar --}}
    @if ($mostrarFormulario)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
             wire:keydown.escape.window="cancelar">
            <div class="card w-full max-w-lg p-6 shadow-xl" @click.outside="$wire.cancelar()">
                <h2 class="text-lg font-semibold mb-4">{{ $editandoId ? 'Editar producto' : 'Nuevo producto' }}</h2>

                <form wire:submit="guardar" class="space-y-4">
                    <div>
                        <label for="nombre" class="label">Nombre</label>
                        <input wire:model="nombre" id="nombre" type="text" class="input" autofocus>
                        @error('nombre') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="descripcion" class="label">Descripción <span class="text-slate-400">(opcional)</span></label>
                        <textarea wire:model="descripcion" id="descripcion" rows="3" class="input"></textarea>
                        @error('descripcion') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="precio" class="label">Precio</label>
                            <input wire:model="precio" id="precio" type="number" step="0.01" min="0" class="input">
                            @error('precio') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="stock" class="label">Stock</label>
                            <input wire:model="stock" id="stock" type="number" min="0" class="input">
                            @error('stock') <p class="error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cancelar" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="guardar">
                            <span wire:loading.remove wire:target="guardar">{{ $editandoId ? 'Actualizar' : 'Crear' }}</span>
                            <span wire:loading wire:target="guardar">Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
```

**Nuevas directivas:**
| Directiva | Qué hace |
|---|---|
| `wire:model.live.debounce.300ms` | Envía al servidor cada vez que escribes, esperando 300 ms de pausa. |
| `wire:click="editar(5)"` | Llama a un método y le pasa parámetros. |
| `wire:confirm="..."` | Pide confirmación antes de ejecutar la acción. |
| `wire:key="producto-5"` | Identifica cada fila. **Obligatorio en los bucles** para evitar errores visuales. |
| `wire:keydown.escape.window` | Ejecuta una acción al pulsar Esc. |
| `@click.outside="$wire.cancelar()"` | Alpine.js: al hacer clic fuera del modal, llama a `cancelar()` de Livewire. |
| `{{ $productos->links() }}` | Muestra los botones de paginación. |

---

## 15. Gestión de usuarios (solo administradores)

### 15.1 `app/Livewire/Usuarios.php`

```php
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
                // único, pero ignorando al propio usuario que se está editando
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
                ->withCount('productos')   // añade $usuario->productos_count
                ->when($this->buscar, fn ($q) => $q->where(fn ($q) => $q
                    ->where('name', 'ilike', "%{$this->buscar}%")
                    ->orWhere('email', 'ilike', "%{$this->buscar}%")))
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
```

> **¿Por qué `boot()` con `authorize()`?** La ruta `/usuarios` está protegida, pero las acciones de Livewire (`guardar`, `eliminar`...) llegan por otra URL (`/livewire/update`). `boot()` se ejecuta en **cada** petición, así que el permiso se comprueba siempre.

### 15.2 `resources/views/livewire/usuarios.blade.php`

```blade
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold">Usuarios</h1>
        <button wire:click="crear" class="btn-primary">+ Nuevo usuario</button>
    </div>

    @if (session('ok'))
        <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <input wire:model.live.debounce.300ms="buscar" type="search" placeholder="Buscar por nombre o correo..." class="input">

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3 font-medium">Usuario</th>
                    <th class="px-4 py-3 font-medium">Rol</th>
                    <th class="px-4 py-3 font-medium">Productos</th>
                    <th class="px-4 py-3 font-medium">Registrado</th>
                    <th class="px-4 py-3 text-right font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($usuarios as $usuario)
                    <tr wire:key="usuario-{{ $usuario->id }}" class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex size-8 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold uppercase">
                                    {{ $usuario->initials() }}
                                </span>
                                <div>
                                    <p class="font-medium">
                                        {{ $usuario->name }}
                                        @if ($usuario->id === auth()->id()) <span class="text-xs text-slate-400">(tú)</span> @endif
                                    </p>
                                    <p class="text-slate-500 text-xs">{{ $usuario->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if ($usuario->is_admin)
                                <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Admin</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">Usuario</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $usuario->productos_count }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <button wire:click="editar({{ $usuario->id }})" class="text-indigo-600 hover:underline">Editar</button>
                            @if ($usuario->id !== auth()->id())
                                <button wire:click="eliminar({{ $usuario->id }})"
                                        wire:confirm="¿Eliminar a {{ $usuario->name }}? También se borrarán sus productos."
                                        class="text-red-600 hover:underline">Eliminar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No se encontraron usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $usuarios->links() }}

    @if ($mostrarFormulario)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
             wire:keydown.escape.window="cancelar">
            <div class="card w-full max-w-lg p-6 shadow-xl" @click.outside="$wire.cancelar()">
                <h2 class="text-lg font-semibold mb-4">{{ $editandoId ? 'Editar usuario' : 'Nuevo usuario' }}</h2>

                <form wire:submit="guardar" class="space-y-4">
                    <div>
                        <label for="name" class="label">Nombre</label>
                        <input wire:model="name" id="name" type="text" class="input" autofocus>
                        @error('name') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="label">Correo electrónico</label>
                        <input wire:model="email" id="email" type="email" class="input">
                        @error('email') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="label">Contraseña</label>
                            <input wire:model="password" id="password" type="password" autocomplete="new-password" class="input"
                                   placeholder="{{ $editandoId ? 'Dejar vacía para no cambiar' : '' }}">
                            @error('password') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="label">Confirmar</label>
                            <input wire:model="password_confirmation" id="password_confirmation" type="password" autocomplete="new-password" class="input">
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="is_admin" type="checkbox" class="rounded border-slate-300"
                               @disabled($editandoId === auth()->id())>
                        Administrador
                        @if ($editandoId === auth()->id())
                            <span class="text-xs text-slate-400">(no puedes quitarte este rol)</span>
                        @endif
                    </label>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cancelar" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="guardar">
                            <span wire:loading.remove wire:target="guardar">{{ $editandoId ? 'Actualizar' : 'Crear' }}</span>
                            <span wire:loading wire:target="guardar">Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
```

---

## 16. Perfil del usuario

### 16.1 `app/Livewire/Perfil.php`

```php
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

    // mount(): se ejecuta UNA vez, al cargar la página (como un constructor)
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
            'current_password' => ['required', 'string', 'current_password'],   // comprueba la contraseña actual
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
```

### 16.2 `resources/views/livewire/perfil.blade.php`

```blade
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

    {{-- Eliminar cuenta (usa Alpine.js para mostrar/ocultar sin ir al servidor) --}}
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
```

> **Alpine.js** viene incluido con Livewire. `x-data`, `x-show` y `@click` funcionan **en el navegador, sin peticiones al servidor**. Úsalo para cosas puramente visuales, como abrir o cerrar paneles.

---

## 17. Traducir los mensajes al español

Crea la carpeta `lang/es/` en la raíz del proyecto (al lado de `app/`) con estos archivos.

### 17.1 `lang/es/validation.php`

```php
<?php

// Mensajes en español para las reglas usadas en el proyecto.
// Las reglas que no estén aquí usan el mensaje en inglés.

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

    // Nombres "bonitos" para los campos
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
```

### 17.2 `lang/es/pagination.php`

```php
<?php

return [
    'previous' => '&laquo; Anterior',
    'next' => 'Siguiente &raquo;',
];
```

### 17.3 `lang/es.json` (en `lang/`, no dentro de `es/`)

```json
{
    "Showing": "Mostrando",
    "to": "a",
    "of": "de",
    "results": "resultados",
    "Pagination Navigation": "Navegación de paginación",
    "Go to page :page": "Ir a la página :page"
}
```

> Esto funciona porque en `.env` pusiste `APP_LOCALE=es`. Si cambias el `.env`, ejecuta `docker compose exec app php artisan config:clear`.

---

## 18. Probar todo

### 18.1 Reiniciar la base de datos con datos de prueba

```powershell
docker compose exec app php artisan migrate:fresh --seed
```

### 18.2 Lista de comprobación

Abre http://localhost:8000 y marca cada punto:

**Autenticación**
- [ ] Al entrar a `/` sin sesión, te redirige a `/login`.
- [ ] En `/register`, si envías el formulario vacío, aparecen errores en español.
- [ ] Al escribir un correo ya registrado y salir del campo, aparece "Este correo electrónico ya está registrado".
- [ ] Al registrarte, entras directamente al Dashboard.
- [ ] "Salir" te lleva a `/login`.
- [ ] Una contraseña incorrecta muestra "Estas credenciales no coinciden...".
- [ ] `admin@example.com` con la contraseña `password` funciona.

**Productos**
- [ ] Crear un producto: aparece en la tabla con el mensaje "Producto creado".
- [ ] Guardar con campos vacíos muestra errores.
- [ ] Editar cambia los datos.
- [ ] Eliminar pide confirmación.
- [ ] Al buscar se filtra mientras escribes y la URL cambia (`?buscar=...`).
- [ ] Al hacer clic en los títulos de las columnas se ordena la tabla.
- [ ] La paginación funciona (el administrador tiene 15 productos).
- [ ] Esc o un clic fuera cierra el modal.

**Permisos**
- [ ] Con `user@example.com` no aparece el menú "Usuarios".
- [ ] Con `user@example.com`, al ir a `/usuarios` aparece el error **403**.
- [ ] Cada usuario solo ve sus propios productos.

**Usuarios (como admin)**
- [ ] Crear, editar y eliminar usuarios.
- [ ] No puedes eliminarte a ti mismo ni quitarte el rol de administrador.

**Perfil**
- [ ] Cambiar el nombre: se actualiza en el menú al recargar.
- [ ] Una contraseña actual incorrecta muestra un error.
- [ ] Eliminar la cuenta pide la contraseña.

---

## 19. Solución de problemas

| Error o síntoma | Causa | Solución |
|---|---|---|
| `error during connect ... dockerDesktopLinuxEngine` | Docker Desktop no está abierto. | Ábrelo y espera a que diga *Engine running*. |
| `port is already allocated` (5432 u 8000) | Otro programa usa ese puerto. | En `docker-compose.yml` cambia `"5432:5432"` por `"5433:5432"`, o `"8000:8000"` por `"8080:8000"`. |
| `No application encryption key has been specified` | Falta la `APP_KEY`. | `docker compose exec app php artisan key:generate` |
| `relation "sessions" does not exist` | No se ejecutaron las migraciones. | `docker compose exec app php artisan migrate` |
| `could not translate host name "db"` | `DB_HOST` está mal o la base de datos no está encendida. | Revisa `DB_HOST=db` en `.env` y ejecuta `docker compose ps`. |
| `Vite manifest not found` | No se compilaron los estilos. | `npm run build` o deja `npm run dev` corriendo. |
| La página se ve sin estilos | Vite no está corriendo o no se recompiló. | `npm run dev` |
| `Unable to find component: [xxx]` | El nombre de la clase o el namespace no coinciden. | Revisa que el `namespace` y el nombre del archivo sean iguales. |
| `Livewire only supports one HTML element per component` | La vista tiene más de un elemento raíz. | Envuelve todo en un único `<div>`. |
| `419 Page Expired` | La sesión o el token CSRF caducaron. | Recarga la página. |
| `Cannot update locked property` | Algo intentó modificar una propiedad `#[Locked]`. | Es una protección de seguridad: cambia ese valor solo desde PHP. |
| El cambio en `.env` no hace efecto | Laravel guarda la configuración en caché. | `docker compose exec app php artisan config:clear` |
| Todo va muy lento (más de 5 s) | `vendor/` se está leyendo desde Windows. | Comprueba que existe la línea `- vendor:/var/www/html/vendor` en `docker-compose.yml`. |
| `Class "..." not found` tras crear un archivo | El autoload está desactualizado. | `docker compose exec app composer dump-autoload` |

**Ver los errores en detalle:**

```powershell
docker compose logs -f app
```

También puedes abrir el archivo `storage/logs/laravel.log`.

---

## 20. Chuleta de comandos

```powershell
# --- Docker ---
docker compose up -d                 # encender
docker compose down                  # apagar
docker compose down -v               # apagar y BORRAR la base de datos y vendor
docker compose ps                    # ver estado
docker compose logs -f app           # ver logs en vivo (Ctrl+C para salir)
docker compose restart app           # reiniciar PHP

# --- Artisan (Laravel) ---
docker compose exec app php artisan make:livewire NombreComponente
docker compose exec app php artisan make:model Nombre -mf
docker compose exec app php artisan make:migration nombre_de_la_migracion
docker compose exec app php artisan migrate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan route:list --except-vendor
docker compose exec app php artisan tinker          # consola interactiva de PHP
docker compose exec app php artisan optimize:clear  # limpiar todas las cachés

# --- Composer ---
docker compose exec app composer require paquete/nombre

# --- Estilos ---
npm run dev      # mientras desarrollas
npm run build    # versión final
```

**Conectarte a PostgreSQL** con DBeaver, TablePlus o pgAdmin:
host `localhost` · puerto `5432` · base de datos `livewire_app` · usuario `laravel` · contraseña `secret`

---

## 21. Glosario

| Término | Significado |
|---|---|
| **Artisan** | La herramienta de comandos de Laravel (`php artisan ...`). |
| **Blade** | El motor de plantillas de Laravel (`.blade.php`). |
| **Componente Livewire** | Una clase PHP más su vista Blade, que juntas forman una parte interactiva de la página. |
| **CRUD** | Crear, leer, actualizar y borrar. |
| **CSRF** | Un ataque que se previene con un token; por eso los formularios llevan `@csrf`. |
| **Eloquent** | El ORM de Laravel: permite trabajar con la base de datos usando objetos PHP (`Producto::create()`). |
| **Factory** | Un generador de datos falsos para pruebas. |
| **Gate** | Una regla de permiso con nombre (`can('admin')`). |
| **Hidratación** | Cuando Livewire reconstruye tu componente en cada petición a partir del estado guardado. |
| **Hook** | Un método que Livewire llama automáticamente (`mount`, `boot`, `updatedX`). |
| **Layout** | La plantilla común de todas las páginas. |
| **Middleware** | Un filtro que se ejecuta antes de una ruta (`auth`, `guest`, `can:admin`). |
| **Migración** | Un archivo que crea o modifica tablas. |
| **Modelo** | Una clase que representa una tabla (`User` corresponde a `users`). |
| **Seeder** | Un archivo que carga datos iniciales o de prueba. |
| **Validación** | Las reglas que deben cumplir los datos antes de guardarlos. |

---

## 22. Siguientes pasos

Cuando todo funcione, prueba a ampliar el proyecto con estos ejercicios:

1. **Categorías:** crea un modelo `Categoria` y relaciónalo con `Producto` (`belongsTo` / `hasMany`). Añade un `<select>` al formulario.
2. **Imagen del producto:** usa el trait `WithFileUploads` de Livewire y `wire:model="foto"`.
3. **Componentes hijos:** pasa el modal de productos a un componente aparte y comunícalo con el padre mediante `$this->dispatch('producto-guardado')` y `#[On('producto-guardado')]`.
4. **Form Objects:** mueve los campos del formulario a una clase `ProductoForm` (`php artisan livewire:form ProductoForm`).
5. **Recuperar contraseña** por correo (`Password::sendResetLink`).
6. **Verificación de email** (`MustVerifyEmail`).
7. **Tests:** prueba tus componentes con `Livewire::test(Productos::class)->call('guardar')->assertHasErrors()`.

**Documentación oficial:**
- Livewire 3: https://livewire.laravel.com/docs
- Laravel: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs

¡Felicidades por tu primera app con Laravel y Livewire! 🚀

---

## Anexo: instalación local (sin Docker)

Este anexo **reemplaza los pasos 1 a 4**. Cuando lo termines, continúa en el **[paso 5](#5-recorrido-por-las-carpetas-de-laravel)**.

### L1. Instalar PHP, Composer y el instalador de Laravel

Laravel ofrece un instalador oficial que configura todo de una vez.

1. Abre **PowerShell como administrador**: menú Inicio → escribe "PowerShell" → clic derecho → **Ejecutar como administrador**.
2. Pega este comando (es el que indica la documentación oficial de Laravel):

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
```

3. **Cierra PowerShell y ábrelo de nuevo** (esta vez no hace falta como administrador).
4. Comprueba la instalación:

```powershell
php -v
composer -V
laravel --version
```

> **Alternativa con interfaz gráfica:** [Laravel Herd](https://herd.laravel.com/windows) instala PHP, Composer y el instalador de Laravel con un asistente. Si usas Herd, sáltate este paso L1.

### L2. Instalar Node.js

Descarga la versión **LTS** de https://nodejs.org/, instálala y comprueba la instalación:

```powershell
node --version
npm --version
```

### L3. Instalar PostgreSQL

1. Descarga el instalador de https://www.postgresql.org/download/windows/ (botón *Download the installer*, versión **17**).
2. Durante la instalación:
   - Deja marcados **PostgreSQL Server**, **pgAdmin 4** y **Command Line Tools**.
   - Te pedirá una **contraseña para el usuario `postgres`**. **Apúntala**, porque la necesitarás en el `.env`.
   - Puerto: deja **5432**.
   - Al final, desmarca *Stack Builder* (no hace falta).

### L4. Crear la base de datos

**Opción 1: con pgAdmin (gráfico)**
1. Abre **pgAdmin 4** desde el menú Inicio.
2. A la izquierda, despliega *Servers* → *PostgreSQL 17* (te pedirá la contraseña que apuntaste).
3. Clic derecho en **Databases** → **Create** → **Database...**
4. En *Database* escribe `livewire_app` y pulsa **Save**.

**Opción 2: con la consola**
Abre **SQL Shell (psql)** desde el menú Inicio, pulsa Enter en todo (servidor, base de datos, puerto, usuario) hasta que pida la contraseña, escríbela y ejecuta:

```sql
CREATE DATABASE livewire_app;
```

### L5. Activar la extensión de PostgreSQL en PHP

PHP necesita una extensión para conectarse a PostgreSQL. Comprueba si ya está activa:

```powershell
php -m | findstr pgsql
```

- Si aparecen `pdo_pgsql` y `pgsql`, ya está todo listo: pasa al paso L6.
- Si no aparece nada, haz lo siguiente:

1. Averigua qué archivo `php.ini` usa PHP:
   ```powershell
   php --ini
   ```
   Fíjate en la línea **Loaded Configuration File** (por ejemplo, `C:\Users\TU_USUARIO\.config\herd-lite\bin\php.ini`).
2. Abre ese archivo con VS Code y busca estas dos líneas:
   ```ini
   ;extension=pdo_pgsql
   ;extension=pgsql
   ```
3. **Quita el `;`** del principio para activarlas:
   ```ini
   extension=pdo_pgsql
   extension=pgsql
   ```
4. Guarda el archivo, cierra y vuelve a abrir PowerShell, y repite `php -m | findstr pgsql`.

> Si `php --ini` dice `Loaded Configuration File: (none)`, en la misma carpeta de PHP hay un archivo `php.ini-development`. Cópialo con el nombre `php.ini` y repite los pasos anteriores.

### L6. Crear el proyecto Laravel

```powershell
cd $HOME\Documents
mkdir proyectos
cd proyectos
composer create-project laravel/laravel livewire-app
cd livewire-app
code .
```

> Este comando ya crea el `.env`, genera la `APP_KEY` y prepara una base de datos SQLite temporal. En el siguiente paso la cambias por PostgreSQL.

### L7. Configurar el `.env` para PostgreSQL

Abre `.env` y cambia estas líneas (quita el `#` de las que lo tengan):

```ini
APP_NAME="Livewire App"
APP_LOCALE=es

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=livewire_app
DB_USERNAME=postgres
DB_PASSWORD=la_contraseña_que_apuntaste
```

> ⚠️ Fíjate en dos diferencias con la Ruta A: aquí `DB_HOST` es `127.0.0.1` (tu propio ordenador) y no `db`, y el usuario es `postgres`.

Crea las tablas en PostgreSQL:

```powershell
php artisan config:clear
php artisan migrate
```

Si funciona, verás la lista de tablas creadas (`users`, `sessions`, `cache`, `jobs`...).

> Puedes borrar el archivo `database/database.sqlite`, que ya no se usa.

### L8. Instalar las dependencias de estilos

```powershell
npm install
npm run build
```

### L9. Arrancar la aplicación

Necesitas **dos terminales abiertas** en VS Code (botón `+` del panel de la terminal):

**Terminal 1: el servidor de Laravel**
```powershell
php artisan serve
```

**Terminal 2: los estilos (se recompilan solos)**
```powershell
npm run dev
```

Abre **http://127.0.0.1:8000** y verás la página de bienvenida de Laravel. 🎉

> Para detener cualquiera de los dos, pulsa `Ctrl + C` en su terminal. Cada vez que vuelvas a trabajar en el proyecto, abre las dos terminales y ejecuta de nuevo esos dos comandos.

### L10. Continuar con la guía

Ve al **[paso 5](#5-recorrido-por-las-carpetas-de-laravel)**. Recuerda quitar `docker compose exec app` de todos los comandos. Por ejemplo, el paso 6 queda así:

```powershell
composer require livewire/livewire:^3.6
```

Y el paso 10.1 así:

```powershell
php artisan make:livewire Auth/Login
```

### Problemas típicos de la instalación local

| Error | Solución |
|---|---|
| `'php' no se reconoce como un comando` | Cierra y vuelve a abrir PowerShell. Si sigue pasando, reinicia Windows (el instalador modificó el PATH). |
| `could not find driver` | La extensión `pdo_pgsql` no está activa. Repite el paso L5. |
| `password authentication failed for user "postgres"` | `DB_PASSWORD` del `.env` no coincide con la contraseña del instalador. Revísala y ejecuta `php artisan config:clear`. |
| `database "livewire_app" does not exist` | No creaste la base de datos. Repite el paso L4. |
| `Connection refused` en el puerto 5432 | El servicio de PostgreSQL está detenido. Abre **Servicios** de Windows (`services.msc`), busca `postgresql-x64-17` y pulsa **Iniciar**. |
| `Failed to listen on 127.0.0.1:8000` | El puerto está ocupado. Usa `php artisan serve --port=8080`. |
| La página se ve sin estilos | Falta `npm run dev` en la segunda terminal. |

### Chuleta de comandos (Ruta B)

```powershell
php artisan serve                          # arrancar el servidor
npm run dev                                # compilar estilos en vivo
php artisan make:livewire NombreComponente
php artisan make:model Nombre -mf
php artisan migrate
php artisan migrate:fresh --seed           # borrar todo y cargar datos de prueba
php artisan route:list --except-vendor
php artisan tinker
php artisan optimize:clear
composer require paquete/nombre
```

**Conectarte a la base de datos:** usa pgAdmin (ya instalado) con host `127.0.0.1`, puerto `5432`, usuario `postgres` y la contraseña que elegiste.
