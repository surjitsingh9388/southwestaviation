<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

class CustomerAircraftWOOptionPricingInfoesTable extends Table
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
        $this->setTable('customer_aircraft_wo_option_pricing_infoes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');        

        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id');
        
        $validator
            ->integer('wo_item_id')
            ->notEmptyString('wo_item_id');
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        /*if (!empty($entity->osr_date_due)) {
            $entity->osr_date_due = $service->dateFormatBeforeSave($entity->osr_date_due);
        }*/
        
        if(!empty($entity->id)){
            $CustomerAircraftWOOptionPricingInfoesModel =  FactoryLocator::get('Table')->get('CustomerAircraftWOOptionPricingInfoes');
            $aircraftwoitems = $CustomerAircraftWOOptionPricingInfoesModel->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->work_order_id = $entity->work_order_id;
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Item Price Info was updated.';
            
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
            
            if($entity->labor_discount != $aircraftwoitems->labor_discount){
                $description .= 'Labor Discount was changed from "'.$aircraftwoitems->labor_discount.'" to "'.$entity->labor_discount.'".<br/>';
            }
            if($entity->labor_discount_percentage != $aircraftwoitems->labor_discount_percentage){
                $description .= 'Labor % Discount was changed from "'.$aircraftwoitems->labor_discount_percentage.'" to "'.$entity->labor_discount_percentage.'".<br/>';
            }
            if($entity->flat_discount_amount != $aircraftwoitems->flat_discount_amount){
                $description .= 'Flat Discount Amount was changed from "'.$aircraftwoitems->flat_discount_amount.'" to "'.$entity->flat_discount_amount.'".<br/>';
            }
            if($entity->parts_user_dealer_price != $aircraftwoitems->parts_user_dealer_price){
                $description .= 'Use Dealer Prices was changed from "'.$aircraftwoitems->parts_user_dealer_price.'" to "'.$entity->parts_user_dealer_price.'".<br/>';
            }
            if($entity->parts_discount != $aircraftwoitems->parts_discount){
                $description .= 'Part Discount was changed from "'.$aircraftwoitems->parts_discount.'" to "'.$entity->parts_discount.'".<br/>';
            }
            if($entity->parts_discount_percentage != $aircraftwoitems->parts_discount_percentage){
                $description .= 'Part % Discount was changed from "'.$aircraftwoitems->parts_discount_percentage.'" to "'.$entity->parts_discount_percentage.'".<br/>';
            }
            if($entity->parts_flat_discount_amount != $aircraftwoitems->parts_flat_discount_amount){
                $description .= 'Part Flat Discount Amount was changed from "'.$aircraftwoitems->parts_flat_discount_amount.'" to "'.$entity->parts_flat_discount_amount.'".<br/>';
            }
            if($entity->discount_over_cost != $aircraftwoitems->discount_over_cost){
                $description .= 'Discount is % Over Cost was changed from "'.$aircraftwoitems->discount_over_cost.'" to "'.$entity->discount_over_cost.'".<br/>';
            }
            if($entity->donot_use_markup_formula != $aircraftwoitems->donot_use_markup_formula){
                $description .= 'Do not use mark-up formula was changed from "'.$aircraftwoitems->donot_use_markup_formula.'" to "'.$entity->donot_use_markup_formula.'".<br/>';
            }
            if($entity->donot_charge_shipping != $aircraftwoitems->donot_charge_shipping){
                $description .= 'Do not charge shipping was changed from "'.$aircraftwoitems->donot_charge_shipping.'" to "'.$entity->donot_charge_shipping.'".<br/>';
            }
            if($entity->is_internal_bill_at_cost != $aircraftwoitems->is_internal_bill_at_cost){
                $description .= 'Is Internal - Ball at Costs was changed from "'.$aircraftwoitems->is_internal_bill_at_cost.'" to "'.$entity->is_internal_bill_at_cost.'".<br/>';
            }
            
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
                
                $AircraftWOItemHistories->user_id = $entity->updated_by;
                $AircraftWOItemHistories->description = $description;
                $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
            }
        }

        return true;
    }
    
}
