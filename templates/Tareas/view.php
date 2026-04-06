<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tarea $tarea
 * @var array<string, string> $opcionesEstado
 */
?>
<div class="tareas view content">
    <?= $this->Html->link(__('Volver'), ['action' => 'index'], ['class' => 'button float-right']) ?>
    <h3><?= h($tarea->titulo) ?></h3>
    <table>
        <tr>
            <th><?= __('Estado') ?></th>
            <td><?= h($opcionesEstado[$tarea->estado] ?? $tarea->estado) ?></td>
        </tr>
        <tr>
            <th><?= __('Fecha límite') ?></th>
            <td><?= $tarea->fecha_limite ? h($tarea->fecha_limite->i18nFormat()) : '—' ?></td>
        </tr>
        <tr>
            <th><?= __('Descripción (español)') ?></th>
            <td><?= $tarea->descripcion_es ? nl2br(h($tarea->descripcion_es)) : '—' ?></td>
        </tr>
        <tr>
            <th><?= __('Descripción (inglés)') ?></th>
            <td><?= $tarea->descripcion_en ? nl2br(h($tarea->descripcion_en)) : '—' ?></td>
        </tr>
        <tr>
            <th><?= __('Descripción según tu idioma') ?></th>
            <td><?= nl2br(h($tarea->descripcionParaLocale())) ?></td>
        </tr>
    </table>
    <p>
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $tarea->id], ['class' => 'button']) ?>
        <?= $this->Form->postLink(__('Eliminar'), ['action' => 'delete', $tarea->id], ['class' => 'button button-outline', 'confirm' => __('¿Eliminar esta tarea?')]) ?>
    </p>
</div>
