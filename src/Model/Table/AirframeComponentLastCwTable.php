<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Http\Session;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

/**
 * AirframeComponentLastCw Model
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
class AirframeComponentLastCwTable extends Table
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
        $this->setTable('airframe_component_last_cw');
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
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AirframeComponentParts', [
            'foreignKey' => 'airframe_component_part_id',
            'joinType' => 'INNER'
        ]);

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
            ->integer('airframe_component_part_id')
            ->notEmptyString('airframe_component_part_id');
        
        /*$validator
            ->date('last_cw_date')
            ->allowEmptyString('last_cw_date');

        $validator
            ->decimal('last_cw_hrs')
            ->allowEmptyString('last_cw_hrs');

        $validator
            ->integer('last_cw_afl')
            ->allowEmptyString('last_cw_afl');

        $validator
            ->integer('last_cw_msc')
            ->allowEmptyString('last_cw_msc');
        
        $validator
            ->date('next_due_date')
            ->allowEmptyString('next_due_date');

        $validator
            ->decimal('next_due_hrs')
            ->allowEmptyString('next_due_hrs');

        $validator
            ->integer('next_due_afl')
            ->allowEmptyString('next_due_afl');

        $validator
            ->integer('next_due_msc')
            ->allowEmptyString('next_due_msc');

        $validator
            ->decimal('tolerance_mos')
            ->allowEmptyString('tolerance_mos');

        $validator
            ->integer('tolerance_days')
            ->allowEmptyString('tolerance_days');

        $validator
            ->decimal('tolerance_hrs')
            ->allowEmptyString('tolerance_hrs');

        $validator
            ->integer('tolerance_afl')
            ->allowEmptyString('tolerance_afl');

        $validator
            ->integer('alert_days')
            ->allowEmptyString('alert_days');

        $validator
            ->decimal('alert_hrs')
            ->allowEmptyString('alert_hrs');

        $validator
            ->integer('alert_afl')
            ->allowEmptyString('alert_afl');

        $validator
            ->decimal('recurring_mos')
            ->allowEmptyString('recurring_mos');

        $validator
            ->integer('recurring_days')
            ->allowEmptyString('recurring_days');

        $validator
            ->decimal('recurring_hrs')
            ->allowEmptyString('recurring_hrs');

        $validator
            ->integer('recurring_afl')
            ->allowEmptyString('recurring_afl');

        $validator
            ->decimal('threshold_mos')
            ->allowEmptyString('threshold_mos');

        $validator
            ->integer('threshold_days')
            ->allowEmptyString('threshold_days');

        $validator
            ->decimal('threshold_hrs')
            ->allowEmptyString('threshold_hrs');

        $validator
            ->integer('threshold_afl')
            ->allowEmptyString('threshold_afl');

        $validator
            ->decimal('required_frequency_mos')
            ->allowEmptyString('required_frequency_mos');

        $validator
            ->integer('required_frequency_days')
            ->allowEmptyString('required_frequency_days');

        $validator
            ->decimal('required_frequency_hrs')
            ->allowEmptyString('required_frequency_hrs');

        $validator
            ->integer('required_frequency_afl')
            ->allowEmptyString('required_frequency_afl');

        $validator
            ->decimal('adjustment_mos')
            ->allowEmptyString('adjustment_mos');

        $validator
            ->integer('adjustment_days')
            ->allowEmptyString('adjustment_days');

        $validator
            ->decimal('adjustment_hrs')
            ->allowEmptyString('adjustment_hrs');

        $validator
            ->integer('adjustment_afl')
            ->allowEmptyString('adjustment_afl');

        $validator
            ->scalar('last_revised_by')
            ->maxLength('last_revised_by', 100)
            ->allowEmptyString('last_revised_by');

        $validator
            ->scalar('last_reported_by')
            ->maxLength('last_reported_by', 100)
            ->allowEmptyString('last_reported_by');

        $validator
            ->scalar('is_recThres')
            ->maxLength('is_recThres', 50)
            ->allowEmptyString('is_recThres');*/

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
            $airframeComponentPartsModel =  FactoryLocator::get('Table')->get('AirframeComponentLastCw');
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
            if($entity->override != $airframeComponentParts->override){
                $description .= 'Override Next Due was changed from "'.$airframeComponentParts->override.'" to "'.$entity->override.'".<br/>';
            }
            if($entity->eom != $airframeComponentParts->eom){
                $description .= 'EoM Adj was changed from "'.$airframeComponentParts->eom.'" to "'.$entity->eom.'".<br/>';
            }
            if($entity->last_cw_date != $airframeComponentParts->last_cw_date){
                $description .= 'Last Complied With Date was changed from "'.$airframeComponentParts->last_cw_date.'" to "'.$entity->last_cw_date.'".<br/>';
            }
            if($entity->last_cw_hrs != $airframeComponentParts->last_cw_hrs){
                $description .= 'Last Complied With Hours was changed from "'.$airframeComponentParts->last_cw_hrs.'" to "'.$entity->last_cw_hrs.'".<br/>';
            }
            if($entity->last_cw_afl != $airframeComponentParts->last_cw_afl){
                $description .= 'Last Complied With Cycles was changed from "'.$airframeComponentParts->last_cw_afl.'" to "'.$entity->last_cw_afl.'".<br/>';
            }
            if($entity->next_due_date != $airframeComponentParts->next_due_date){
                $description .= 'Next Due Date was changed from "'.$airframeComponentParts->next_due_date.'" to "'.$entity->next_due_date.'".<br/>';
            }
            if($entity->next_due_hrs != $airframeComponentParts->next_due_hrs){
                $description .= 'Next Due Hours was changed from "'.$airframeComponentParts->next_due_hrs.'" to "'.$entity->next_due_hrs.'".<br/>';
            }
            if($entity->next_due_afl != $airframeComponentParts->next_due_afl){
                $description .= 'Next Due Cycles was changed from "'.$airframeComponentParts->next_due_afl.'" to "'.$entity->next_due_afl.'".<br/>';
            }
            if($entity->tolerance_mos != $airframeComponentParts->tolerance_mos){
                $description .= 'Tolerance Months was changed from "'.$airframeComponentParts->tolerance_mos.'" to "'.$entity->tolerance_mos.'".<br/>';
            }
            if($entity->tolerance_days != $airframeComponentParts->tolerance_days){
                $description .= 'Tolerance Days was changed from "'.$airframeComponentParts->tolerance_days.'" to "'.$entity->tolerance_days.'".<br/>';
            }
            if($entity->tolerance_hrs != $airframeComponentParts->tolerance_hrs){
                $description .= 'Tolerance Hours was changed from "'.$airframeComponentParts->tolerance_hrs.'" to "'.$entity->tolerance_hrs.'".<br/>';
            }
            if($entity->tolerance_afl != $airframeComponentParts->tolerance_afl){
                $description .= 'Tolerance Cycles was changed from "'.$airframeComponentParts->tolerance_afl.'" to "'.$entity->tolerance_afl.'".<br/>';
            }
            if($entity->alert_days != $airframeComponentParts->alert_days){
                $description .= 'Alert Days was changed from "'.$airframeComponentParts->alert_days.'" to "'.$entity->alert_days.'".<br/>';
            }
            if($entity->alert_hrs != $airframeComponentParts->alert_hrs){
                $description .= 'Alert Hours was changed from "'.$airframeComponentParts->alert_hrs.'" to "'.$entity->alert_hrs.'".<br/>';
            }
            if($entity->alert_afl != $airframeComponentParts->alert_afl){
                $description .= 'Alert Cycles was changed from "'.$airframeComponentParts->alert_afl.'" to "'.$entity->alert_afl.'".<br/>';
            }
            if($entity->recurring_hrs != $airframeComponentParts->recurring_hrs){
                $description .= 'Recurring Hours was changed from "'.$airframeComponentParts->recurring_hrs.'" to "'.$entity->recurring_hrs.'".<br/>';
            }
            if($entity->recurring_afl != $airframeComponentParts->recurring_afl){
                $description .= 'Recurring Cycles was changed from "'.$airframeComponentParts->recurring_afl.'" to "'.$entity->recurring_afl.'".<br/>';
            }
            if($entity->recurring_days != $airframeComponentParts->recurring_days){
                $description .= 'Recurring Days was changed from "'.$airframeComponentParts->recurring_days.'" to "'.$entity->recurring_days.'".<br/>';
            }
            if($entity->recurring_hrs != $airframeComponentParts->recurring_hrs){
                $description .= 'Recurring Hours was changed from "'.$airframeComponentParts->recurring_hrs.'" to "'.$entity->recurring_hrs.'".<br/>';
            }
            if($entity->threshold_mos != $airframeComponentParts->threshold_mos){
                $description .= 'Threshold Months was changed from "'.$airframeComponentParts->threshold_mos.'" to "'.$entity->threshold_mos.'".<br/>';
            }
            if($entity->threshold_days != $airframeComponentParts->threshold_days){
                $description .= 'Threshold Days was changed from "'.$airframeComponentParts->threshold_days.'" to "'.$entity->threshold_days.'".<br/>';
            }
            if($entity->threshold_hrs != $airframeComponentParts->threshold_hrs){
                $description .= 'Threshold Hours was changed from "'.$airframeComponentParts->threshold_hrs.'" to "'.$entity->threshold_hrs.'".<br/>';
            }
            if($entity->threshold_afl != $airframeComponentParts->threshold_afl){
                $description .= 'Threshold Cycles was changed from "'.$airframeComponentParts->threshold_afl.'" to "'.$entity->threshold_afl.'".<br/>';
            }
            if($entity->required_frequency_mos != $airframeComponentParts->required_frequency_mos){
                $description .= 'Interval Months was changed from "'.$airframeComponentParts->required_frequency_mos.'" to "'.$entity->required_frequency_mos.'".<br/>';
            }
            if($entity->required_frequency_days != $airframeComponentParts->required_frequency_days){
                $description .= 'Interval Days was changed from "'.$airframeComponentParts->required_frequency_days.'" to "'.$entity->required_frequency_days.'".<br/>';
            }
            if($entity->required_frequency_hrs != $airframeComponentParts->required_frequency_hrs){
                $description .= 'Interval Hours was changed from "'.$airframeComponentParts->required_frequency_hrs.'" to "'.$entity->required_frequency_hrs.'".<br/>';
            }
            if($entity->required_frequency_afl != $airframeComponentParts->required_frequency_afl){
                $description .= 'Interval Cycles was changed from "'.$airframeComponentParts->required_frequency_afl.'" to "'.$entity->required_frequency_afl.'".<br/>';
            }
            if($entity->adjustment_mos != $airframeComponentParts->adjustment_mos){
                $description .= 'Adjustment Months was changed from "'.$airframeComponentParts->adjustment_mos.'" to "'.$entity->adjustment_mos.'".<br/>';
            }
            if($entity->adjustment_days != $airframeComponentParts->adjustment_days){
                $description .= 'Adjustment Days No was changed from "'.$airframeComponentParts->adjustment_days.'" to "'.$entity->adjustment_days.'".<br/>';
            }
            if($entity->adjustment_hrs != $airframeComponentParts->adjustment_hrs){
                $description .= 'Adjustment Hours was changed from "'.$airframeComponentParts->adjustment_hrs.'" to "'.$entity->adjustment_hrs.'".<br/>';
            }
            if($entity->adjustment_afl != $airframeComponentParts->adjustment_afl){
                $description .= 'Adjustment Cycles was changed from "'.$airframeComponentParts->adjustment_afl.'" to "'.$entity->adjustment_afl.'".<br/>';
            }
            if($entity->last_revised_by != $airframeComponentParts->last_revised_by){
                $description .= 'Last Revised By was changed from "'.$airframeComponentParts->last_revised_by.'" to "'.$entity->last_revised_by.'".<br/>';
            }
            if($entity->last_reported_by != $airframeComponentParts->last_reported_by){
                $description .= 'Last Reported By was changed from "'.$airframeComponentParts->last_reported_by.'" to "'.$entity->last_reported_by.'".<br/>';
            }
            if($entity->admin_notes != $airframeComponentParts->admin_notes){
                $description .= 'Notes was changed from "'.$airframeComponentParts->admin_notes.'" to "'.$entity->admin_notes.'".<br/>';
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
