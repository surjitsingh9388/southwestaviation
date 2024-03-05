<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;

class InventoryToolCertificationHistoriesTable extends Table
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
        $this->setTable('inventory_tool_certification_histories');
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

        if(!empty($entity->date_sent_out)) {
            $entity->date_sent_out = $this->Planes->dateFormatBeforeSave($entity->date_sent_out);
        }

        if(!empty($entity->date_received_back)) {
            $entity->date_received_back = $this->Planes->dateFormatBeforeSave($entity->date_received_back);
        }

        if(!empty($entity->date_of_calibration)) {
            $entity->date_of_calibration = $this->Planes->dateFormatBeforeSave($entity->date_of_calibration);
        }
        
        return true;
    }
}
