<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;

class CustomerAircraftWOOptionWarrantyInfoesTable extends Table
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
        $this->setTable('customer_aircraft_wo_option_warranty_infoes');
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

        if(!empty($entity->id)){
            $CustomerAircraftWOOptionWarrantyInfoesModel =  FactoryLocator::get('Table')->get('CustomerAircraftWOOptionWarrantyInfoes');
            $aircraftwoitems = $CustomerAircraftWOOptionWarrantyInfoesModel->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->work_order_id = $entity->work_order_id;
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Item Warranty Info was updated.';
            
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
            
            if($entity->company_id != $aircraftwoitems->company_id){
                $description .= 'Company was changed from "'.$aircraftwoitems->company_id.'" to "'.$entity->company_id.'".<br/>';
            }
            if($entity->contact != $aircraftwoitems->contact){
                $description .= 'Contact was changed from "'.$aircraftwoitems->contact.'" to "'.$entity->contact.'".<br/>';
            }
            if($entity->currency != $aircraftwoitems->currency){
                $description .= 'Currency was changed from "'.$aircraftwoitems->currency.'" to "'.$entity->currency.'".<br/>';
            }
            if($entity->tax_method != $aircraftwoitems->tax_method){
                $description .= 'Tax Method was changed from "'.$aircraftwoitems->tax_method.'" to "'.$entity->tax_method.'".<br/>';
            }
            if($entity->tax_rate1 != $aircraftwoitems->tax_rate1){
                $description .= 'Tax Rate was changed from "'.$aircraftwoitems->tax_rate1.'" to "'.$entity->tax_rate1.'".<br/>';
            }
            if($entity->tax_rate2 != $aircraftwoitems->tax_rate2){
                $description .= 'Tax Rate was changed from "'.$aircraftwoitems->tax_rate2.'" to "'.$entity->tax_rate2.'".<br/>';
            }
            if($entity->tax_rate3 != $aircraftwoitems->tax_rate3){
                $description .= 'Tax Rate was changed from "'.$aircraftwoitems->tax_rate3.'" to "'.$entity->tax_rate3.'".<br/>';
            }
            if($entity->part_pricing_options != $aircraftwoitems->part_pricing_options){
                $description .= 'Part Pricing Options was changed from "'.$aircraftwoitems->part_pricing_options.'" to "'.$entity->part_pricing_options.'".<br/>';
            }
            if($entity->over_cost != $aircraftwoitems->over_cost){
                $description .= '% Over Cost was changed from "'.$aircraftwoitems->over_cost.'" to "'.$entity->over_cost.'".<br/>';
            }
            if($entity->use_labor_rate != $aircraftwoitems->use_labor_rate){
                $description .= 'Use Labor Rate was changed from "'.$aircraftwoitems->use_labor_rate.'" to "'.$entity->use_labor_rate.'".<br/>';
            }
            if($entity->pay_labor != $aircraftwoitems->pay_labor){
                $description .= 'Pay Labor was changed from "'.$aircraftwoitems->pay_labor.'" to "'.$entity->pay_labor.'".<br/>';
            }
            if($entity->pay_parts != $aircraftwoitems->pay_parts){
                $description .= 'Pay Parts was changed from "'.$aircraftwoitems->pay_parts.'" to "'.$entity->pay_parts.'".<br/>';
            }
            if($entity->pay_shipping != $aircraftwoitems->pay_shipping){
                $description .= 'Pay Shipping was changed from "'.$aircraftwoitems->pay_shipping.'" to "'.$entity->pay_shipping.'".<br/>';
            }
            if($entity->taxable != $aircraftwoitems->taxable){
                $description .= 'Taxable was changed from "'.$aircraftwoitems->taxable.'" to "'.$entity->taxable.'".<br/>';
            }
            if($entity->customer_pays_warranty_tax != $aircraftwoitems->customer_pays_warranty_tax){
                $description .= 'Customer Pays Warranty Tax  was changed from "'.$aircraftwoitems->customer_pays_warranty_tax.'" to "'.$entity->customer_pays_warranty_tax.'".<br/>';
            }
            if($entity->specified_labor_rate != $aircraftwoitems->specified_labor_rate){
                $description .= 'Specified Labor Rate was changed from "'.$aircraftwoitems->specified_labor_rate.'" to "'.$entity->specified_labor_rate.'".<br/>';
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
