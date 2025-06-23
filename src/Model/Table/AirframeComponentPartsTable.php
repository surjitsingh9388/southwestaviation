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

/**
 * AirframeComponentParts Model
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
class AirframeComponentPartsTable extends Table
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
        $this->setTable('airframe_component_parts');
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

        $this->belongsTo('AirframeCategories', [
            'foreignKey' => 'airframe_category_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('AirframeComponentLastCw', [
            'foreignKey' => 'airframe_component_part_id'
        ]);

        $this->hasMany('PartInstalledTimes', [
            'foreignKey' => 'airframe_component_part_id'
        ]);

        $this->hasOne('Groups', [
            'foreignKey' => 'airframe_component_part_id'
        ]);

        $this->hasOne('ParentChildRelations', [
            'foreignKey' => 'airframe_component_part_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
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
            ->integer('airframe_category_id')
            ->allowEmptyString('airframe_category_id');

        $validator
            ->scalar('ata_code')
            ->maxLength('ata_code', 50)
            ->allowEmptyString('ata_code');

        $validator
            ->scalar('mfg_code')
            ->maxLength('mfg_code', 50)
            ->allowEmptyString('mfg_code');

        $validator
            ->scalar('item_type')
            ->maxLength('item_type', 50)
            ->allowEmptyString('item_type');

        $validator
            ->scalar('ad_sb_number')
            ->maxLength('ad_sb_number', 50)
            ->allowEmptyString('ad_sb_number');

        $validator
            ->scalar('ad_sb_status')
            ->maxLength('ad_sb_status', 50)
            ->allowEmptyString('ad_sb_status');

        $validator
            ->scalar('amendment')
            ->maxLength('amendment', 50)
            ->allowEmptyString('amendment');

        $validator
            ->scalar('authority')
            ->maxLength('authority', 50)
            ->allowEmptyString('authority');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->scalar('disposition')
            ->allowEmptyString('disposition');

        $validator
            ->scalar('reference')
            ->allowEmptyString('reference');

        $validator
            ->scalar('requirement_type')
            ->allowEmptyString('requirement_type');

        $validator
            ->scalar('tags')
            ->allowEmptyString('tags');

        $validator
            ->scalar('part_number')
            ->maxLength('part_number', 100)
            ->allowEmptyString('part_number');

        $validator
            ->scalar('serial_number')
            ->maxLength('serial_number', 100)
            ->allowEmptyString('serial_number');

        $validator
            ->scalar('installed_status')
            ->maxLength('installed_status', 50)
            ->allowEmptyString('installed_status');

        $validator
            ->scalar('work_description')
            ->allowEmptyString('work_description');

        $validator
            ->scalar('discrepancy_status')
            ->allowEmptyString('discrepancy_status');

        $validator
            ->scalar('hardware')
            ->maxLength('hardware', 100)
            ->allowEmptyString('hardware');
        
        $validator
            ->scalar('software')
            ->maxLength('software', 100)
            ->allowEmptyString('software');

        $validator
            ->scalar('mm_ref')
            ->maxLength('mm_ref', 200)
            ->allowEmptyString('mm_ref');

        $validator
            ->scalar('ops_numbers')
            ->maxLength('ops_numbers', 200)
            ->allowEmptyString('ops_numbers');

        $validator
            ->decimal('avg_man_hrs')
            ->allowEmptyString('avg_man_hrs');

        $validator
            ->decimal('approx_price')
            ->allowEmptyString('approx_price');

        $validator
            ->scalar('manufacturer')
            ->allowEmptyString('manufacturer');

        $validator
            ->scalar('admin_notes')
            ->allowEmptyString('admin_notes');

        $validator
            ->scalar('work_card')
            ->maxLength('work_card', 50)
            ->allowEmptyString('work_card');

        $validator
            ->scalar('position')
            ->maxLength('position', 50)
            ->allowEmptyString('position');

        $validator
            ->scalar('revision')
            ->maxLength('revision', 50)
            ->allowEmptyString('revision');

        $validator
            ->scalar('version')
            ->maxLength('version', 50)
            ->allowEmptyString('version');

        $validator
            ->integer('parent_id')
            ->allowEmptyString('parent_id');

        $validator
            ->integer('clone_id')
            ->allowEmptyString('clone_id');

        $validator
            ->scalar('quick_ref')
            ->maxLength('quick_ref', 20)
            ->allowEmptyString('quick_ref');

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->id)){
            $planeModel =  FactoryLocator::get('Table')->get('Planes');
            $airframeComponentPartsModel =  FactoryLocator::get('Table')->get('AirframeComponentParts');
            $airframeComponentParts = $airframeComponentPartsModel->get($entity->id);
            $airframeComponentPartsHistoriesModel =  FactoryLocator::get('Table')->get('AirframeComponentPartHistories');
            
            $airframeComponentPartsHistory = $airframeComponentPartsHistoriesModel->newEmptyEntity();
            $airframeComponentPartsHistory->plane_id = $entity->plane_id;
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
            if($entity->item_type != $airframeComponentParts->item_type){
                $description .= 'Item Type was changed from "'.$airframeComponentParts->item_type.'" to "'.$entity->item_type.'".<br/>';
            }
            if($entity->ata_code != $airframeComponentParts->ata_code){
                $description .= 'ATA was changed from "'.$airframeComponentParts->ata_code.'" to "'.$entity->ata_code.'".<br/>';
            }
            if($entity->mfg_code != $airframeComponentParts->mfg_code){
                $description .= 'Mfg Code was changed from "'.$airframeComponentParts->mfg_code.'" to "'.$entity->mfg_code.'".<br/>';
            }
            if($entity->ad_sb_number != $airframeComponentParts->ad_sb_number){
                $description .= 'AD/SB Number was changed from "'.$airframeComponentParts->ad_sb_number.'" to "'.$entity->ad_sb_number.'".<br/>';
            }
            if($entity->ad_sb_status != $airframeComponentParts->ad_sb_status){
                $description .= 'AD/SB Class was changed from "'.$airframeComponentParts->ad_sb_status.'" to "'.$entity->ad_sb_status.'".<br/>';
            }
            if($entity->reference != $airframeComponentParts->reference){
                $description .= 'Reference was changed from "'.$airframeComponentParts->reference.'" to "'.$entity->reference.'".<br/>';
            }
            if($entity->requirement_type != $airframeComponentParts->requirement_type){
                $description .= 'Requirement Type No was changed from "'.$airframeComponentParts->requirement_type.'" to "'.$entity->requirement_type.'".<br/>';
            }
            if($entity->amendment != $airframeComponentParts->amendment){
                $description .= 'Amendment was changed from "'.$airframeComponentParts->amendment.'" to "'.$entity->amendment.'".<br/>';
            }
            if($entity->authority != $airframeComponentParts->authority){
                $description .= 'Authority No was changed from "'.$airframeComponentParts->authority.'" to "'.$entity->authority.'".<br/>';
            }
            if($entity->position_id != $airframeComponentParts->position_id){
                $description .= 'Position was changed from "'.$airframeComponentParts->position_id.'" to "'.$entity->position_id.'".<br/>';
            }
            if($entity->description != $airframeComponentParts->description){
                $description .= 'Item Name was changed from "'.$airframeComponentParts->description.'" to "'.$entity->description.'".<br/>';
            }
            if($entity->notes != $airframeComponentParts->notes){
                $description .= 'Notes No was changed from "'.$airframeComponentParts->notes.'" to "'.$entity->notes.'".<br/>';
            }
            if($entity->work_description != $airframeComponentParts->work_description){
                $description .= 'Work Description was changed from "'.$airframeComponentParts->work_description.'" to "'.$entity->work_description.'".<br/>';
            }
            if($entity->avg_man_hrs != $airframeComponentParts->avg_man_hrs){
                $description .= 'Man Hours No was changed from "'.$airframeComponentParts->avg_man_hrs.'" to "'.$entity->avg_man_hrs.'".<br/>';
            }
            if($entity->tags != $airframeComponentParts->tags){
                $description .= 'Tags was changed from "'.$airframeComponentParts->tags.'" to "'.$entity->tags.'".<br/>';
            }
            if($entity->part_number != $airframeComponentParts->part_number){
                $description .= 'Part Number No was changed from "'.$airframeComponentParts->part_number.'" to "'.$entity->part_number.'".<br/>';
            }
            if($entity->serial_number != $airframeComponentParts->serial_number){
                $description .= 'Serial Number No was changed from "'.$airframeComponentParts->serial_number.'" to "'.$entity->serial_number.'".<br/>';
            }
            if($entity->installed_status != $airframeComponentParts->installed_status){
                $description .= 'Installed Status No was changed from "'.$airframeComponentParts->installed_status.'" to "'.$entity->installed_status.'".<br/>';
            }
            if($entity->admin_notes != $airframeComponentParts->admin_notes){
                $description .= 'Notes was changed from "'.$airframeComponentParts->admin_notes.'" to "'.$entity->admin_notes.'".<br/>';
            }
            if($entity->work_card != $airframeComponentParts->work_card){
                $description .= 'Work Card was changed from "'.$airframeComponentParts->work_card.'" to "'.$entity->work_card.'".<br/>';
            }
            if($entity->position != $airframeComponentParts->position){
                $description .= 'Position was changed from "'.$airframeComponentParts->position.'" to "'.$entity->position.'".<br/>';
            }
            if($entity->version != $airframeComponentParts->version){
                $description .= 'Version was changed from "'.$airframeComponentParts->version.'" to "'.$entity->version.'".<br/>';
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
