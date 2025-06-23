<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
//use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\ORM\TableRegistry;
use Cake\Http\Session;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

/**
 * PartInstalledTimes Model
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
class PartInstalledTimesTable extends Table
{
    //use SoftDeleteTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config):void
    {
        parent::initialize($config);
        $this->setTable('part_installed_times');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');        

        $this->belongsTo('Planes', [
            'foreignKey' => 'plane_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AirframeComponents', [
            'foreignKey' => 'airframe_component_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AirframeComponentParts', [
            'foreignKey' => 'airframe_component_part_id',
            'joinType' => 'INNER'
        ]);
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
            ->allowEmptyString('id', 'create');
        
        $validator
            ->integer('plane_id')
            ->notEmptyString('plane_id');
        
        $validator
            ->integer('airframe_component_id')
            ->notEmptyString('airframe_component_id');

        $validator
            ->integer('airframe_component_part_id')
            ->notEmptyString('airframe_component_part_id');
        
        $validator
            ->scalar('removed_part_number')
            ->maxLength('removed_part_number', 100)
            ->allowEmptyString('removed_part_number');

        $validator
            ->scalar('removed_serial_number')
            ->maxLength('removed_serial_number', 100)
            ->allowEmptyString('removed_serial_number');

        $validator
            ->scalar('removal_reason')
            ->maxLength('removal_reason', 50)
            ->allowEmptyString('removal_reason');

        $validator
            ->scalar('new_months')
            ->allowEmptyString('new_months');

        $validator
            ->decimal('new_hours')
            ->allowEmptyString('new_hours');

        $validator
            ->integer('new_landings')
            ->allowEmptyString('new_landings');

        $validator
            ->scalar('overhaul_months')
            ->allowEmptyString('overhaul_months');

        $validator
            ->decimal('overhaul_hours')
            ->allowEmptyString('overhaul_hours');

        $validator
            ->integer('overhaul_landings')
            ->allowEmptyString('overhaul_landings');

        $validator
            ->scalar('repair_months')
            ->allowEmptyString('repair_months');

        $validator
            ->decimal('repair_hours')
            ->allowEmptyString('repair_hours');

        $validator
            ->integer('repair_landings')
            ->allowEmptyString('repair_landings');

        $validator
            ->scalar('part_type')
            ->maxLength('part_type', 20)
            ->allowEmptyString('part_type');

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if(!empty($entity->last_cw_date)) {
            $entity->last_cw_date = $service->dateFormatBeforeSave($entity->last_cw_date);
        }

        if(!empty($entity->next_due_date)) {
            $entity->next_due_date = $service->dateFormatBeforeSave($entity->next_due_date);
        }

        if(!empty($entity->id)){
            $planeModel =  FactoryLocator::get('Table')->get('Planes');
            $airframeComponentPartsModel =  FactoryLocator::get('Table')->get('PartInstalledTimes');
            $airframeComponentParts = $airframeComponentPartsModel->get($entity->id);
            $airframeComponentPartsHistoriesModel =  FactoryLocator::get('Table')->get('AirframeComponentPartHistories');
            
            $airframeComponentPartsHistory = $airframeComponentPartsHistoriesModel->newEmptyEntity();
            $airframeComponentPartsHistory->airframe_component_part_id = $entity->id;

            $planeData = $planeModel->get($entity->plane_id);
            $airframeComponentPartsHistory->title = 'Airframe Component Part Aircraft '.$planeData->plane_name.' was updated.';
            
            if(!empty($airframeComponentParts->modified)){
                $modified_from = str_replace('-', '/', $airframeComponentParts->modified);
                $modified_from = date("Y-m-d h:i A", strtotime($modified_from));
            }else{
                $modified_from = '';
            }

            if(!empty($entity->modified)){
                $modified_to = str_replace('-', '/', $entity->modified);
                $modified_to = date("Y-m-d h:i A", strtotime($modified_to));
            }else{
                $modified_to = '';
            }

            $description = '';
            if($entity->plane_id != $airframeComponentParts->plane_id){
                $description .= 'Aircraft was changed from "'.$airframeComponentParts->plane_id.'" to "'.$entity->plane_id.'".<br/>';
            }
            if($entity->airframe_component_id != $airframeComponentParts->airframe_component_id){
                $description .= 'Airframe Component was changed from "'.$airframeComponentParts->airframe_component_id.'" to "'.$entity->airframe_component_id.'".<br/>';
            }
            if($entity->removed_part_number != $airframeComponentParts->removed_part_number){
                $description .= 'Part Information Removed Part Number was changed from "'.$airframeComponentParts->removed_part_number.'" to "'.$airframeComponentParts->removed_part_number.'".<br/>';
            }
            if($entity->removed_serial_number != $airframeComponentParts->eremoved_serial_numberom){
                $description .= 'Part Information Removed Serial Number was changed from "'.$airframeComponentParts->removed_serial_number.'" to "'.$entity->removed_serial_number.'".<br/>';
            }
            if($entity->removal_reason != $airframeComponentParts->removal_reason){
                $description .= 'Part Information Removal Reason was changed from "'.$airframeComponentParts->removal_reason.'" to "'.$entity->removal_reason.'".<br/>';
            }
            if($entity->new_months != $airframeComponentParts->new_months){
                $description .= 'Times Since (at Installed) New Months was changed from "'.$airframeComponentParts->new_months.'" to "'.$entity->new_months.'".<br/>';
            }
            if($entity->new_hours != $airframeComponentParts->new_hours){
                $description .= 'Times Since (at Installed) New Hours was changed from "'.$airframeComponentParts->new_hours.'" to "'.$entity->new_hours.'".<br/>';
            }
            if($entity->new_landings != $airframeComponentParts->new_landings){
                $description .= 'Times Since (at Installed) New Landings was changed from "'.$airframeComponentParts->new_landings.'" to "'.$entity->new_landings.'".<br/>';
            }
            if($entity->overhaul_months != $airframeComponentParts->overhaul_months){
                $description .= 'Times Since (at Installed) Overhaul Months was changed from "'.$airframeComponentParts->overhaul_months.'" to "'.$entity->overhaul_months.'".<br/>';
            }
            if($entity->overhaul_hours != $airframeComponentParts->overhaul_hours){
                $description .= 'Times Since (at Installed) Overhaul Hours was changed from "'.$airframeComponentParts->overhaul_hours.'" to "'.$entity->overhaul_hours.'".<br/>';
            }
            if($entity->overhaul_landings != $airframeComponentParts->overhaul_landings){
                $description .= 'Times Since (at Installed) Overhaul Landings was changed from "'.$airframeComponentParts->overhaul_landings.'" to "'.$entity->overhaul_landings.'".<br/>';
            }
            if($entity->repair_months != $airframeComponentParts->repair_months){
                $description .= 'Times Since (at Installed) Repair Months was changed from "'.$airframeComponentParts->repair_months.'" to "'.$entity->repair_months.'".<br/>';
            }
            if($entity->repair_hours != $airframeComponentParts->repair_hours){
                $description .= 'Times Since (at Installed) Repair Hours was changed from "'.$airframeComponentParts->repair_hours.'" to "'.$entity->repair_hours.'".<br/>';
            }
            if($entity->repair_landings != $airframeComponentParts->repair_landings){
                $description .= 'Times Since (at Installed) Repair Landings was changed from "'.$airframeComponentParts->repair_landings.'" to "'.$entity->repair_landings.'".<br/>';
            }
            if($entity->part_type != $airframeComponentParts->part_type){
                $description .= 'Times Since (at Installed) was changed from "'.$airframeComponentParts->part_type.'" to "'.$entity->part_type.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $airframeComponentPartsHistory->user_id = $sessionUser['id'];
                $airframeComponentPartsHistory->description = $description;
                $airframeComponentPartsHistoriesModel->save($airframeComponentPartsHistory);
            }
        }

        return true;
    }
    
}
