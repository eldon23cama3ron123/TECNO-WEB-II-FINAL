<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Pais> $paises
 */
?>
<div class="paises index content">
    <?= $this->Html->link(__('Nuevo País'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Paises') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= __('Id') ?></th>
                    <th><?= __('Nombre') ?></th>
                    <th><?= __('Código ISO') ?></th>
                    <th><?= __('Población') ?></th>
                    <th class="actions"><?= __('Acciones') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paises as $pais): ?>
                <tr>
                    <td><?= h($pais->id) ?></td>
                    <td><?= h($pais->nombre) ?></td>
                    <td><?= h($pais->codigo_iso) ?></td>
                    <td><?= h($pais->poblacion) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $pais->id]) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $pais->id]) ?>
                        <?= $this->Form->postLink(__('Eliminar'), ['action' => 'delete', $pais->id], ['confirm' => __('¿Seguro que deseas eliminar # {0}?', $pais->id)]) ?>
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
