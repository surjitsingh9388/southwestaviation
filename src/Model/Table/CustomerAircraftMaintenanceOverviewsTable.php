<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class CustomerAircraftMaintenanceOverviewsTable extends Table
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

        $this->setTable('customer_aircraft_maintenance_overviews');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        $this->Planes = TableRegistry::get('Planes');
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->next_annual)) {
            $entity->next_annual = $this->Planes->dateFormatBeforeSave($entity->next_annual);
        }
        if(!empty($entity->next_elt_date)) {
            $entity->next_elt_date = $this->Planes->dateFormatBeforeSave($entity->next_elt_date);
        }
        if(!empty($entity->next_corrosion)) {
            $entity->next_corrosion = $this->Planes->dateFormatBeforeSave($entity->next_corrosion);
        }
        if(!empty($entity->next_o2_bottle)) {
            $entity->next_o2_bottle = $this->Planes->dateFormatBeforeSave($entity->next_o2_bottle);
        }
        if(!empty($entity->next_far_91_411)) {
            $entity->next_far_91_411 = $this->Planes->dateFormatBeforeSave($entity->next_far_91_411);
        }
        if(!empty($entity->reg_expires)) {
            $entity->reg_expires = $this->Planes->dateFormatBeforeSave($entity->reg_expires);
        }
        if(!empty($entity->next_far_91_413)) {
            $entity->next_far_91_413 = $this->Planes->dateFormatBeforeSave($entity->next_far_91_413);
        }
        if(!empty($entity->warranty_date)) {
            $entity->warranty_date = $this->Planes->dateFormatBeforeSave($entity->warranty_date);
        }
        if(!empty($entity->ac_battery_date)) {
            $entity->ac_battery_date = $this->Planes->dateFormatBeforeSave($entity->ac_battery_date);
        }
        if(!empty($entity->last_oil_change_date)) {
            $entity->last_oil_change_date = $this->Planes->dateFormatBeforeSave($entity->last_oil_change_date);
        }        
    }
}