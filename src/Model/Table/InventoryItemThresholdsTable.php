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
class InventoryItemThresholdsTable extends Table
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

        $this->setTable('inventory_item_thresholds');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        //$this->Planes = FactoryLocator::get('Table')->get('Planes');

        $this->belongsTo('InventoryLocations', [
            'foreignKey' => 'location_id'
        ]);
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

        $InventoryLocationsModel =  FactoryLocator::get('Table')->get('InventoryLocations');
        $inventorylocations = $InventoryLocationsModel->get($entity->location_id);
        $InventoryItemHistoriesModel =  FactoryLocator::get('Table')->get('InventoryItemHistories');

        $inventoryHistory = $InventoryItemHistoriesModel->newEmptyEntity();
        
        $inventoryHistory->inventory_item_id = $entity->inventory_item_id;
        
        if(!empty($entity->id)){
            $InventoryItemThresholdsModel =  FactoryLocator::get('Table')->get('InventoryItemThresholds');
            $inventoryitemthresholds = $InventoryItemThresholdsModel->get($entity->id);

            $InventoryItemsModel =  FactoryLocator::get('Table')->get('InventoryItems');
            $inventoryitems = $InventoryItemsModel->get($entity->inventory_item_id);

            $inventoryHistory->title = $inventorylocations->location_name.' Threshold Updated';
            
            $description = 'Last updated was changed from "'.$inventoryitems->modified.'" to "'.$entity->modified.'".<br/>';
            
            if($entity->safety_stock_threshold != $inventoryitemthresholds->safety_stock_threshold){
                $description .= 'Safety Stock Threshold was changed from "'.$inventoryitemthresholds->safety_stock_threshold.'" to "'.$entity->safety_stock_threshold.'".<br/>';
            }
        }else{
            $inventoryHistory->title = $inventorylocations->location_name.' Threshold Created';
            
            $description = 'Safety stock threshold="'.$entity->safety_stock_threshold.'".<br/>';
        }

        $inventoryHistory->user_id = $entity->updated_by;
        $inventoryHistory->description = $description;
        $InventoryItemHistoriesModel->save($inventoryHistory);

        return true;
    }
}
