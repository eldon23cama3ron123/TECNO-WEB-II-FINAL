<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @method \App\Model\Entity\Tarea newEmptyEntity()
 * @method \App\Model\Entity\Tarea newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Tarea get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TareasTable extends Table
{
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_EN_PROGRESO = 'en_progreso';
    public const ESTADO_COMPLETADO = 'completado';

    /**
     * Etiquetas traducibles para formularios y filtros.
     *
     * @return array<string, string>
     */
    public function opcionesEstado(): array
    {
        return [
            self::ESTADO_PENDIENTE => __('Pendiente'),
            self::ESTADO_EN_PROGRESO => __('En progreso'),
            self::ESTADO_COMPLETADO => __('Completado'),
        ];
    }

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tareas');
        $this->setDisplayField('titulo');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 255)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->scalar('descripcion_es')
            ->allowEmptyString('descripcion_es');

        $validator
            ->scalar('descripcion_en')
            ->allowEmptyString('descripcion_en');

        $validator
            ->scalar('estado')
            ->maxLength('estado', 32)
            ->requirePresence('estado', 'create')
            ->notEmptyString('estado')
            ->inList('estado', [
                self::ESTADO_PENDIENTE,
                self::ESTADO_EN_PROGRESO,
                self::ESTADO_COMPLETADO,
            ]);

        $validator
            ->dateTime('fecha_limite')
            ->allowEmptyDateTime('fecha_limite');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
