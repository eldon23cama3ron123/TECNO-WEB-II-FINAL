<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateTareasAndPerfiles extends BaseMigration
{
    /**
     * Tablas perfiles (1:1 con users) y tareas (por usuario).
     *
     * @return void
     */
    public function change(): void
    {
        $perfiles = $this->table('perfiles');
        $perfiles
            ->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('idioma', 'string', ['limit' => 10, 'default' => 'es_ES', 'null' => false])
            ->addColumn('biografia', 'text', ['null' => true, 'default' => null])
            ->addColumn('created', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('modified', 'datetime', ['null' => true, 'default' => null])
            ->addIndex(['user_id'], ['unique' => true, 'name' => 'uniq_perfiles_user_id'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        $tareas = $this->table('tareas');
        $tareas
            ->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('titulo', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('descripcion_es', 'text', ['null' => true, 'default' => null])
            ->addColumn('descripcion_en', 'text', ['null' => true, 'default' => null])
            ->addColumn('estado', 'string', ['limit' => 32, 'default' => 'pendiente', 'null' => false])
            ->addColumn('fecha_limite', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('created', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('modified', 'datetime', ['null' => true, 'default' => null])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['user_id'], ['name' => 'idx_tareas_user_id'])
            ->addIndex(['estado'], ['name' => 'idx_tareas_estado'])
            ->addIndex(['fecha_limite'], ['name' => 'idx_tareas_fecha_limite'])
            ->create();
    }
}
