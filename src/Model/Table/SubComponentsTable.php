<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Http\Session;
use Cake\Datasource\FactoryLocator;

/**
 * SubComponents Model
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
class SubComponentsTable extends Table
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
        $this->setTable('sub_components');
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
        
        if(!empty($entity->id)){
            //$planeModel =  FactoryLocator::get('Table')->get('Planes');
            //$airframeComponentModel =  FactoryLocator::get('Table')->get('AirframeComponents');

            $subComponentModel =  FactoryLocator::get('Table')->get('SubComponents');
            $subComponent = $subComponentModel->get($entity->id);
            $subComponentHistoriesModel =  FactoryLocator::get('Table')->get('SubComponentHistories');
            
            $subComponentHistory = $subComponentHistoriesModel->newEmptyEntity();
            $subComponentHistory->plane_id = $entity->plane_id;
            $subComponentHistory->sub_component_id = $entity->id;

            //$planeData = $planeModel->get($entity->plane_id);
            //$airframeCompData = $airframeComponentModel->get($entity->airframe_component_id);

            $subComponentHistory->title = 'Sub Component '.$subComponent->title.' was updated.';
            
            if(!empty($subComponent->modified)){
                $modified_from = str_replace('-', '/', $subComponent->modified);
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
            if($entity->plane_id != $subComponent->plane_id){
                $description .= 'Aircraft was changed from "'.$subComponent->plane_id.'" to "'.$entity->plane_id.'".<br/>';
            }
            if($entity->airframe_component_id != $subComponent->airframe_component_id){
                $description .= 'Aircraft Component was changed from "'.$subComponent->airframe_component_id.'" to "'.$entity->airframe_component_id.'".<br/>';
            }
            if($entity->parent_id != $subComponent->parent_id){
                $description .= 'Sub Component was changed from "'.$subComponent->parent_id.'" to "'.$entity->parent_id.'".<br/>';
            }
            if($entity->title != $subComponent->title){
                $description .= 'Title was changed from "'.$subComponent->title.'" to "'.$entity->title.'".<br/>';
            }
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $subComponentHistory->user_id = $sessionUser['id'];
                $subComponentHistory->description = $description;
                $subComponentHistoriesModel->save($subComponentHistory);
            }
        }

        return true;
    }

}
