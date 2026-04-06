# Informe IMRD — aplicación de tareas multilenguaje

## Introducción

Este proyecto es una aplicación web para la gestión personal de tareas, desarrollada como entregable académico. Los usuarios se registran, inician sesión y administran tareas con título, descripciones en dos idiomas, estado y fecha límite. La interfaz puede mostrarse en varios idiomas (entre ellos español, inglés, chino mandarín, hindi, árabe, francés, ruso, portugués, alemán y japonés) según la preferencia guardada en el perfil. El objetivo es cumplir requisitos de autenticación, CRUD, internacionalización y búsqueda/filtrado sobre un stack PHP con CakePHP y MariaDB/MySQL.

## Métodos

- **Framework y lenguaje:** CakePHP 5 y PHP 8.2+, siguiendo convenciones MVC (controladores, plantillas, capa ORM).
- **Base de datos:** tablas `users`, `perfiles` (relación uno a uno con el usuario, campo `idioma`) y `tareas` (clave foránea `user_id`, campos `descripcion_es` y `descripcion_en`). El esquema se versiona con migraciones en `config/Migrations/` y un script SQL alternativo en `config/schema/entregable_tareas.sql`.
- **Autenticación:** sesión PHP con clave `Auth`; las rutas protegidas se centralizan en `AppController::beforeFilter`, excepto `login`, `register` y `logout`.
- **Internacionalización:** archivos PO en `resources/locales/` (entre otros `es_ES`, `en_US`, `zh_CN`, `hi_IN`, `ar_SA`, `fr_FR`, `ru_RU`, `pt_BR`, `de_DE`, `ja_JP`); `I18n::setLocale()` se aplica tras el login y en cada petición autenticada según `Auth.locale`.
- **Filtrado:** parámetros GET en el listado de tareas (estado, texto en título/descripciones, rango de fechas sobre `fecha_limite`).
- **Herramientas:** Composer para dependencias, Git para control de versiones, asistente de código/IA para acelerar planteamiento y revisión (detalle en `BITACORA_IA.md`).

## Resultados

- Registro de usuario con elección de idioma inicial y creación automática de la fila en `perfiles`.
- Login y logout funcionales; redirección a la lista de tareas tras autenticación correcta.
- CRUD completo de tareas restringido al propietario (`user_id`).
- Filtros y búsqueda en el índice de tareas.
- Cambio de idioma de interfaz desde el perfil, con persistencia en base de datos y sesión.
- Descripciones de tarea en español e inglés, mostrando según el locale activo con reserva al otro idioma si falta texto.

## Discusión

El desarrollo permitió practicar el ciclo completo: modelo de datos, reglas de validación, autorización por fila (solo mis tareas) e i18n real con catálogos de traducción. Como limitaciones actuales cabe citar la ausencia de roles administrativos refinados (cualquier usuario autenticado puede acceder al CRUD de países/usuarios si conoce la URL) y la dependencia de sesión sin token API. Mejoras futuras: pruebas automatizadas ampliadas, políticas de autorización explícitas (por ejemplo Policies), y carga perezosa o caché de traducciones en producción.
