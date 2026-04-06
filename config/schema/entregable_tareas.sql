-- Esquema adicional para el entregable (MariaDB/MySQL).
-- Ejecutar sobre la misma base donde ya existe la tabla `users`.

CREATE TABLE IF NOT EXISTS perfiles (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    idioma VARCHAR(10) NOT NULL DEFAULT 'es_ES',
    biografia TEXT NULL,
    created DATETIME NULL,
    modified DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_perfiles_user_id (user_id),
    CONSTRAINT fk_perfiles_user FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tareas (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion_es TEXT NULL,
    descripcion_en TEXT NULL,
    estado VARCHAR(32) NOT NULL DEFAULT 'pendiente',
    fecha_limite DATETIME NULL,
    created DATETIME NULL,
    modified DATETIME NULL,
    PRIMARY KEY (id),
    KEY idx_tareas_user_id (user_id),
    KEY idx_tareas_estado (estado),
    KEY idx_tareas_fecha_limite (fecha_limite),
    CONSTRAINT fk_tareas_user FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
