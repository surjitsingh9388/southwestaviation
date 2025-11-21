<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

class CustomerAircraftWOOptionExtraTaxesTable extends Table
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

        $this->setTable('customer_aircraft_wo_option_extra_taxes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();
        
        /*if(!empty($entity->date_due)) {
            $entity->date_due = $service->dateFormatBeforeSave($entity->date_due);
        }*/

        if(!empty($entity->id)){
            $CustomerAircraftWOOptionExtraTaxesModel =  FactoryLocator::get('Table')->get('CustomerAircraftWOOptionExtraTaxes');
            $aircraftwoitems = $CustomerAircraftWOOptionExtraTaxesModel->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->work_order_id = $entity->work_order_id;
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Item Extra Tax Info was updated.';
            
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
            
            if($entity->taxable != $aircraftwoitems->taxable){
                $description .= 'Taxable was changed from "'.$aircraftwoitems->taxable.'" to "'.$entity->taxable.'".<br/>';
            }
            if($entity->tax_method != $aircraftwoitems->tax_method){
                $description .= 'Tax Method was changed from "'.$aircraftwoitems->tax_method.'" to "'.$entity->tax_method.'".<br/>';
            }
            if($entity->tax_rate != $aircraftwoitems->tax_rate){
                $description .= 'Tax Rate was changed from "'.$aircraftwoitems->tax_rate.'" to "'.$entity->tax_rate.'".<br/>';
            }
            if($entity->tax_item_labor != $aircraftwoitems->tax_item_labor){
                $description .= 'Tax Item Labor was changed from "'.$aircraftwoitems->tax_item_labor.'" to "'.$entity->tax_item_labor.'".<br/>';
            }
            if($entity->tax_item_oil_analysis != $aircraftwoitems->tax_item_oil_analysis){
                $description .= 'Tax Item Oil Analysis was changed from "'.$aircraftwoitems->tax_item_oil_analysis.'" to "'.$entity->tax_item_oil_analysis.'".<br/>';
            }
            if($entity->tax_item_parts != $aircraftwoitems->tax_item_parts){
                $description .= 'Tax Item Parts was changed from "'.$aircraftwoitems->tax_item_parts.'" to "'.$entity->tax_item_parts.'".<br/>';
            }
            if($entity->tax_item_pilot_services != $aircraftwoitems->tax_item_pilot_services){
                $description .= 'Tax Item Pilot Services was changed from "'.$aircraftwoitems->tax_item_pilot_services.'" to "'.$entity->tax_item_pilot_services.'".<br/>';
            }
            if($entity->tax_item_labor_osr != $aircraftwoitems->tax_item_labor_osr){
                $description .= 'Tax Item Labor (OSR) was changed from "'.$aircraftwoitems->tax_item_labor_osr.'" to "'.$entity->tax_item_labor_osr.'".<br/>';
            }
            if($entity->tax_item_misc_charges != $aircraftwoitems->tax_item_misc_charges){
                $description .= 'Tax Item Misc. Charges was changed from "'.$aircraftwoitems->tax_item_misc_charges.'" to "'.$entity->tax_item_misc_charges.'".<br/>';
            }
            if($entity->tax_item_parts_osr != $aircraftwoitems->tax_item_parts_osr){
                $description .= 'Tax Item Part (OSR) was changed from "'.$aircraftwoitems->tax_item_parts_osr.'" to "'.$entity->tax_item_parts_osr.'".<br/>';
            }
            if($entity->tax_item_shop_supplies != $aircraftwoitems->tax_item_shop_supplies){
                $description .= 'Tax Item Shop Supplies was changed from "'.$aircraftwoitems->tax_item_shop_supplies.'" to "'.$entity->tax_item_shop_supplies.'".<br/>';
            }
            if($entity->tax_item_ship_out != $aircraftwoitems->tax_item_ship_out){
                $description .= 'Tax Item Ship Out was changed from "'.$aircraftwoitems->tax_item_ship_out.'" to "'.$entity->tax_item_ship_out.'".<br/>';
            }
            if($entity->tax_item_fuel != $aircraftwoitems->tax_item_fuel){
                $description .= 'Tax Item Fuel was changed from "'.$aircraftwoitems->tax_item_fuel.'" to "'.$entity->tax_item_fuel.'".<br/>';
            }
            if($entity->tax_item_shipin != $aircraftwoitems->tax_item_shipin){
                $description .= 'Tax Item Ship In was changed from "'.$aircraftwoitems->tax_item_shipin.'" to "'.$entity->tax_item_shipin.'".<br/>';
            }
            if($entity->tax_item_tire_disposal != $aircraftwoitems->tax_item_tire_disposal){
                $description .= 'Tax Item  was changed from "'.$aircraftwoitems->tax_item_tire_disposal.'" to "'.$entity->tax_item_tire_disposal.'".<br/>';
            }
            if($entity->tax_item_ship_out_osr != $aircraftwoitems->tax_item_ship_out_osr){
                $description .= 'Tax Item  was changed from "'.$aircraftwoitems->tax_item_ship_out_osr.'" to "'.$entity->tax_item_ship_out_osr.'".<br/>';
            }
            if($entity->tax_item_cores != $aircraftwoitems->tax_item_cores){
                $description .= 'Tax Item  was changed from "'.$aircraftwoitems->tax_item_cores.'" to "'.$entity->tax_item_cores.'".<br/>';
            }
            if($entity->tax_item_shipin_osr != $aircraftwoitems->tax_item_shipin_osr){
                $description .= 'Tax Item  was changed from "'.$aircraftwoitems->tax_item_shipin_osr.'" to "'.$entity->tax_item_shipin_osr.'".<br/>';
            }
            if($entity->tax_item_cores_credit != $aircraftwoitems->tax_item_cores_credit){
                $description .= 'Tax Item  was changed from "'.$aircraftwoitems->tax_item_cores_credit.'" to "'.$entity->tax_item_cores_credit.'".<br/>';
            }
            if($entity->tax_item_epa_charges != $aircraftwoitems->tax_item_epa_charges){
                $description .= 'Tax Item  was changed from "'.$aircraftwoitems->tax_item_epa_charges.'" to "'.$entity->tax_item_epa_charges.'".<br/>';
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