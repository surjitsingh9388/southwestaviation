<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * CustomerAircraftWorkOrderItems Model
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
class CustomerAircraftWOItemsTable extends Table
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
        $this->setTable('customer_aircraft_wo_items');
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
            ->integer('work_order_id')
            ->notEmpty('work_order_id');
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        /*if (!empty($entity->discrepancy_date)) {
            $entity->discrepancy_date = $this->Planes->dateFormatBeforeSave($entity->discrepancy_date);
        }*/

        if(!empty($entity->id)){
            $CustomerAircraftWOItemsModel = TableRegistry::get('CustomerAircraftWOItems');
            $aircraftwoitems = $CustomerAircraftWOItemsModel->get($entity->id);
            $AircraftWOItemHistoriesModel = TableRegistry::get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEntity();
            
            $AircraftWOItemHistories->work_order_id = $entity->work_order_id;
            $AircraftWOItemHistories->wo_item_id = $entity->id;

            $AircraftWOItemHistories->title = 'Item No. '.$entity->wo_item_position.' was updated.';
            
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

            $description = 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
            if($entity->wo_item_position != $aircraftwoitems->wo_item_position){
                $description .= 'Item No. was changed from "'.$aircraftwoitems->wo_item_position.'" to "'.$entity->wo_item_position.'".<br/>';
            }
            if($entity->wo_discrepancy != $aircraftwoitems->wo_discrepancy){
                $description .= 'Discrepancy was changed from "'.$aircraftwoitems->wo_discrepancy.'" to "'.$entity->wo_discrepancy.'".<br/>';
            }
            if($entity->wo_corrective_action != $aircraftwoitems->wo_corrective_action){
                $description .= 'Corrective Action was changed from "'.$aircraftwoitems->wo_corrective_action.'" to "'.$entity->wo_corrective_action.'".<br/>';
            }
            if($entity->wo_item_status != $aircraftwoitems->wo_item_status){
                $description .= 'Item Status was changed from "'.$aircraftItemStatus[$aircraftwoitems->wo_item_status].'" to "'.$aircraftItemStatus[$entity->wo_item_status].'".<br/>';
            }
            if($entity->item_notes != $aircraftwoitems->item_notes){
                $description .= 'Notes was changed from "'.$aircraftwoitems->item_notes.'" to "'.$entity->item_notes.'".<br/>';
            }

            $AircraftWOItemHistories->user_id = $entity->updated_by;
            $AircraftWOItemHistories->description = $description;
            $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
        }

        return true;
    }
    
}
