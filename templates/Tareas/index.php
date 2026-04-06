<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Tarea> $tareas
 * @var array<string, string> $estadosFiltro
 * @var string $estadoSeleccionado
 * @var string $q
 * @var string $fechaDesde
 * @var string $fechaHasta
 * @var array<string, string> $opcionesEstado
 */
?>
<div class="tareas index content">
    <?= $this->Html->link(__('Nueva tarea'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Mis tareas') ?></h3>

    <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index'], 'class' => 'row']) ?>
    <fieldset style="margin-bottom: 1.5rem;">
        <legend><?= __('Filtros y búsqueda') ?></legend>
        <div class="row">
            <div class="column">
                <?= $this->Form->control('estado', [
                    'type' => 'select',
                    'options' => $estadosFiltro,
                    'value' => $estadoSeleccionado,
                    'label' => __('Estado'),
                    'empty' => false,
                ]) ?>
            </div>
            <div class="column">
                <?= $this->Form->control('q', [
                    'type' => 'text',
                    'value' => $q,
                    'label' => __('Texto (título o descripción)'),
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <?= $this->Form->control('fecha_desde', [
                    'type' => 'date',
                    'value' => $fechaDesde,
                    'label' => __('Fecha límite desde'),
                ]) ?>
            </div>
            <div class="column">
                <?= $this->Form->control('fecha_hasta', [
                    'type' => 'date',
                    'value' => $fechaHasta,
                    'label' => __('Fecha límite hasta'),
                ]) ?>
            </div>
        </div>
        <?= $this->Form->button(__('Filtrar')) ?>
        <?= $this->Html->link(__('Limpiar'), ['action' => 'index'], ['class' => 'button button-outline']) ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= __('Título') ?></th>
                    <th><?= __('Descripción (idioma actual)') ?></th>
                    <th><?= __('Estado') ?></th>
                    <th><?= __('Fecha límite') ?></th>
                    <th class="actions"><?= __('Acciones') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tareas as $tarea): ?>
                <tr>
                    <td><?= h($tarea->titulo) ?></td>
                    <td><?= nl2br(h($tarea->descripcionParaLocale())) ?></td>
                    <td><?= h($opcionesEstado[$tarea->estado] ?? $tarea->estado) ?></td>
                    <td><?= $tarea->fecha_limite ? h($tarea->fecha_limite->i18nFormat()) : '—' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $tarea->id]) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $tarea->id]) ?>
                        <?= $this->Form->postLink(__('Eliminar'), ['action' => 'delete', $tarea->id], ['confirm' => __('¿Eliminar esta tarea?')]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primero')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('siguiente') . ' >') ?>
            <?= $this->Paginator->last(__('último') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} registro(s) de {{count}} total')) ?></p>
    </div>
</div>
