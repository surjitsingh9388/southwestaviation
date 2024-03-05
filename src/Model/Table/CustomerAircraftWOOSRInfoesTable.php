<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * CustomerAircraftWOOSRInfoes Model
 *
 * @method \App\Model\Entity\Routes get($primaryKey, $options = [])
 * @method \App\Model\Entity\Routes newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Routes[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Routes|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Routes patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Routes[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Routes findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerAircraftWOOSRInfoesTable extends Table
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
        $this->setTable('customer_aircraft_wo_osr_infoes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');        

        $this->Planes = TableRegistry::get('Planes');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id');
        
        $validator
            ->integer('wo_item_id')
            ->notEmpty('wo_item_id');
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if (!empty($entity->osr_date_due)) {
            $entity->osr_date_due = $this->Planes->dateFormatBeforeSave($entity->osr_date_due);
        }

        if(!empty($entity->id)){
            $this->CustomerAircraftWOOSRInfoes = TableRegistry::get('CustomerAircraftWOOSRInfoes');
            $woitemosrinfo = $this->CustomerAircraftWOOSRInfoes->get($entity->id);
            $AircraftWOItemHistoriesModel = TableRegistry::get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEntity();
            
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Outside Repair was updated.';
            
            if(!empty($woitemosrinfo->updated_at)){
                $modified_from = str_replace('-', '/', $woitemosrinfo->updated_at);
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

            $outsideRepairInfoCond = unserialize(OUTSIDE_REPAIR_INFO_CONDITION);

            if($entity->osr_repair_done_by != $woitemosrinfo->osr_repair_done_by){
                $description .= 'Repair Done By was changed from "'.$woitemosrinfo->osr_repair_done_by.'" to "'.$entity->osr_repair_done_by.'".<br/>';
            }
            if($entity->osr_invoice_no != $woitemosrinfo->osr_invoice_no){
                $description .= 'Invoice No was changed from "'.$woitemosrinfo->osr_invoice_no.'" to "'.$entity->osr_invoice_no.'".<br/>';
            }
            $description = 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
            if($entity->osr_purchase_order_no != $woitemosrinfo->osr_purchase_order_no){
                $description .= 'Purchase Order No was changed from "'.$woitemosrinfo->osr_purchase_order_no.'" to "'.$entity->osr_purchase_order_no.'".<br/>';
            }
            if($entity->osr_part_number != $woitemosrinfo->osr_part_number){
                $description .= 'Part Number was changed from "'.$woitemosrinfo->osr_part_number.'" to "'.$entity->osr_part_number.'".<br/>';
            }
            if($entity->osr_condition != $woitemosrinfo->osr_condition){
                $description .= 'Condition was changed from "'.$outsideRepairInfoCond[$woitemosrinfo->osr_condition].'" to "'.$outsideRepairInfoCond[$entity->osr_condition].'".<br/>';
            }
            if($entity->osr_description_of_work != $woitemosrinfo->osr_description_of_work){
                $description .= 'Description of Work was changed from "'.$woitemosrinfo->osr_description_of_work.'" to "'.$entity->osr_description_of_work.'".<br/>';
            }
            if($entity->osr_old_serial_number != $woitemosrinfo->osr_old_serial_number){
                $description .= 'Old Serial Number was changed from "'.$woitemosrinfo->osr_old_serial_number.'" to "'.$entity->osr_old_serial_number.'".<br/>';
            }
            if($entity->osr_new_serial_number != $woitemosrinfo->osr_new_serial_number){
                $description .= 'New Serial Number was changed from "'.$woitemosrinfo->osr_new_serial_number.'" to "'.$entity->osr_new_serial_number.'".<br/>';
            }
            if($entity->osr_labor_charge != $woitemosrinfo->osr_labor_charge){
                $description .= 'Labor Charge (To Customer) was changed from "'.$woitemosrinfo->osr_labor_charge.'" to "'.$entity->osr_labor_charge.'".<br/>';
            }
            if($entity->osr_parts_charge != $woitemosrinfo->osr_parts_charge){
                $description .= 'Parts Charge (To Customer) was changed from "'.$woitemosrinfo->osr_parts_charge.'" to "'.$entity->osr_parts_charge.'".<br/>';
            }
            if($entity->osr_tax_labor != $woitemosrinfo->osr_tax_labor){
                $description .= 'Tax Labor was changed from "'.$woitemosrinfo->osr_tax_labor.'" to "'.$entity->osr_tax_labor.'".<br/>';
            }
            if($entity->osr_tax_parts != $woitemosrinfo->osr_tax_parts){
                $description .= 'Tax Parts was changed from "'.$woitemosrinfo->osr_tax_parts.'" to "'.$entity->osr_tax_parts.'".<br/>';
            }
            if($entity->osr_shipping_out != $woitemosrinfo->osr_shipping_out){
                $description .= 'Shipping Out was changed from "'.$woitemosrinfo->osr_shipping_out.'" to "'.$entity->osr_shipping_out.'".<br/>';
            }
            if($entity->osr_shipping_in != $woitemosrinfo->osr_shipping_in){
                $description .= 'Shipping In was changed from "'.$woitemosrinfo->osr_shipping_in.'" to "'.$entity->osr_shipping_in.'".<br/>';
            }
            if($entity->osr_vendor_labor_charges != $woitemosrinfo->osr_vendor_labor_charges){
                $description .= 'Vendor Labor Charge was changed from "'.$woitemosrinfo->osr_vendor_labor_charges.'" to "'.$entity->osr_vendor_labor_charges.'".<br/>';
            }
            if($entity->osr_vendor_part_charges != $woitemosrinfo->osr_vendor_part_charges){
                $description .= 'Vendor Parts Charge was changed from "'.$woitemosrinfo->osr_vendor_part_charges.'" to "'.$entity->osr_vendor_part_charges.'".<br/>';
            }
            if($entity->osr_date_due != $woitemosrinfo->osr_date_due){
                $description .= 'Date Due was changed from "'.$woitemosrinfo->osr_date_due.'" to "'.$entity->osr_date_due.'".<br/>';
            }
            if($entity->osr_inspector_code != $woitemosrinfo->osr_inspector_code){
                $description .= 'Inspector Code was changed from "'.$woitemosrinfo->osr_inspector_code.'" to "'.$entity->osr_inspector_code.'".<br/>';
            }
            
            $AircraftWOItemHistories->user_id = $entity->updated_by;
            $AircraftWOItemHistories->description = $description;
            $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
        }

        return true;
    }
    
}
