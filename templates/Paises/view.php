<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pais $pais
 */
?>
<div class="paises view content">
    <?= $this->Html->link(__('Editar'), ['action' => 'edit', $pais->id], ['class' => 'button float-right']) ?>
    <h3><?= __('País') ?></h3>
    <div class="table-responsive">
        <table>
            <tr>
                <th><?= __('Id') ?></th>
                <td><?= h($pais->id) ?></td>
            </tr>
            <tr>
                <th><?= __('Nombre') ?></th>
                <td><?= h($pais->nombre) ?></td>
            </tr>
            <tr>
                <th><?= __('Código ISO') ?></th>
                <td><?= h($pais->codigo_iso) ?></td>
            </tr>
            <tr>
                <th><?= __('Población') ?></th>
                <td><?= h($pais->poblacion) ?></td>
            </tr>
            <tr>
                <th><?= __('Creado') ?></th>
                <td><?= h($pais->created) ?></td>
            </tr>
            <tr>
                <th><?= __('Modificado') ?></th>
                <td><?= h($pais->modified) ?></td>
            </tr>
        </table>
    </div>
    <div class="related">
        <?= $this->Html->link(__('Volver'), ['action' => 'index'], ['class' => 'button']) ?>
        <?= $this->Form->postLink(__('Eliminar'), ['action' => 'delete', $pais->id], ['class' => 'button', 'confirm' => __('¿Seguro que deseas eliminar # {0}?', $pais->id)]) ?>
    </div>
</div>
