<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pais $pais
 */
?>
<div class="paises form content">
    <?= $this->Html->link(__('Volver al listado'), ['action' => 'index'], ['class' => 'button float-right']) ?>
    <h3><?= __('Editar País') ?></h3>
    <?= $this->Form->create($pais) ?>
    <fieldset>
        <legend><?= __('Datos del país') ?></legend>
        <?php
            echo $this->Form->control('nombre');
            echo $this->Form->control('codigo_iso', ['label' => __('Código ISO')]);
            echo $this->Form->control('poblacion', ['label' => __('Población')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Actualizar')) ?>
    <?= $this->Form->end() ?>
</div>
