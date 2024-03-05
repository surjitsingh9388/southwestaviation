<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;

class InventoryToolsTable extends Table
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
        $this->setTable('inventory_tools');
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

        $validator
            ->scalar('tool_name')
            ->maxLength('tool_name', 255)
            ->requirePresence('tool_name', 'create')
            ->notEmpty('tool_name')
            ->add('tool_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        /*$validator
            ->integer('modified')
            ->requirePresence('modified', 'create')
            ->notEmpty('modified');*/

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /*public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['part_number']));

        return $rules;
    }*/

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->calibration_date)) {
            $entity->calibration_date = $this->Planes->dateFormatBeforeSave($entity->calibration_date);
        }

        if(!empty($entity->due_date)) {
            $entity->due_date = $this->Planes->dateFormatBeforeSave($entity->due_date);
        }

        if(!empty($entity->date_labeled)) {
            $entity->date_labeled = $this->Planes->dateFormatBeforeSave($entity->date_labeled);
        }

        if(!empty($entity->date_purchased)) {
            $entity->date_purchased = $this->Planes->dateFormatBeforeSave($entity->date_purchased);
        }
        
        return true;
    }
}
