<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;

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
class InventoryItemsTable extends Table
{
    //use SoftDeleteTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('inventory_items');
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('part_number')
            ->maxLength('part_number', 100)
            ->requirePresence('part_number', 'create')
            ->notEmpty('part_number')
            ->add('part_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        /*$validator
            ->integer('modified')
            ->requirePresence('modified', 'create')
            ->notEmpty('modified');*/

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /*public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['part_number']));

        return $rules;
    }*/

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->date_received)) {
            $entity->date_received = $this->Planes->dateFormatBeforeSave($entity->date_received);
        }

        if(!empty($entity->warranty_expires)) {
            $entity->warranty_expires = $this->Planes->dateFormatBeforeSave($entity->warranty_expires);
        }

        if(!empty($entity->id)){
            $InventoryItemsModel = TableRegistry::get('InventoryItems');
            $inventoryitems = $InventoryItemsModel->get($entity->id);
            $InventoryItemHistoriesModel = TableRegistry::get('InventoryItemHistories');
            $InventoryTransactionHistoriesModel = TableRegistry::get('InventoryTransactionHistories');
            $InventoriesModel = TableRegistry::get('Inventories');
            
            $inventoryHistory = $InventoryItemHistoriesModel->newEntity();
            
            $inventoryHistory->inventory_item_id = $entity->id;

            $inventoryHistory->title = 'Inventory Item '.$entity->part_number.' was updated.';
            
            if(!empty($inventoryitems->modified)){
                $modified_from = str_replace('-', '/', $inventoryitems->modified);
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

            $from_description = '';
            $to_description = '';
            $itemtype_from = '';
            $itemtype_to = '';
            $transaction_type = '';
            $invItemType = unserialize(INVENTORY_ITEM_TYPE);

            $description = 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            if($entity->name != $inventoryitems->name){
                $description .= 'Name was changed from "'.$inventoryitems->name.'" to "'.$entity->name.'".<br/>';
            }
            if($entity->part_number != $inventoryitems->part_number){
                $description .= 'Part Number was changed from "'.$inventoryitems->part_number.'" to "'.$entity->part_number.'".<br/>';
            }
            if($entity->is_this_item_serialized != $inventoryitems->is_this_item_serialized){
                $description .= 'Is this item serialized was changed from "'.(!empty($inventoryitems->is_this_item_serialized) ? 'True' : 'False').'" to "'.(!empty($entity->is_this_item_serialized) ? 'True' : 'False').'".<br/>';
            }
            if($entity->accept_install != $inventoryitems->accept_install){
                $description .= 'Accepts Install was changed from "'.(!empty($inventoryitems->accept_install) ? 'True' : 'False').'" to "'.(!empty($entity->accept_install) ? 'True' : 'False').'".<br/>';
            }
            if($entity->item_type != $inventoryitems->item_type){
                $description .= 'Item Type was changed from "'.$inventoryitems->item_type.'" to "'.$entity->item_type.'".<br/>';

                //transaction history
                $from_description = 'Inventory Part Type was '.(!empty($inventoryitems->item_type) ? $invItemType[$inventoryitems->item_type] : '0');
                $to_description = 'Inventory Part Type changed to '.(!empty($entity->item_type) ? $invItemType[$entity->item_type] : '0');
                $transaction_type = 'Part Type Change';
                $itemtype_from = $inventoryitems->item_type;
                $itemtype_to = $entity->item_type;
            }
            if($entity->capital_equipment != $inventoryitems->capital_equipment){
                $description .= 'Is capital was changed from "'.(!empty($inventoryitems->capital_equipment) ? 'True' : 'False').'" to "'.(!empty($entity->capital_equipment) ? 'True' : 'False').'".<br/>';

                //transaction history
                $from_description = 'Capital Equipment Designation was '.(!empty($inventoryitems->capital_equipment) ? 'True' : 'False');
                $to_description = 'Capital Equipment Designation changed to '.(!empty($entity->capital_equipment) ? 'True' : 'False');
                $transaction_type = 'Capital Change';
            }
            if($entity->safety_stock_threshold != $inventoryitems->safety_stock_threshold){
                $description .= 'Safety Stock Threshold was changed from "'.$inventoryitems->safety_stock_threshold.'" to "'.$entity->safety_stock_threshold.'".<br/>';
            }
            if($entity->default_uom != $inventoryitems->default_uom){
                $description .= 'Default UOM was changed from "'.$inventoryitems->default_uom.'" to "'.$entity->default_uom.'".<br/>';
            }
            if($entity->unit_cost != $inventoryitems->unit_cost){
                $description .= 'Out Right Cost was changed from "'.$inventoryitems->unit_cost.'" to "'.$entity->unit_cost.'".<br/>';
            }
            if($entity->currency != $inventoryitems->currency){
                $description .= 'Currency was changed from "'.$inventoryitems->currency.'" to "'.$entity->currency.'".<br/>';
            }
            if($entity->exchange_cost != $inventoryitems->exchange_cost){
                $description .= 'Exchange Cost was changed from "'.$inventoryitems->exchange_cost.'" to "'.$entity->exchange_cost.'".<br/>';
            }
            if($entity->rev != $inventoryitems->rev){
                $description .= 'Rev was changed from "'.$inventoryitems->rev.'" to "'.$entity->rev.'".<br/>';
            }
            if($entity->manufacturer_id != $inventoryitems->manufacturer_id){
                $description .= 'Manufacturer was changed from "'.$inventoryitems->manufacturer_id.'" to "'.$entity->manufacturer_id.'".<br/>';
            }
            if($entity->description != $inventoryitems->description){
                $description .= 'Description was changed from "'.$inventoryitems->description.'" to "'.$entity->description.'".<br/>';
            }
            if($entity->notes != $inventoryitems->notes){
                $description .= 'Notes was changed from "'.$inventoryitems->notes.'" to "'.$entity->notes.'".<br/>';
            }
            if($entity->tags != $inventoryitems->tags){
                $description .= 'Tags was changed from "'.$inventoryitems->tags.'" to "'.$entity->tags.'".<br/>';
            }
            if($entity->alternate_part_number != $inventoryitems->alternate_part_number){
                $description .= 'Alternate Part Numbers was changed from "'.$inventoryitems->alternate_part_number.'" to "'.$entity->alternate_part_number.'".<br/>';
            }
            if($entity->exchange_price != $inventoryitems->exchange_price){
                $description .= 'Exchange Price was changed from "'.$inventoryitems->exchange_price.'" to "'.$entity->exchange_price.'".<br/>';
            }
            if($entity->retail_price != $inventoryitems->retail_price){
                $description .= 'Retail Price was changed from "'.$inventoryitems->retail_price.'" to "'.$entity->retail_price.'".<br/>';
            }
            if($entity->company_purchase_price != $inventoryitems->company_purchase_price){
                $description .= 'Company Purchase Price was changed from "'.$inventoryitems->company_purchase_price.'" to "'.$entity->company_purchase_price.'".<br/>';
            }
            if($entity->overhauled_cost != $inventoryitems->overhauled_cost){
                $description .= 'Overhauled Cost was changed from "'.$inventoryitems->overhauled_cost.'" to "'.$entity->overhauled_cost.'".<br/>';
            }

            $inventoryHistory->user_id = $entity->updated_by;
            $inventoryHistory->description = $description;
            $InventoryItemHistoriesModel->save($inventoryHistory);

            //transaction history data save
            if(!empty($from_description) || !empty($to_description)){
                $inventoriesarr = $InventoriesModel->find('all')->where(['inventory_item_id'=>$entity->id])->select($InventoriesModel);
                if($inventoriesarr->count() > 0){
                    foreach($inventoriesarr as $inventoriesval){
                        $inventoryTransactionHistory = $InventoryTransactionHistoriesModel->newEntity();
                        if(empty($itemtype_from)){
                            $itemtype_from = $entity->item_type;
                            $itemtype_to = $entity->item_type;
                        }

                        $inventoryTransactionHistory->from_description = $from_description;
                        $inventoryTransactionHistory->to_description = $to_description;
                        $inventoryTransactionHistory->type = $transaction_type;
                        $inventoryTransactionHistory->from_item_type = $itemtype_from;
                        $inventoryTransactionHistory->to_item_type = $itemtype_to;
                        $inventoryTransactionHistory->inventory_id = $inventoriesval['id'];
                        $inventoryTransactionHistory->qty = $inventoriesval['qty'];
                        $inventoryTransactionHistory->uom = $inventoriesval['uom'];
                        $inventoryTransactionHistory->unit_cost = $inventoriesval['cost'];
                        $inventoryTransactionHistory->reason = $inventoriesval['reason'];
                        $inventoryTransactionHistory->tags = $inventoriesval['tags'];
                        $inventoryTransactionHistory->vendor_id = $inventoriesval['vendor_id'];
                        $inventoryTransactionHistory->account_code = $inventoriesval['account_code'];
                        $inventoryTransactionHistory->ata_chapter = $inventoriesval['ata_chapter'];
                        $inventoryTransactionHistory->added_by = $entity->updated_by;

                        $InventoryTransactionHistoriesModel->save($inventoryTransactionHistory);
                    }
                }
            }
        }

        return true;
    }
}
