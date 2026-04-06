<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $apellido
 * @property string|null $correo
 * @property mixed $telefono
 * @property mixed $telf
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property string|null $password
 * @property string|null $language
 * @property int $role_id
 * @property \App\Model\Entity\Perfil|null $perfil
 * @property \App\Model\Entity\Rol|null $role
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'nombre' => true,
        'apellido' => true,
        'correo' => true,
        'telefono' => true,
        'created' => true,
        'modified' => true,
        'password' => true,
        'language' => true,
        'role_id' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(?string $password): ?string
    {
        if ($password === null) {
            return null;
        }

        $password = trim($password);
        if ($password === '') {
            return '';
        }

        // Avoid double-hashing if an already-hashed value is provided.
        if (str_starts_with($password, '$argon2') || preg_match('/^\$2[ayb]\$/', $password)) {
            return $password;
        }

        return password_hash($password, PASSWORD_DEFAULT);
    }
}
