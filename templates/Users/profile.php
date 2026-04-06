<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array<string, string> $idiomasPerfil
 */
?>
<div class="row">
    <div class="column column-80 column-offset-10">
        <div class="users form content">
            <h3><?= __('Mi perfil') ?></h3>
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Datos personales') ?></legend>
                <?php
                    echo $this->Form->control('nombre', ['label' => __('Nombre')]);
                    echo $this->Form->control('apellido', ['label' => __('Apellido')]);
                    echo $this->Form->control('correo', ['label' => __('Correo')]);
                    echo $this->Form->control('password', [
                        'type' => 'password',
                        'label' => __('Nueva contraseña (dejar vacío para no cambiar)'),
                        'autocomplete' => 'new-password',
                        'required' => false,
                        'value' => '',
                    ]);
                ?>
            </fieldset>
            <fieldset>
                <legend><?= __('Preferencias') ?></legend>
                <?php
                    echo $this->Form->control('perfil.idioma', [
                        'type' => 'select',
                        'options' => $idiomasPerfil,
                        'label' => __('Idioma de la interfaz'),
                    ]);
                    echo $this->Form->control('perfil.biografia', [
                        'type' => 'textarea',
                        'label' => __('Biografía'),
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Guardar perfil')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
