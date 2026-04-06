# App de tareas (CakePHP 5)

Aplicación web de gestión de tareas por usuario con registro, sesión, perfil con idioma (español, inglés, chino mandarín, hindi, árabe, francés, ruso, portugués, alemán y japonés), CRUD de tareas, filtros y descripciones bilingües por tarea.

## Requisitos

- PHP 8.2 o superior
- Composer
- MariaDB o MySQL 5.7+ (compatible con el driver `Mysql` de CakePHP)
- Apache con `mod_rewrite` **o** el servidor integrado de PHP/Cake (`bin/cake server`)

## Instalación

1. Clonar el repositorio e ir al directorio del proyecto.

2. Instalar dependencias PHP:

   ```bash
   composer install
   ```

3. Configurar la base de datos: copiar `config/app_local.example.php` a `config/app_local.php` y editar el array `Datasources` con host, usuario, contraseña y nombre de la base de datos.

4. Crear la base de datos vacía en MariaDB/MySQL.

5. Aplicar el esquema:

   **Opción A — migraciones (recomendado):**

   ```bash
   bin/cake migrations migrate
   ```

   **Opción B — SQL manual:** ejecutar el script [config/schema/entregable_tareas.sql](config/schema/entregable_tareas.sql) sobre la misma base donde ya existe la tabla `users` (y el resto de tablas que uses).

6. (Opcional) Copiar `config/.env.example` a `config/.env` y ajustar variables si tu entorno las usa.

## Cómo ejecutar

**Servidor de desarrollo CakePHP:**

```bash
bin/cake server -p 8765
```

Abrir `http://localhost:8765` (la raíz muestra el inicio de sesión).

**Apache:** configurar el `DocumentRoot` al directorio `webroot/` del proyecto.

## Uso

- **Registro:** enlace «Registrar» en la barra superior (sin sesión).
- **Inicio de sesión:** `/` (ruta por defecto).
- **Tareas:** cada usuario solo ve y gestiona sus propias tareas; en el listado hay filtros por estado, rango de fecha límite y texto.
- **Perfil:** idioma de interfaz entre los anteriores y datos personales; el código se guarda en `perfiles.idioma` (p. ej. `es_ES`, `zh_CN`) y se sincroniza con `users.language`.
- **Países / Usuarios:** módulos adicionales de ejemplo (requieren sesión).

## Estructura de base de datos relevante

- `users` — usuarios (incluye `language` para compatibilidad).
- `perfiles` — una fila por usuario (`idioma`, `biografia`).
- `tareas` — `user_id`, `titulo`, `descripcion_es`, `descripcion_en`, `estado`, `fecha_limite`.

## Documentación del entregable

- [docs/INFORME_IMRD.md](docs/INFORME_IMRD.md) — informe en formato IMRD.
- [docs/BITACORA_IA.md](docs/BITACORA_IA.md) — bitácora de uso de IA.
- [docs/EVIDENCIAS.md](docs/EVIDENCIAS.md) — qué capturas o video conviene entregar.

## Licencia

MIT (igual que el esqueleto oficial de CakePHP).
