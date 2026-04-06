<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @method \App\Model\Entity\Perfil newEmptyEntity()
 * @method \App\Model\Entity\Perfil newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Perfil get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PerfilesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('perfiles');
        $this->setDisplayField('idioma');
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
            ->scalar('idioma')
            ->maxLength('idioma', 10)
            ->requirePresence('idioma', 'create')
            ->notEmptyString('idioma');

        $validator
            ->scalar('biografia')
            ->allowEmptyString('biografia');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['user_id'], __('Este usuario ya tiene un perfil.')));

        return $rules;
    }
}
