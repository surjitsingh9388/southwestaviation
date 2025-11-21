<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

class CustomerAircraftWOOptionGeneralInfoesTable extends Table
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

        $this->setTable('customer_aircraft_wo_option_general_infoes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp');
        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();
        
        if(!empty($entity->date_due)) {
            $entity->date_due = $service->dateFormatBeforeSave($entity->date_due);
        }

        if(!empty($entity->id)){
            $CustomerAircraftWOOptionGeneralInfoesModel =  FactoryLocator::get('Table')->get('CustomerAircraftWOOptionGeneralInfoes');
            $aircraftwoitems = $CustomerAircraftWOOptionGeneralInfoesModel->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->work_order_id = $entity->work_order_id;
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Item General Info was updated.';
            
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
            
            if($entity->customer_po != $aircraftwoitems->customer_po){
                $description .= 'Customer P/O was changed from "'.$aircraftwoitems->customer_po.'" to "'.$entity->customer_po.'".<br/>';
            }
            if($entity->service_quote != $aircraftwoitems->service_quote){
                $description .= 'Service Quote was changed from "'.$aircraftwoitems->service_quote.'" to "'.$entity->service_quote.'".<br/>';
            }
            if($entity->terms != $aircraftwoitems->terms){
                $description .= 'Terms was changed from "'.$aircraftwoitems->terms.'" to "'.$entity->terms.'".<br/>';
            }
            if($entity->disclaimer_for_ro != $aircraftwoitems->disclaimer_for_ro){
                $description .= 'Disclaimer for R/O was changed from "'.$aircraftwoitems->disclaimer_for_ro.'" to "'.$entity->disclaimer_for_ro.'".<br/>';
            }
            if($entity->min_hour_worked_per_item != $aircraftwoitems->min_hour_worked_per_item){
                $description .= 'Min. Hours Worked Per Item was changed from "'.$aircraftwoitems->min_hour_worked_per_item.'" to "'.$entity->min_hour_worked_per_item.'".<br/>';
            }
            if($entity->add_hrs_inspection != $aircraftwoitems->add_hrs_inspection){
                $description .= 'Overtime Hrs. was changed from "'.$aircraftwoitems->add_hrs_inspection.'" to "'.$entity->add_hrs_inspection.'".<br/>';
            }
            if($entity->overtime_hrs != $aircraftwoitems->overtime_hrs){
                $description .= 'Add Hrs. Inspection was changed from "'.$aircraftwoitems->overtime_hrs.'" to "'.$entity->overtime_hrs.'".<br/>';
            }
            if($entity->date_due != $aircraftwoitems->date_due){
                $description .= 'Date Due was changed from "'.$aircraftwoitems->date_due.'" to "'.$entity->date_due.'".<br/>';
            }
            if($entity->lead_technician != $aircraftwoitems->lead_technician){
                $description .= 'Lead Technician was changed from "'.$aircraftwoitems->lead_technician.'" to "'.$entity->lead_technician.'".<br/>';
            }
            if($entity->sales_reply != $aircraftwoitems->sales_reply){
                $description .= 'Status Notes was changed from "'.$aircraftwoitems->sales_reply.'" to "'.$entity->sales_reply.'".<br/>';
            }
            if($entity->status_notes != $aircraftwoitems->status_notes){
                $description .= 'Sales Rep. was changed from "'.$aircraftwoitems->status_notes.'" to "'.$entity->status_notes.'".<br/>';
            }
            if($entity->est_invoice != $aircraftwoitems->est_invoice){
                $description .= 'Est/Invoice - Show A/C Times Profile was changed from "'.$aircraftwoitems->est_invoice.'" to "'.$entity->est_invoice.'".<br/>';
            }
            if($entity->deposit_notes != $aircraftwoitems->deposit_notes){
                $description .= 'Notes was changed from "'.$aircraftwoitems->deposit_notes.'" to "'.$entity->deposit_notes.'".<br/>';
            }
            if($entity->accounting_invoice != $aircraftwoitems->accounting_invoice){
                $description .= 'Total Deposit Amount was changed from "'.$aircraftwoitems->accounting_invoice.'" to "'.$entity->accounting_invoice.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
                
                $AircraftWOItemHistories->user_id = $entity->updated_by;
                $AircraftWOItemHistories->description = $description;
                //$AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
            }
        }
    }
}