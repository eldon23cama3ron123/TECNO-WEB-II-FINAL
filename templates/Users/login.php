<?php
/**
 * @var \App\View\AppView $this
 * @var array<string, string>|null $localesInterfaz
 */
?>
<div class="row">
    <div class="column column-50 column-offset-25">
        <div class="users form content">
            <h3><?= __('Iniciar sesión') ?></h3>
            <?php if (!empty($localesInterfaz)): ?>
            <p class="locale-switch" style="font-size: 0.9rem; margin-bottom: 1rem;">
                <?= __('Idioma') ?>:
                <?php
                $parts = [];
                foreach ($localesInterfaz as $code => $label) {
                    $parts[] = $this->Html->link(h($label), ['action' => 'login', '?' => ['locale' => $code]], ['class' => 'locale-link']);
                }
                echo implode(' · ', $parts);
                ?>
            </p>
            <?php endif; ?>
            <?= $this->Form->create(null) ?>
            <fieldset>
                <?php
                    echo $this->Form->control('correo', [
                        'label' => __('Correo'),
                        'required' => true,
                        'autocomplete' => 'username',
                    ]);
                    echo $this->Form->control('password', [
                        'label' => __('Contraseña'),
                        'required' => true,
                        'autocomplete' => 'current-password',
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Entrar')) ?>
            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

