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
 * AirframeComponents Model
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
class AirframeComponentsTable extends Table
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
        $this->setTable('airframe_components');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Planes', [
            'foreignKey' => 'plane_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('AirframeComponentCategories', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('AirframeComponentTimes', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('SubComponents', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('AirframeComponentParts', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('AirframeComponentLastCW', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('AddDiscrepancies', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('PartInstalledTimes', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('Utilizations', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('Groups', [
            'foreignKey' => 'airframe_component_id'
        ]);

        /*$this->hasMany('ExtraDetails', [
            'foreignKey' => 'airframe_component_id'
        ]);*/
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
            ->requirePresence('plane_id', 'create')
            ->notEmptyString('plane_id');
        
        $validator
            ->scalar('log_book')
            ->maxLength('log_book', 100)
            ->notEmptyString('log_book');

        $validator
            ->integer('position')
            ->allowEmptyString('position');

        return $validator;
    }
    
    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->id)){
            $planeModel =  FactoryLocator::get('Table')->get('Planes');
            $airframeComponentModel =  FactoryLocator::get('Table')->get('AirframeComponents');
            $airframeComponent = $airframeComponentModel->get($entity->id);
            $airframeComponentHistoriesModel =  FactoryLocator::get('Table')->get('AirframeComponentHistories');
            
            $airframeComponentHistory = $airframeComponentHistoriesModel->newEmptyEntity();
            $airframeComponentHistory->plane_id = $entity->plane_id;
            $airframeComponentHistory->airframe_component_id = $entity->id;

            $planeData = $planeModel->get($entity->plane_id);
            $airframeComponentHistory->title = 'Airframe Component Aircraft '.$planeData->plane_name.' was updated.';
            
            if(!empty($airframeComponent->modified)){
                $modified_from = str_replace('-', '/', $airframeComponent->modified);
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
            if($entity->plane_id != $airframeComponent->plane_id){
                $description .= 'Aircraft was changed from "'.$airframeComponent->plane_id.'" to "'.$entity->plane_id.'".<br/>';
            }
            if($entity->log_book != $airframeComponent->log_book){
                $description .= 'Log Book was changed from "'.$airframeComponent->log_book.'" to "'.$entity->log_book.'".<br/>';
            }
            if($entity->position != $airframeComponent->position){
                $description .= 'Position was changed from "'.$airframeComponent->position.'" to "'.$entity->position.'".<br/>';
            }
            if($entity->description != $airframeComponent->description){
                $description .= 'Model was changed from "'.$airframeComponent->description.'" to "'.$entity->description.'".<br/>';
            }
            if($entity->serial_no != $airframeComponent->serial_no){
                $description .= 'Serial No was changed from "'.$airframeComponent->serial_no.'" to "'.$entity->serial_no.'".<br/>';
            }
            
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';

                $session = new Session();
                $sessionUser = $session->read('Auth');
                $airframeComponentHistory->user_id = $sessionUser['id'];
                $airframeComponentHistory->description = $description;
                $airframeComponentHistoriesModel->save($airframeComponentHistory);
            }
        }

        return true;
    }
}
