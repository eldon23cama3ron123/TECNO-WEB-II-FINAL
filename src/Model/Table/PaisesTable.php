<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaisesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('paises');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 100)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->scalar('codigo_iso')
            ->maxLength('codigo_iso', 3)
            ->requirePresence('codigo_iso', 'create')
            ->notEmptyString('codigo_iso');

        $validator
            ->integer('poblacion')
            ->allowEmptyString('poblacion');

        return $validator;
    }
}
