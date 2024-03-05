<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class CustomerAircraftComplianceInspectionHistoriesTable extends Table
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

        $this->setTable('customer_aircraft_compliance_inspection_histories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->Planes = TableRegistry::get('Planes');
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->date_override)) {
            $entity->date_override = $this->Planes->dateFormatBeforeSave($entity->date_override);
        }
    }
}