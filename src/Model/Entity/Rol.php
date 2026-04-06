<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rol Entity
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 * @property \App\Model\Entity\User[] $users
 */
class Rol extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     */
    protected array $_accessible = [
        'nombre' => true,
        'descripcion' => true,
        'created' => true,
        'modified' => true,
        'users' => true,
    ];
}
