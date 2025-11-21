<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;

class CustomerAircraftWOOptionBillingInfoesTable extends Table
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

        $this->setTable('customer_aircraft_wo_option_billing_infoes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }
    
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->id)){
            $CustomerAircraftWOOptionBillingInfoesModel =  FactoryLocator::get('Table')->get('CustomerAircraftWOOptionBillingInfoes');
            $aircraftwoitems = $CustomerAircraftWOOptionBillingInfoesModel->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->work_order_id = $entity->work_order_id;
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Item Billing Info was updated.';
            
            if(!empty($aircraftwoitems->updated_at)){
                $modified_from = str_replace('-', '/', $aircraftwoitems->updated_at);
                $modified_from = date("Y-m-d h:i A", strtotime($modified_from));
            }else{
                $modified_from = '';
            }

            if(!empty($entity->updated_at)){
                $modified_to = str_replace('-', '/', $entity->updated_at);
                $modified_to = date("Y-m-d h:i A", strtotime($modified_to));
            }else{
                $modified_to = '';
            }

            $aircraftItemStatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);

            $description = '';
            
            if($entity->billing_rate_method != $aircraftwoitems->billing_rate_method){
                $description .= 'Billing Rate Method was changed from "'.$aircraftwoitems->billing_rate_method.'" to "'.$entity->billing_rate_method.'".<br/>';
            }
            if($entity->min_hour_rate != $aircraftwoitems->min_hour_rate){
                $description .= 'Min Hour Rate was changed from "'.$aircraftwoitems->min_hour_rate.'" to "'.$entity->min_hour_rate.'".<br/>';
            }
            if($entity->aircraft_rate_method != $aircraftwoitems->aircraft_rate_method){
                $description .= 'Rate Method was changed from "'.$aircraftwoitems->aircraft_rate_method.'" to "'.$entity->aircraft_rate_method.'".<br/>';
            }
            if($entity->aircraft_rate_hour != $aircraftwoitems->aircraft_rate_hour){
                $description .= 'Rate / Hr. was changed from "'.$aircraftwoitems->aircraft_rate_hour.'" to "'.$entity->aircraft_rate_hour.'".<br/>';
            }
            if($entity->use_special_rate_hrs != $aircraftwoitems->use_special_rate_hrs){
                $description .= 'Use Special Rate / Hr. was changed from "'.$aircraftwoitems->use_special_rate_hrs.'" to "'.$entity->use_special_rate_hrs.'".<br/>';
            }
            if($entity->use_special_rate_amount != $aircraftwoitems->use_special_rate_amount){
                $description .= 'Use Special Rate Amount was changed from "'.$aircraftwoitems->use_special_rate_amount.'" to "'.$entity->use_special_rate_amount.'".<br/>';
            }
            if($entity->default_bill_to_customer != $aircraftwoitems->default_bill_to_customer){
                $description .= 'Default Bill-to-Customer was changed from "'.$aircraftwoitems->default_bill_to_customer.'" to "'.$entity->default_bill_to_customer.'".<br/>';
            }
            if($entity->accounting_type != $aircraftwoitems->accounting_type){
                $description .= 'Accounting Type was changed from "'.$aircraftwoitems->accounting_type.'" to "'.$entity->accounting_type.'".<br/>';
            }
            if($entity->customer_currency_format_override != $aircraftwoitems->customer_currency_format_override){
                $description .= 'Customer Currency Format Override was changed from "'.$aircraftwoitems->customer_currency_format_override.'" to "'.$entity->customer_currency_format_override.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
                
                $AircraftWOItemHistories->user_id = $entity->updated_by;
                $AircraftWOItemHistories->description = $description;
                //$AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
            }
        }

        return true;
    }
}