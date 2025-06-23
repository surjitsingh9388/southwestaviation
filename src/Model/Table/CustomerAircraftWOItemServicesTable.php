<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;

/**
 * CustomerAircraftWOItemServices Model
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
class CustomerAircraftWOItemServicesTable extends Table
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
        $this->setTable('customer_aircraft_wo_item_services');
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
            $CustomerAircraftWOItemServices =  FactoryLocator::get('Table')->get('CustomerAircraftWOItemServices');
            $woitemservices = $CustomerAircraftWOItemServices->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Service was updated.';
            
            if(!empty($woitemservices->updated_at)){
                $modified_from = str_replace('-', '/', $woitemservices->updated_at);
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

            $technicianBillingStyle = unserialize(TECHNICIANBILLINGSTYLE);

            $description = '';

            if($entity->flat_rate != $woitemservices->flat_rate){
                $description .= 'Flat Rate was changed from "'.$woitemservices->flat_rate.'" to "'.$entity->flat_rate.'".<br/>';
            }
            if($entity->flat_rate_qty != $woitemservices->flat_rate_qty){
                $description .= 'Item Status was changed from "'.$woitemservices->flat_rate_qty.'" to "'.$entity->flat_rate_qty.'".<br/>';
            }
            
            if($entity->repair_technician != $woitemservices->repair_technician){
                $description .= 'Repair Technician was changed from "'.$woitemservices->repair_technician.'" to "'.$entity->repair_technician.'".<br/>';
            }
            if($entity->service_rate_an_hour != $woitemservices->service_rate_an_hour){
                $description .= 'Rate an Hour was changed from "'.$woitemservices->service_rate_an_hour.'" to "'.$entity->service_rate_an_hour.'".<br/>';
            }
            if($entity->technician_billing_style != $woitemservices->technician_billing_style){
                $description .= 'Technician Billing Style was changed from "'.(!empty($woitemservices->technician_billing_style) ? $technicianBillingStyle[$woitemservices->technician_billing_style] : '').'" to "'.(!empty($entity->technician_billing_style) ? $technicianBillingStyle[$entity->technician_billing_style] : '').'".<br/>';
            }
            if($entity->is_lead_tech_on_item != $woitemservices->is_lead_tech_on_item){
                $description .= 'Is Lead Tech on Item was changed from "'.$woitemservices->is_lead_tech_on_item.'" to "'.$entity->is_lead_tech_on_item.'".<br/>';
            }
            if($entity->currently_on_overtime != $woitemservices->currently_on_overtime){
                $description .= 'Currently on Overtime was changed from "'.$woitemservices->currently_on_overtime.'" to "'.$entity->currently_on_overtime.'".<br/>';
            }
            if($entity->service_notes != $woitemservices->service_notes){
                $description .= 'Note was changed from "'.$woitemservices->service_notes.'" to "'.$entity->service_notes.'".<br/>';
            }
            if($entity->service_override_hrs != $woitemservices->service_override_hrs){
                $description .= 'Override Hrs. was changed from "'.$woitemservices->service_override_hrs.'" to "'.$entity->service_override_hrs.'".<br/>';
            }
            if($entity->hrs_worked != $woitemservices->hrs_worked){
                $description .= 'Hrs. Worked was changed from "'.$woitemservices->hrs_worked.'" to "'.$entity->hrs_worked.'".<br/>';
            }
            if($entity->service_overtime_hrs != $woitemservices->service_overtime_hrs){
                $description .= 'Overtime Hrs. was changed from "'.$woitemservices->service_overtime_hrs.'" to "'.$entity->service_overtime_hrs.'".<br/>';
            }
            if($entity->estimated_hrs_for_item != $woitemservices->estimated_hrs_for_item){
                $description .= 'Estimated Hrs. for Item was changed from "'.$woitemservices->estimated_hrs_for_item.'" to "'.$entity->estimated_hrs_for_item.'".<br/>';
            }
            if($entity->total_hrs_for_tech != $woitemservices->total_hrs_for_tech){
                $description .= 'Total Hrs. for Tech was changed from "'.$woitemservices->total_hrs_for_tech.'" to "'.$entity->total_hrs_for_tech.'".<br/>';
            }
            if($entity->total_hrs_for_item != $woitemservices->total_hrs_for_item){
                $description .= 'Total Hrs. for Item was changed from "'.$woitemservices->total_hrs_for_item.'" to "'.$entity->total_hrs_for_item.'".<br/>';
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
