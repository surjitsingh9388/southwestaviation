<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class CustomerAircraftMaintenanceAppliancesTable extends Table
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

        $this->setTable('customer_aircraft_maintenance_appliances');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->Planes = TableRegistry::get('Planes');
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->a_last_update)) {
            $entity->a_last_update = $this->Planes->dateFormatBeforeSave($entity->a_last_update);
        }
        if(!empty($entity->a_due_date)) {
            $entity->a_due_date = $this->Planes->dateFormatBeforeSave($entity->a_due_date);
        }
        
    }
}