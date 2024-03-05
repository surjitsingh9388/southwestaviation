<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class CustomerRepairOrderRatesTable extends Table
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

        $this->setTable('customer_repair_order_rates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->Planes = TableRegistry::get('Planes');
    }
    
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        /*if(!empty($entity->invoice_date_created)) {
            $entity->invoice_date_created = $this->Planes->dateFormatBeforeSave($entity->invoice_date_created);
        }*/
    }
}