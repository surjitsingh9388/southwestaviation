<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

/**
 * CustomerAircraftWOItemOverviews Model
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
class CustomerAircraftWOItemOverviewsTable extends Table
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
        $this->setTable('customer_aircraft_wo_item_overviews');
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
            ->integer('work_order_id')
            ->notEmptyString('work_order_id');
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        /*if (!empty($entity->discrepancy_date)) {
            $entity->discrepancy_date = $service->dateFormatBeforeSave($entity->discrepancy_date);
        }*/

        if(!empty($entity->id)){
            $CustomerAircraftWOItemOverviewsModel =  FactoryLocator::get('Table')->get('CustomerAircraftWOItemOverviews');
            $woitemoverviews = $CustomerAircraftWOItemOverviewsModel->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Overview was updated.';
            
            if(!empty($woitemoverviews->updated_at)){
                $modified_from = str_replace('-', '/', $woitemoverviews->updated_at);
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

            $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
            $warrantywoarr = unserialize(WOITEMOVERVIEWWARRANTY);
            $aircraftWOLogBookCategory = unserialize(AIRCRAFT_WORKORDER_LOGBOOK_CATEGORY);
            $aircraftWOWayofBilling = unserialize(AIRCRAFT_WORKORDER_WAYOF_BILLING);
            $authrization = ['1'=>'Open', '2'=>'Yes', '3'=>'No'];
            $contractRateDepartment = unserialize(CONTRACT_RATE_DEPARTMENT);

            $description = '';
            
            if($entity->wo_category != $woitemoverviews->wo_category){
                $description .= 'Category was changed from "'.(!empty($woitemoverviews->wo_category) ? $aircraftWOCategory[$woitemoverviews->wo_category] : '').'" to "'.(!empty($entity->wo_category) ? $aircraftWOCategory[$entity->wo_category] : '').'".<br/>';
            }
            if($entity->labor_kit != $woitemoverviews->labor_kit){
                $description .= 'Labor Kit Name was changed from "'.$woitemoverviews->labor_kit.'" to "'.$entity->labor_kit.'".<br/>';
            }
            if($entity->owner_authentication != $woitemoverviews->owner_authentication){
                $description .= 'Owner Authorization was changed from "'.(!empty($woitemoverviews->owner_authentication) ? $authrization[$woitemoverviews->owner_authentication] : '').'" to "'.(!empty($entity->owner_authentication) ? $authrization[$entity->owner_authentication] : '').'".<br/>';
            }
            if($entity->warranty != $woitemoverviews->warranty){
                $description .= 'Warranty was changed from "'.(!empty($woitemoverviews->warranty) ? $warrantywoarr[$woitemoverviews->warranty] : '').'" to "'.(!empty($entity->warranty) ? $warrantywoarr[$entity->warranty] : '').'".<br/>';
            }
            if($entity->warranty_claim_no != $woitemoverviews->warranty_claim_no){
                $description .= 'Warranty Claim No. was changed from "'.$woitemoverviews->warranty_claim_no.'" to "'.$entity->warranty_claim_no.'".<br/>';
            }
            if($entity->log_book_category != $woitemoverviews->log_book_category){
                $description .= 'Log Book Category was changed from "'.(!empty($woitemoverviews->log_book_category) ? $aircraftWOLogBookCategory[$woitemoverviews->log_book_category] : '').'" to "'.(!empty($entity->log_book_category) ? $aircraftWOLogBookCategory[$entity->log_book_category] : '').'".<br/>';
            }
            if($entity->ata_code_name != $woitemoverviews->ata_code_name){
                $description .= 'ATA Code was changed from "'.$woitemoverviews->ata_code_name.'" to "'.$entity->ata_code_name.'".<br/>';
            }
            if($entity->way_of_billing != $woitemoverviews->way_of_billing){
                $description .= 'Way of Billing was changed from "'.(!empty($woitemoverviews->way_of_billing) ? $aircraftWOWayofBilling[$woitemoverviews->way_of_billing] : '').'" to "'.(!empty($entity->way_of_billing) ? $aircraftWOWayofBilling[$entity->way_of_billing] : '').'".<br/>';
            }
            if($entity->department != $woitemoverviews->department){
                $description .= 'Department was changed from "'.(!empty($woitemoverviews->department) ? $contractRateDepartment[$woitemoverviews->department] : '').'" to "'.(!empty($entity->department) ? $contractRateDepartment[$entity->department] : '').'".<br/>';
            }
            if($entity->shipping_in != $woitemoverviews->shipping_in){
                $description .= 'Shipping In was changed from "'.$woitemoverviews->shipping_in.'" to "'.$entity->shipping_in.'".<br/>';
            }
            if($entity->special_rate_hr != $woitemoverviews->special_rate_hr){
                $description .= 'Special Rate / Hr was changed from "'.$woitemoverviews->special_rate_hr.'" to "'.$entity->special_rate_hr.'".<br/>';
            }
            if($entity->estimated_hour != $woitemoverviews->estimated_hour){
                $description .= 'Estimated Hrs was changed from "'.$woitemoverviews->estimated_hour.'" to "'.$entity->estimated_hour.'".<br/>';
            }
            if($entity->estimated_rate != $woitemoverviews->estimated_rate){
                $description .= 'Estimated Rate was changed from "'.$woitemoverviews->estimated_rate.'" to "'.$entity->estimated_rate.'".<br/>';
            }
            if($entity->flat_rate != $woitemoverviews->flat_rate){
                $description .= 'Flat Rate was changed from "'.$woitemoverviews->flat_rate.'" to "'.$entity->flat_rate.'".<br/>';
            }
            if($entity->flat_rate_qty != $woitemoverviews->flat_rate_qty){
                $description .= 'Item Status was changed from "'.$woitemoverviews->flat_rate_qty.'" to "'.$entity->flat_rate_qty.'".<br/>';
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
