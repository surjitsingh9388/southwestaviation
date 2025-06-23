<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

class CustomerAircraftWOLogBookValuePropsTable extends Table
{
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config):void
    {
        parent::initialize($config);

        $this->setTable('customer_aircraft_wo_logbook_value_props');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();
        
        if(!empty($entity->p_oh_date_l)) {
            $entity->p_oh_date_l = $service->dateFormatBeforeSave($entity->p_oh_date_l);
        }
        if(!empty($entity->p_oh_date_r)) {
            $entity->p_oh_date_r = $service->dateFormatBeforeSave($entity->p_oh_date_r);
        }
    }
}