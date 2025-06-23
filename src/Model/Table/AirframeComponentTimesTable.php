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
 * AirframeComponentTimes Model
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
class AirframeComponentTimesTable extends Table
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
        $this->setTable('airframe_component_times');
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

        $this->hasMany('ExtraDetails', [
            'foreignKey' => 'airframe_component_time_id'
        ]);

        $this->belongsTo('Flightlogs', [
            'foreignKey' => 'flightlog_id',
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
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if (!empty($entity->log_date)) {
            $entity->log_date = $service->dateFormatBeforeSave($entity->log_date);
        }

        if(!empty($entity->id)){
            $planeModel =  FactoryLocator::get('Table')->get('Planes');
            $airframeComponentTimesModel =  FactoryLocator::get('Table')->get('AirframeComponentTimes');
            $airframeComponentTimes = $airframeComponentTimesModel->get($entity->id);
            $airframeComponentTimesHistoriesModel =  FactoryLocator::get('Table')->get('AirframeComponentTimeHistories');
            
            $airframeComponentTimesHistory = $airframeComponentTimesHistoriesModel->newEmptyEntity();
            $airframeComponentTimesHistory->plane_id = $entity->plane_id;
            $airframeComponentTimesHistory->airframe_component_time_id = $entity->id;

            $planeData = $planeModel->get($entity->plane_id);
            $airframeComponentTimesHistory->title = 'Airframe Component Times Aircraft '.$planeData->plane_name.' was updated.';
            
            if(!empty($airframeComponentTimes->modified)){
                $modified_from = str_replace('-', '/', $airframeComponentTimes->modified);
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
            if($entity->plane_id != $airframeComponentTimes->plane_id){
                $description .= 'Aircraft was changed from "'.$airframeComponentTimes->plane_id.'" to "'.$entity->plane_id.'".<br/>';
            }
            if($entity->airframe_component_id != $airframeComponentTimes->airframe_component_id){
                $description .= 'Airframe Component was changed from "'.$airframeComponentTimes->airframe_component_id.'" to "'.$entity->airframe_component_id.'".<br/>';
            }
            if(strtotime($entity->log_date) != strtotime($airframeComponentTimes->log_date)){
                $description .= 'Log Date was changed from "'.$airframeComponentTimes->log_date.'" to "'.$entity->log_date.'".<br/>';
            }
            if($entity->hours != $airframeComponentTimes->hours){
                $description .= 'Hours was changed from "'.$airframeComponentTimes->hours.'" to "'.$entity->hours.'".<br/>';
            }
            if($entity->cycles != $airframeComponentTimes->cycles){
                $description .= 'Cycles No was changed from "'.$airframeComponentTimes->cycles.'" to "'.$entity->cycles.'".<br/>';
            }
            
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $airframeComponentTimesHistory->user_id = $sessionUser['id'];
                $airframeComponentTimesHistory->description = $description;
                $airframeComponentTimesHistoriesModel->save($airframeComponentTimesHistory);
            }
        }

        return true;
    }    
}
