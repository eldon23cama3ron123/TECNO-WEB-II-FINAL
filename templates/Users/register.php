<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array<string, string> $idiomasRegistro
 * @var array<string, string>|null $localesInterfaz
 */
?>
<div class="row">
    <div class="column column-50 column-offset-25">
        <div class="users form content">
            <h3><?= __('Registrar usuario') ?></h3>
            <?php if (!empty($localesInterfaz)): ?>
            <p class="locale-switch" style="font-size: 0.9rem; margin-bottom: 1rem;">
                <?= __('Idioma') ?>:
                <?php
                $parts = [];
                foreach ($localesInterfaz as $code => $label) {
                    $parts[] = $this->Html->link(h($label), ['action' => 'register', '?' => ['locale' => $code]], ['class' => 'locale-link']);
                }
                echo implode(' · ', $parts);
                ?>
            </p>
            <?php endif; ?>

            <?= $this->Form->create($user) ?>
            <fieldset>
                <?php
                    echo $this->Form->control('nombre', [
                        'label' => __('Nombre'),
                        'required' => false,
                        'autocomplete' => 'given-name',
                    ]);
                    echo $this->Form->control('apellido', [
                        'label' => __('Apellido'),
                        'required' => false,
                        'autocomplete' => 'family-name',
                    ]);
                    echo $this->Form->control('correo', [
                        'label' => __('Correo'),
                        'required' => true,
                        'autocomplete' => 'username',
                    ]);
                    echo $this->Form->control('password', [
                        'label' => __('Contraseña'),
                        'required' => true,
                        'autocomplete' => 'new-password',
                    ]);
                    echo $this->Form->control('password_confirm', [
                        'label' => __('Confirmar contraseña'),
                        'required' => true,
                        'type' => 'password',
                        'autocomplete' => 'new-password',
                    ]);
                    echo $this->Form->control('idioma', [
                        'type' => 'select',
                        'options' => $idiomasRegistro,
                        'label' => __('Idioma de la interfaz'),
                        'default' => 'es_ES',
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Crear cuenta')) ?>
            <?= $this->Form->end() ?>

            <p>
                <?= $this->Html->link(__('Volver al login'), ['action' => 'login']) ?>
            </p>
        </div>
    </div>
</div>

