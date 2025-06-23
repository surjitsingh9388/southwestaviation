<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\Datasource\FactoryLocator;

/**
 * Parts Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Parts get($primaryKey, $options = [])
 * @method \App\Model\Entity\Parts newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Parts[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Parts|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Parts patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Parts[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Parts findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InventoryLocationsTable extends Table
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

        $this->setTable('inventory_locations');
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
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('location_name')
            ->maxLength('location_name', 100)
            ->requirePresence('location_name', 'create')
            ->notEmptyString('location_name')
            ->add('location_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        /*$validator
            ->integer('modified')
            ->requirePresence('modified', 'create')
            ->notEmptyString('modified');*/

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /*public function buildRules(RulesChecker $rules):RulesChecker
    {
        $rules->add($rules->isUnique(['part_number']));

        return $rules;
    }*/
    
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->id)){
            $InventoryLocationsModel =  FactoryLocator::get('Table')->get('InventoryLocations');
            $inventorylocations = $InventoryLocationsModel->get($entity->id);
            $InventoryLocationHistoriesModel =  FactoryLocator::get('Table')->get('InventoryLocationHistories');
            
            $inventoryLocationHistory = $InventoryLocationHistoriesModel->newEmptyEntity();
            
            $inventoryLocationHistory->inventory_location_id = $entity->id;

            $inventoryLocationHistory->title = 'Location '.$entity->location_name.' was updated.';
            
            if(!empty($inventorylocations->modified)){
                $modified_from = str_replace('-', '/', $inventorylocations->modified);
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
            if($entity->parent_location_id != $inventorylocations->parent_location_id){
                $description .= 'Parent id was changed from "'.$inventorylocations->parent_location_id.'" to "'.$entity->parent_location_id.'".<br/>';
            }
            if($entity->location_name != $inventorylocations->location_name){
                $description .= 'Name was changed from "'.$inventorylocations->location_name.'" to "'.$entity->location_name.'".<br/>';
            }
            if($entity->location_status != $inventorylocations->location_status){
                $description .= 'Status was changed from "'.$inventorylocations->location_status.'" to "'.$entity->location_status.'".<br/>';
            }
            if($entity->description != $inventorylocations->description){
                $description .= 'Description was changed from "'.$inventorylocations->description.'" to "'.$entity->description.'".<br/>';
            }
            if($entity->bar_code != $inventorylocations->bar_code){
                $description .= 'Bar Code was changed from "'.$inventorylocations->bar_code.'" to "'.$entity->bar_code.'".<br/>';
            }
            if($entity->status != $inventorylocations->status){
                $description .= 'Status was changed from "'.$inventorylocations->status.'" to "'.$entity->status.'".<br/>';
            }
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            }

            if(!empty($description)){
                $inventoryLocationHistory->user_id = $entity->updated_by;
                $inventoryLocationHistory->description = $description;
                $InventoryLocationHistoriesModel->save($inventoryLocationHistory);
            }
        }

        return true;
    }
}
