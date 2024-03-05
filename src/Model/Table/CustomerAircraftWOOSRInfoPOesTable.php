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
class CustomerAircraftWOOSRInfoPOesTable extends Table
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
        $this->setTable('customer_aircraft_wo_osr_info_poes');
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
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if (!empty($entity->date_order_placed)) {
            $entity->date_order_placed = $this->Planes->dateFormatBeforeSave($entity->date_order_placed);
        }
        if (!empty($entity->general_est_arrival_date)) {
            $entity->general_est_arrival_date = $this->Planes->dateFormatBeforeSave($entity->general_est_arrival_date);
        }

        /*if(!empty($entity->id)){
            $this->CustomerAircraftWOOSRInfoPOes = TableRegistry::get('CustomerAircraftWOOSRInfoPOes');
            $woitemosrinfopoes = $this->CustomerAircraftWOOSRInfoPOes->get($entity->id);
            $AircraftWOItemHistoriesModel = TableRegistry::get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEntity();
            
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Outside Repair Service P/O was updated.';
            
            if(!empty($woitemosrinfopoes->updated_at)){
                $modified_from = str_replace('-', '/', $woitemosrinfopoes->updated_at);
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

            $defaultPaymentMethod = unserialize(DEFAULTPAYMENTMETHOD);
            $aircraftWOOSRPOStatus = unserialize(AIRCRAFT_WO_OSR_PO_STATUS);
            $defaultOTCShippingMethod = unserialize(DEFAULTOTCSHIPPINGMETHOD);

            if($entity->vendor_id != $woitemosrinfopoes->vendor_id){
                $description .= 'Vendor was changed from "'.$woitemosrinfopoes->vendor_id.'" to "'.$entity->vendor_id.'".<br/>';
            }
            if($entity->osr_invoice_no != $woitemosrinfopoes->osr_invoice_no){
                $description .= 'Invoice No was changed from "'.$woitemosrinfopoes->osr_invoice_no.'" to "'.$entity->osr_invoice_no.'".<br/>';
            }
            $description = 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
            if($entity->vendor_phone != $woitemosrinfopoes->vendor_phone){
                $description .= 'Vendor Phone was changed from "'.$woitemosrinfopoes->vendor_phone.'" to "'.$entity->vendor_phone.'".<br/>';
            }
            if($entity->date_order_placed != $woitemosrinfopoes->date_order_placed){
                $description .= 'Date Order Placed was changed from "'.$woitemosrinfopoes->date_order_placed.'" to "'.$entity->date_order_placed.'".<br/>';
            }
            if($entity->general_est_arrival_date != $woitemosrinfopoes->general_est_arrival_date){
                $description .= 'General Est. Arrival Date was changed from "'.$woitemosrinfopoes->general_est_arrival_date.'" to "'.$entity->general_est_arrival_date.'".<br/>';
            }
            if($entity->term != $woitemosrinfopoes->term){
                $description .= 'Terms was changed from "'.$defaultPaymentMethod[$woitemosrinfopoes->term].'" to "'.$defaultPaymentMethod[$entity->term].'".<br/>';
            }
            if($entity->po_status != $woitemosrinfopoes->po_status){
                $description .= 'P/O Status was changed from "'.$aircraftWOOSRPOStatus[$woitemosrinfopoes->po_status].'" to "'.$aircraftWOOSRPOStatus[$entity->po_status].'".<br/>';
            }
            if($entity->vendor_contact != $woitemosrinfopoes->vendor_contact){
                $description .= 'Vendor Contact was changed from "'.$woitemosrinfopoes->vendor_contact.'" to "'.$entity->vendor_contact.'".<br/>';
            }
            if($entity->total_shipping_cost != $woitemosrinfopoes->total_shipping_cost){
                $description .= 'Total Shipping Cost was changed from "'.$woitemosrinfopoes->total_shipping_cost.'" to "'.$entity->total_shipping_cost.'".<br/>';
            }
            if($entity->ship_to != $woitemosrinfopoes->ship_to){
                $description .= 'Ship To was changed from "'.$woitemosrinfopoes->ship_to.'" to "'.$entity->ship_to.'".<br/>';
            }
            if($entity->full_address_info != $woitemosrinfopoes->full_address_info){
                $description .= 'Full Address Info was changed from "'.$woitemosrinfopoes->full_address_info.'" to "'.$entity->full_address_info.'".<br/>';
            }
            if($entity->ship_method != $woitemosrinfopoes->ship_method){
                $description .= 'Ship Method was changed from "'.$defaultOTCShippingMethod[$woitemosrinfopoes->ship_method].'" to "'.$defaultOTCShippingMethod[$entity->ship_method].'".<br/>';
            }
            if($entity->rma_number != $woitemosrinfopoes->rma_number){
                $description .= 'RMA Number was changed from "'.$woitemosrinfopoes->rma_number.'" to "'.$entity->rma_number.'".<br/>';
            }
            if($entity->tracking_number != $woitemosrinfopoes->tracking_number){
                $description .= 'Tracking Number was changed from "'.$woitemosrinfopoes->tracking_number.'" to "'.$entity->tracking_number.'".<br/>';
            }
            if($entity->tracking_number1 != $woitemosrinfopoes->tracking_number1){
                $description .= 'Tracking Number1 was changed from "'.$woitemosrinfopoes->tracking_number1.'" to "'.$entity->tracking_number1.'".<br/>';
            }
            if($entity->tracking_number2 != $woitemosrinfopoes->tracking_number2){
                $description .= 'Tracking Number2 was changed from "'.$woitemosrinfopoes->tracking_number2.'" to "'.$entity->tracking_number2.'".<br/>';
            }
            if($entity->tracking_number3 != $woitemosrinfopoes->tracking_number3){
                $description .= 'Tracking Number3 was changed from "'.$woitemosrinfopoes->tracking_number3.'" to "'.$entity->tracking_number3.'".<br/>';
            }
            if($entity->tracking_number4 != $woitemosrinfopoes->tracking_number4){
                $description .= 'Tracking Number4 was changed from "'.$woitemosrinfopoes->tracking_number4.'" to "'.$entity->tracking_number4.'".<br/>';
            }
            if($entity->tracking_number5 != $woitemosrinfopoes->tracking_number5){
                $description .= 'Tracking Number5 was changed from "'.$woitemosrinfopoes->tracking_number5.'" to "'.$entity->tracking_number5.'".<br/>';
            }
            
            $AircraftWOItemHistories->user_id = $entity->updated_by;
            $AircraftWOItemHistories->description = $description;
            $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
        }*/

        return true;
    }
    
}
