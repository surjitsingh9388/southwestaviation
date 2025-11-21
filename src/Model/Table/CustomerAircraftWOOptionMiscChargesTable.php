<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

class CustomerAircraftWOOptionMiscChargesTable extends Table
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
        $this->setTable('customer_aircraft_wo_option_misc_charges');
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
            $CustomerAircraftWOOptionMiscChargesModel =  FactoryLocator::get('Table')->get('CustomerAircraftWOOptionMiscCharges');
            $aircraftwoitems = $CustomerAircraftWOOptionMiscChargesModel->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->work_order_id = $entity->work_order_id;
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Item Misc Charges was updated.';
            
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
            
            if($entity->epa_charge != $aircraftwoitems->epa_charge){
                $description .= 'EPA Charge was changed from "'.$aircraftwoitems->epa_charge.'" to "'.$entity->epa_charge.'".<br/>';
            }
            if($entity->epa_charge_twin != $aircraftwoitems->epa_charge_twin){
                $description .= 'EPA Charge Twin was changed from "'.$aircraftwoitems->epa_charge_twin.'" to "'.$entity->epa_charge_twin.'".<br/>';
            }
            if($entity->epa_charge_amount != $aircraftwoitems->epa_charge_amount){
                $description .= 'EPA Charge Twin Amount was changed from "'.$aircraftwoitems->epa_charge_amount.'" to "'.$entity->epa_charge_amount.'".<br/>';
            }
            if($entity->oil_analysis != $aircraftwoitems->oil_analysis){
                $description .= 'Oil Analysis was changed from "'.$aircraftwoitems->oil_analysis.'" to "'.$entity->oil_analysis.'".<br/>';
            }
            if($entity->oil_analysis_twin != $aircraftwoitems->oil_analysis_twin){
                $description .= 'Oil Analysis Twin was changed from "'.$aircraftwoitems->oil_analysis_twin.'" to "'.$entity->oil_analysis_twin.'".<br/>';
            }
            if($entity->oil_analysis_amount != $aircraftwoitems->oil_analysis_amount){
                $description .= 'Oil Analysis Twin Amount was changed from "'.$aircraftwoitems->oil_analysis_amount.'" to "'.$entity->oil_analysis_amount.'".<br/>';
            }
            if($entity->tire_disposal != $aircraftwoitems->tire_disposal){
                $description .= 'Tire Disposal was changed from "'.$aircraftwoitems->tire_disposal.'" to "'.$entity->tire_disposal.'".<br/>';
            }
            if($entity->tire != $aircraftwoitems->tire){
                $description .= 'Tires was changed from "'.$aircraftwoitems->tire.'" to "'.$entity->tire.'".<br/>';
            }
            if($entity->amount_per_tire != $aircraftwoitems->amount_per_tire){
                $description .= 'Amount Per Tire was changed from "'.$aircraftwoitems->amount_per_tire.'" to "'.$entity->amount_per_tire.'".<br/>';
            }
            if($entity->mis_charge != $aircraftwoitems->mis_charge){
                $description .= 'Misc. Charges was changed from "'.$aircraftwoitems->mis_charge.'" to "'.$entity->mis_charge.'".<br/>';
            }
            if($entity->mis_charge_amount != $aircraftwoitems->mis_charge_amount){
                $description .= 'Misc. Charges Amount was changed from "'.$aircraftwoitems->mis_charge_amount.'" to "'.$entity->mis_charge_amount.'".<br/>';
            }
            if($entity->pilot_services != $aircraftwoitems->pilot_services){
                $description .= 'Pilot Services was changed from "'.$aircraftwoitems->pilot_services.'" to "'.$entity->pilot_services.'".<br/>';
            }
            if($entity->pilot_services_amount != $aircraftwoitems->pilot_services_amount){
                $description .= 'Pilot Services Amount was changed from "'.$aircraftwoitems->pilot_services_amount.'" to "'.$entity->pilot_services_amount.'".<br/>';
            }
            if($entity->tax_credit != $aircraftwoitems->tax_credit){
                $description .= 'Tax Credit was changed from "'.$aircraftwoitems->tax_credit.'" to "'.$entity->tax_credit.'".<br/>';
            }
            if($entity->tax_credit_amount != $aircraftwoitems->tax_credit_amount){
                $description .= 'Tax Credit Amount was changed from "'.$aircraftwoitems->tax_credit_amount.'" to "'.$entity->tax_credit_amount.'".<br/>';
            }
            if($entity->shop_supplies != $aircraftwoitems->shop_supplies){
                $description .= 'Shop Supplies was changed from "'.$aircraftwoitems->shop_supplies.'" to "'.$entity->shop_supplies.'".<br/>';
            }
            if($entity->shop_supplies_method != $aircraftwoitems->shop_supplies_method){
                $description .= 'Shop Supplies Method was changed from "'.$aircraftwoitems->shop_supplies_method.'" to "'.$entity->shop_supplies_method.'".<br/>';
            }
            if($entity->shop_supplies_amount != $aircraftwoitems->shop_supplies_amount){
                $description .= 'Shop Supplies Amount was changed from "'.$aircraftwoitems->shop_supplies_amount.'" to "'.$entity->shop_supplies_amount.'".<br/>';
            }
            if($entity->percentage_of_labor != $aircraftwoitems->percentage_of_labor){
                $description .= 'Percentage of Labor was changed from "'.$aircraftwoitems->percentage_of_labor.'" to "'.$entity->percentage_of_labor.'".<br/>';
            }
            if($entity->break_off_amount != $aircraftwoitems->break_off_amount){
                $description .= 'Break-off Amount was changed from "'.$aircraftwoitems->break_off_amount.'" to "'.$entity->break_off_amount.'".<br/>';
            }
            if($entity->break_off_percentage != $aircraftwoitems->break_off_percentage){
                $description .= 'Break-off was changed from "'.$aircraftwoitems->break_off_percentage.'" to "'.$entity->break_off_percentage.'".<br/>';
            }
            if($entity->above_break_off_percentage != $aircraftwoitems->above_break_off_percentage){
                $description .= 'Above Break-off was changed from "'.$aircraftwoitems->above_break_off_percentage.'" to "'.$entity->above_break_off_percentage.'".<br/>';
            }
            if($entity->misc_charge_description_for_invoice != $aircraftwoitems->misc_charge_description_for_invoice){
                $description .= 'Misc. Charges Description for Invoice was changed from "'.$aircraftwoitems->misc_charge_description_for_invoice.'" to "'.$entity->misc_charge_description_for_invoice.'".<br/>';
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
