<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\I18n;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string|null $descripcion_es
 * @property string|null $descripcion_en
 * @property string $estado
 * @property \Cake\I18n\DateTime|null $fecha_limite
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property \App\Model\Entity\User $user
 */
class Tarea extends Entity
{
    /**
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'user_id' => true,
        'titulo' => true,
        'descripcion_es' => true,
        'descripcion_en' => true,
        'estado' => true,
        'fecha_limite' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];

    /**
     * Descripción según el idioma activo, con respaldo al otro idioma.
     */
    public function descripcionParaLocale(?string $locale = null): string
    {
        $locale = $locale ?? I18n::getLocale();
        if (str_starts_with((string)$locale, 'en')) {
            $t = $this->descripcion_en;

            return $t !== null && $t !== '' ? $t : (string)($this->descripcion_es ?? '');
        }
        $t = $this->descripcion_es;

        return $t !== null && $t !== '' ? $t : (string)($this->descripcion_en ?? '');
    }
}
