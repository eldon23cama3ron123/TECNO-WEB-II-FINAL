# Bitácora de uso de inteligencia artificial

Este documento resume cómo se utilizó asistencia de IA (por ejemplo ChatGPT o el asistente integrado en el editor) durante el proyecto. 

| Fecha | Pregunta o petición (resumen) | Respuesta / idea recibida (resumen) | Qué se aplicó o se cambió en el proyecto |
|--------|-------------------------------|--------------------------------------|------------------------------------------|
| *(2/04/26)* | Comparar el proyecto con la rúbrica de entrega y listar brechas | Lista de faltantes: CRUD tareas, i18n real, perfil, Git, documentación | Sirvió como hoja de ruta; se implementó tareas, perfiles, locales y documentos |
| *(4/04/26)* | Diseño de tablas `tareas` y `perfiles` con FK a `users` | Propuesta de columnas y restricciones | Se adoptó `descripcion_es` / `descripcion_en`, estados y `fecha_limite` |
| *(5/04/26)* | Buenas prácticas de `I18n::setLocale` en CakePHP 5 | Aplicar locale en `beforeFilter` según sesión | Se centralizó en `AppController` y se guarda `locale` en `Auth` |
| *(5/04/26)* | Revisión de filtros ORM por texto y fechas | Uso de `LIKE` y comparaciones en `fecha_limite` | Implementado en `TareasController::index` |

**Nota ética:** la IA ayudó a estructurar y revisar código; las decisiones finales, pruebas manuales y la entrega en el aula son responsabilidad del equipo o alumno.
