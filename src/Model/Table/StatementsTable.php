<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;

class StatementsTable extends Table
{
    
    public function initialize(array $config):void
    {
        parent::initialize($config);

        $this->setTable('statements');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('statement_name')
            ->maxLength('statement_name', 255)
            ->requirePresence('statement_name', 'create')
            ->notEmptyString('statement_name');

        return $validator;
    }

}