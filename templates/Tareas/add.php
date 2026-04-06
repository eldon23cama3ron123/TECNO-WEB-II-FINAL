<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tarea $tarea
 * @var array<string, string> $opcionesEstado
 */
?>
<div class="tareas form content">
    <?= $this->Html->link(__('Volver al listado'), ['action' => 'index'], ['class' => 'button float-right']) ?>
    <h3><?= __('Nueva tarea') ?></h3>
    <?= $this->Form->create($tarea) ?>
    <fieldset>
        <legend><?= __('Datos de la tarea') ?></legend>
        <?php
            echo $this->Form->control('titulo', ['label' => __('Título')]);
            echo $this->Form->control('descripcion_es', ['type' => 'textarea', 'label' => __('Descripción (español)')]);
            echo $this->Form->control('descripcion_en', ['type' => 'textarea', 'label' => __('Descripción (inglés)')]);
            echo $this->Form->control('estado', ['type' => 'select', 'options' => $opcionesEstado, 'label' => __('Estado')]);
            echo $this->Form->control('fecha_limite', ['type' => 'datetime-local', 'label' => __('Fecha límite'), 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Guardar')) ?>
    <?= $this->Form->end() ?>
</div>
