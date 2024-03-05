<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class CustomerAircraftMaintenanceEnginesTable extends Table
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

        $this->setTable('customer_aircraft_maintenance_engines');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->Planes = TableRegistry::get('Planes');
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->oh_date)) {
            $entity->oh_date = $this->Planes->dateFormatBeforeSave($entity->oh_date);
        }
        if(!empty($entity->oh_date_r)) {
            $entity->oh_date_r = $this->Planes->dateFormatBeforeSave($entity->oh_date_r);
        }
        
    }
}