<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;

class UserTimeClocksTable extends Table
{
    //use SoftDeleteTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('user_time_clocks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->Planes = TableRegistry::get('Planes');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        return $validator;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->in_time)) {
            $entity->in_time = $this->Planes->dateFormatBeforeSave($entity->in_time);
        }

        if(!empty($entity->out_time)) {
            $entity->out_time = $this->Planes->dateFormatBeforeSave($entity->out_time);
        }

        return true;
    }
    
}
