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
 * Planes Model
 *
 * @method \App\Model\Entity\Routes get($primaryKey, $options = [])
 * @method \App\Model\Entity\Routes newEmptyEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Routes[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Routes|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Routes patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Routes[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Routes findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PlanesTable extends Table
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
        $this->setTable('planes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');        
        
        $this->hasMany('AirframeComponents', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AirframeCategories', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AirframeComponentTimes', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AirframeComponentParts', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AirframeComponentLastCW', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AddDiscrepancies', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('ExtraDetails', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('PartInstalledTimes', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('Utilizations', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('Groups', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('Flightlogs', [
            'foreignKey' => 'plane_id',
            'dependent' => true
        ]);

        /*$this->hasMany('CrewDetails', [
            'foreignKey' => 'plane_id',
            'dependent' => true
        ]);*/

        $this->hasMany('Crews', [
            'foreignKey' => 'plane_id',
            'dependent' => true
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
            ->scalar('plane_name')
            ->maxLength('plane_name', 100)
            ->allowEmptyString('plane_name');
        
        $validator
            ->scalar('plane_type')
            ->maxLength('plane_type', 100)
            ->allowEmptyString('plane_type');

        $validator
            ->scalar('plane_code')
            ->maxLength('plane_code', 40)
            ->requirePresence('plane_code', 'create')
            ->notEmptyString('plane_code');
        
        $validator
            ->scalar('manufactured_by')
            ->maxLength('manufactured_by', 200)
            ->allowEmptyString('manufactured_by');
        
        $validator
            ->scalar('plane_serial_number')
            ->maxLength('plane_serial_number', 100)
            ->allowEmptyString('plane_serial_number');

        $validator
            ->scalar('federal_aviation_regulation')
            ->maxLength('federal_aviation_regulation', 100)
            ->allowEmptyString('federal_aviation_regulation');

        $validator
            ->scalar('address')
            ->allowEmptyString('address');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules):RulesChecker
    {
        $rules->add($rules->isUnique(['plane_code']));
        return $rules;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if (!empty($entity->airworthiness_date)) {
            $entity->airworthiness_date = $service->dateFormatBeforeSave($entity->airworthiness_date);
        }

        if (!empty($entity->manufacturered_on)) {
            $entity->manufacturered_on = $service->dateFormatBeforeSave($entity->manufacturered_on);
        }
        
        if(!empty($entity->id)){
            $planeModel =  FactoryLocator::get('Table')->get('Planes');
            $planes = $planeModel->get($entity->id);
            $planeHistoriesModel =  FactoryLocator::get('Table')->get('PlaneHistories');
            
            $planeHistory = $planeHistoriesModel->newEmptyEntity();
            
            $planeHistory->plane_id = $entity->id;

            $planeHistory->title = 'Plane '.$entity->plane_name.' was updated.';
            
            if(!empty($planes->modified)){
                $modified_from = str_replace('-', '/', $planes->modified);
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
            if($entity->plane_name != $planes->plane_name){
                $description .= 'Name was changed from "'.$planes->plane_name.'" to "'.$entity->plane_name.'".<br/>';
            }
            if($entity->plane_code != $planes->plane_code){
                $description .= 'Registration Code was changed from "'.$planes->plane_code.'" to "'.$entity->plane_code.'".<br/>';
            }
            if($entity->plane_serial_number != $planes->plane_serial_number){
                $description .= 'Serial Number was changed from "'.$planes->plane_serial_number.'" to "'.$entity->plane_serial_number.'".<br/>';
            }
            if($entity->plane_type != $planes->plane_type){
                $description .= 'Make & Model was changed from "'.$planes->plane_type.'" to "'.$entity->plane_type.'".<br/>';
            }
            if($entity->airworthiness_date != $planes->airworthiness_date){
                $description .= 'Airworthiness Date was changed from "'.$planes->airworthiness_date.'" to "'.$entity->airworthiness_date.'".<br/>';
            }
            if($entity->federal_aviation_regulation != $planes->federal_aviation_regulation){
                $description .= 'Schedule Revision Level was changed from "'.$planes->federal_aviation_regulation.'" to "'.$entity->federal_aviation_regulation.'".<br/>';
            }
            if($entity->hours != $planes->hours){
                $description .= 'Hours was changed from "'.$planes->hours.'" to "'.$entity->hours.'".<br/>';
            }
            if($entity->cycles != $planes->cycles){
                $description .= 'Cycles was changed from "'.$planes->cycles.'" to "'.$entity->cycles.'".<br/>';
            }
            if($entity->address != $planes->address){
                $description .= 'Address was changed from "'.$planes->address.'" to "'.$entity->address.'".<br/>';
            }
            if($entity->manufacturered_by != $planes->manufacturered_by){
                $description .= 'Manufactured By was changed from "'.$planes->manufacturered_by.'" to "'.$entity->manufacturered_by.'".<br/>';
            }
            if($entity->manufacturered_on != $planes->manufacturered_on){
                $description .= 'Manufactured On was changed from "'.$planes->manufacturered_on.'" to "'.$entity->manufacturered_on.'".<br/>';
            }
            if($entity->status != $planes->status){
                $description .= 'Status was changed from "'.$planes->status.'" to "'.$entity->status.'".<br/>';
            }
            if($entity->order_type != $planes->order_type){
                $description .= 'Order Type was changed from "'.$planes->order_type.'" to "'.$entity->order_type.'".<br/>';
            }
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $planeHistory->user_id = $sessionUser['id'];
                $planeHistory->description = $description;
                $planeHistoriesModel->save($planeHistory);
            }
        }

        return true;
    }

    //Change date format 'm-d-Y' to 'Y-m-d'
    public function dateFormatBeforeSave($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('Y-m-d H:i:s', strtotime($dateString));
    }

    public function timeFormatBeforeSave($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('H:i', strtotime($dateString));
    }
}
