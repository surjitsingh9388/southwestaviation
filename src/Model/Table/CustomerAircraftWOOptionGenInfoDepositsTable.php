<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class CustomerAircraftWOOptionGenInfoDepositsTable extends Table
{
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
    */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('customer_aircraft_wo_option_geninfo_deposits');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->Planes = TableRegistry::get('Planes');
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->deposit_date)) {
            $entity->deposit_date = $this->Planes->dateFormatBeforeSave($entity->deposit_date);
        }
    }
}