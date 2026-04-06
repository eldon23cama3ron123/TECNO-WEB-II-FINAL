<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pais Entity
 *
 * @property int $id
 * @property string $nombre
 * @property string $codigo_iso
 * @property int|null $poblacion
 */
class Pais extends Entity
{
    protected array $_accessible = [
        'nombre' => true,
        'codigo_iso' => true,
        'poblacion' => true,
    ];
}
