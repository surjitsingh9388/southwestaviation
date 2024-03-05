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
class InventoriesTable extends Table
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
        $this->setTable('inventories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->Planes = TableRegistry::get('Planes');

        $this->belongsTo('InventoryItems', [
            'foreignKey' => 'inventory_item_id'
        ]);

        $this->belongsTo('InventoryLocations', [
            'foreignKey' => 'location_id'
        ]);

        $this->belongsTo('InventoryVendors', [
            'foreignKey' => 'vendor'
        ]);
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

        if(!empty($entity->expiration)) {
            $entity->expiration = $this->Planes->dateFormatBeforeSave($entity->expiration);
        }

        if(!empty($entity->received)) {
            $entity->received = $this->Planes->dateFormatBeforeSave($entity->received);
        }

        if(!empty($entity->warranty_expire)) {
            $entity->warranty_expire = $this->Planes->dateFormatBeforeSave($entity->warranty_expire);
        }

        if(!empty($entity->id)){
            $InventoryItemsModel = TableRegistry::get('InventoryItems');
            $inventoryitems = $InventoryItemsModel->get($entity->inventory_item_id);
            $InventoriesModel = TableRegistry::get('Inventories');
            $inventories = $InventoriesModel->get($entity->id);
            $InventoryHistoriesModel = TableRegistry::get('InventoryHistories');
            $InventoryTransactionHistoriesModel = TableRegistry::get('InventoryTransactionHistories');
            $currency = unserialize(CURRENCY);
            
            $inventoryHistory = $InventoryHistoriesModel->newEntity();
            $inventoryTransactionHistory = $InventoryTransactionHistoriesModel->newEntity();
            
            $inventoryHistory->inventory_id = $entity->id;

            $from_description = '';
            $to_description = '';
            $from_cost = '0';
            $to_cost = '0';
            
            $inventoryHistory->title = 'Physical Inventory '.$inventoryitems->part_number.' '.$entity->serial_no.' was updated.';
            
            if(!empty($inventories->modified)){
                $modified_from = str_replace('-', '/', $inventories->modified);
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

            $description = 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            if($entity->serial_no != $inventories->serial_no){
                $description .= 'Serial/Lot was changed from "'.$inventories->serial_no.'" to "'.$entity->serial_no.'".<br/>';
            }
            if($entity->display_name != $inventories->display_name){
                $description .= 'Display Name was changed from "'.$inventories->display_name.'" to "'.$entity->display_name.'".<br/>';
            }
            if($entity->cost != $inventories->cost){
                $description .= 'Cost was changed from "'.$inventories->cost.'" to "'.$entity->cost.'".<br/>';

                //transaction history
                $from_description = 'Price Changed From '.$currency[$inventories->currency].' '.$inventories->cost;
                $to_description = 'Price Changed To '.$currency[$entity->currency].' '.$entity->cost;
                $inventoryTransactionHistory->type = 'Price Change';
                $from_cost = $inventories->cost;
                $to_cost = $entity->cost;
            }
            if($entity->currency != $inventories->currency){
                $description .= 'Currency was changed from "'.$inventories->currency.'" to "'.$entity->currency.'".<br/>';
            }
            if($entity->exchange_cost != $inventories->exchange_cost){
                $description .= 'Exchange Cost was changed from "'.$inventories->exchange_cost.'" to "'.$entity->exchange_cost.'".<br/>';

                //transaction history
                $from_description = 'Exchange Cost Changed From '.$currency[$inventories->currency].' '.$inventories->exchange_cost;
                $to_description = 'Exchange Cost Changed To '.$currency[$entity->currency].' '.$entity->exchange_cost;
                $inventoryTransactionHistory->type = 'Exchange Price Change';
                
            }
            if($entity->capital_equipment != $inventories->capital_equipment){
                $description .= 'Capital Equipment was changed from "'.(!empty($inventories->capital_equipment) ? 'True' : 'False').'" to "'.(!empty($entity->capital_equipment) ? 'True' : 'False').'".<br/>';

                //transaction history
                $from_description = 'Capital Equipment Designation was '.(!empty($inventories->capital_equipment) ? 'True' : 'False');
                $to_description = 'Capital Equipment Designation changed to '.(!empty($entity->capital_equipment) ? 'True' : 'False');
                $inventoryTransactionHistory->type = 'Capital Change';
            }
            if($entity->safety_stock_threshold != $inventories->safety_stock_threshold){
                $description .= 'Safety Stock Threshold was changed from "'.$inventories->safety_stock_threshold.'" to "'.$entity->safety_stock_threshold.'".<br/>';
            }
            if($entity->uom != $inventories->uom){
                $description .= 'Default UOM was changed from "'.$inventories->uom.'" to "'.$entity->uom.'".<br/>';
            }
            if($entity->qty != $inventories->qty){
                $description .= 'Quantity was changed from "'.$inventories->qty.'" to "'.$entity->qty.'".<br/>';
            }
            if($entity->bar_code != $inventories->bar_code){
                $description .= 'Bar Code was changed from "'.$inventories->bar_code.'" to "'.$entity->bar_code.'".<br/>';
            }
            if($entity->account_code != $inventories->account_code){
                $description .= 'Account Code was changed from "'.$inventories->account_code.'" to "'.$entity->account_code.'".<br/>';
            }
            if($entity->vendor != $inventories->vendor){
                $description .= 'Vendor was changed from "'.$inventories->vendor.'" to "'.$entity->vendor.'".<br/>';
            }
            if($entity->revision != $inventories->revision){
                $description .= 'Revision was changed from "'.$inventories->revision.'" to "'.$entity->revision.'".<br/>';
            }
            if($entity->conditions != $inventories->conditions){
                $description .= 'Conditions was changed from "'.$inventories->conditions.'" to "'.$entity->conditions.'".<br/>';
            }
            if($entity->ata_chapter != $inventories->ata_chapter){
                $description .= 'ATA Chapter was changed from "'.$inventories->ata_chapter.'" to "'.$entity->ata_chapter.'".<br/>';
            }
            if(strtotime($entity->expiration) != strtotime($inventories->expiration)){
                if(!empty($inventories->expiration)){
                    $expiration_date_from = str_replace('-', '/', $inventories->expiration);
                    $expiration_date_from = date("l, F d, Y", strtotime($expiration_date_from));
                }else{
                    $expiration_date_from = '';
                }

                if(!empty($entity->expiration)){
                    $expiration_date_to = str_replace('-', '/', $entity->expiration);
                    $expiration_date_to = date("l, F d, Y", strtotime($expiration_date_to));
                }else{
                    $expiration_date_to = '';
                }

                $description .= 'Expiration was changed from "'.$expiration_date_from.'" to "'.$expiration_date_to.'".<br/>';
            }
            if(strtotime($entity->received) != strtotime($inventories->received)){
                if(!empty($inventories->received)){
                    $received_from = str_replace('-', '/', $inventories->received);
                    $received_from = date("l, F d, Y", strtotime($received_from));
                }else{
                    $received_from = '';
                }

                if(!empty($entity->received)){
                    $received_to = str_replace('-', '/', $entity->received);
                    $received_to = date("l, F d, Y", strtotime($received_to));
                }else{
                    $received_to = '';
                }
                $description .= 'Received was changed from "'.$received_from.'" to "'.$received_to.'".<br/>';
            }
            if($entity->tags != $inventories->tags){
                $description .= 'Tags was changed from "'.$inventories->tags.'" to "'.$entity->tags.'".<br/>';
            }
            if(strtotime($entity->warranty_expire) != strtotime($inventories->warranty_expire)){
                if(!empty($inventories->warranty_expire)){
                    $warranty_expire_from = str_replace('-', '/', $inventories->warranty_expire);
                    $warranty_expire_from = date("l, F d, Y", strtotime($warranty_expire_from));
                }else{
                    $warranty_expire_from = '';
                }

                if(!empty($entity->warranty_expire)){
                    $warranty_expire_to = str_replace('-', '/', $entity->warranty_expire);
                    $warranty_expire_to = date("l, F d, Y", strtotime($warranty_expire_to));
                }else{
                    $warranty_expire_to = '';
                }
                $description .= 'Warranty was changed from "'.$warranty_expire_from.'" to "'.$warranty_expire_to.'".<br/>';
            }
            if($entity->notes != $inventories->notes){
                $description .= 'Notes was changed from "'.$inventories->notes.'" to "'.$entity->notes.'".<br/>';
            }
            if($entity->months_new != $inventories->months_new){
                $description .= 'Months since new was changed from "'.$inventories->months_new.'" to "'.$entity->months_new.'".<br/>';
            }
            if($entity->months_overhaul != $inventories->months_overhaul){
                $description .= 'Months since overhaul was changed from "'.$inventories->months_overhaul.'" to "'.$entity->months_overhaul.'".<br/>';
            }
            if($entity->months_repair != $inventories->months_repair){
                $description .= 'Months since repair was changed from "'.$inventories->months_repair.'" to "'.$entity->months_repair.'".<br/>';
            }
            if($entity->hours_new != $inventories->hours_new){
                $description .= 'Hours since new was changed from "'.$inventories->hours_new.'" to "'.$entity->hours_new.'".<br/>';
            }
            if($entity->hours_overhaul != $inventories->hours_overhaul){
                $description .= 'Hours since overhaul was changed from "'.$inventories->hours_overhaul.'" to "'.$entity->hours_overhaul.'".<br/>';
            }
            if($entity->hours_repair != $inventories->hours_repair){
                $description .= 'Hours since repair was changed from "'.$inventories->hours_repair.'" to "'.$entity->hours_repair.'".<br/>';
            }
            if($entity->landings_new != $inventories->landings_new){
                $description .= 'Landings since new was changed from "'.$inventories->landings_new.'" to "'.$entity->landings_new.'".<br/>';
            }
            if($entity->landings_overhaul != $inventories->landings_overhaul){
                $description .= 'Landings since overhaul was changed from "'.$inventories->landings_overhaul.'" to "'.$entity->landings_overhaul.'".<br/>';
            }if($entity->landings_repair != $inventories->landings_repair){
                $description .= 'Landings since repair was changed from "'.$inventories->landings_repair.'" to "'.$entity->landings_repair.'".<br/>';
            }if($entity->cycles_new != $inventories->cycles_new){
                $description .= 'Cycles since new was changed from "'.$inventories->cycles_new.'" to "'.$entity->cycles_new.'".<br/>';
            }if($entity->cycles_overhaul != $inventories->cycles_overhaul){
                $description .= 'Cycles since overhaul was changed from "'.$inventories->cycles_overhaul.'" to "'.$entity->cycles_overhaul.'".<br/>';
            }if($entity->cycles_repair != $inventories->cycles_repair){
                $description .= 'Cycles since repair was changed from "'.$inventories->cycles_repair.'" to "'.$entity->cycles_repair.'".<br/>';
            }
            if($entity->exchange_price != $inventories->exchange_price){
                $description .= 'Exchange Price was changed from "'.$inventories->exchange_price.'" to "'.$entity->exchange_price.'".<br/>';
            }
            if($entity->retail_price != $inventories->retail_price){
                $description .= 'Retail Price was changed from "'.$inventories->retail_price.'" to "'.$entity->retail_price.'".<br/>';
            }
            if($entity->company_purchase_price != $inventories->company_purchase_price){
                $description .= 'Company Purchase Price was changed from "'.$inventories->company_purchase_price.'" to "'.$entity->company_purchase_price.'".<br/>';
            }
            if($entity->overhauled_cost != $inventories->overhauled_cost){
                $description .= 'Overhauled Cost was changed from "'.$inventories->overhauled_cost.'" to "'.$entity->overhauled_cost.'".<br/>';
            }
            
            if($entity->status != $inventories->status){
                $description .= 'Status was changed from "'.$inventories->status.'" to "'.$entity->status.'".<br/>';
            }

            $inventoryHistory->user_id = $entity->updated_by;
            $inventoryHistory->description = $description;
            $InventoryHistoriesModel->save($inventoryHistory);

            //transaction history data save
            if(!empty($from_description) || !empty($to_description)){
                $inventoryTransactionHistory->from_description = $from_description;
                $inventoryTransactionHistory->to_description = $to_description;
                $inventoryTransactionHistory->from_item_type = $inventoryitems->item_type;
                $inventoryTransactionHistory->to_item_type = $inventoryitems->item_type;
                $inventoryTransactionHistory->inventory_id = $entity->id;
                $inventoryTransactionHistory->from_status = $inventories->status;
                $inventoryTransactionHistory->to_status = $entity->status;
                $inventoryTransactionHistory->qty = $entity->qty;
                $inventoryTransactionHistory->uom = $entity->uom;
                $inventoryTransactionHistory->from_cost = $from_cost;
                $inventoryTransactionHistory->to_cost = $to_cost;
                $inventoryTransactionHistory->unit_cost = $entity->cost;
                $inventoryTransactionHistory->reason = $entity->reason;
                $inventoryTransactionHistory->tags = $entity->tags;
                $inventoryTransactionHistory->vendor_id = $entity->vendor_id;
                $inventoryTransactionHistory->account_code = $entity->account_code;
                $inventoryTransactionHistory->ata_chapter = $entity->ata_chapter;
                $inventoryTransactionHistory->added_by = $entity->updated_by;

                $InventoryTransactionHistoriesModel->save($inventoryTransactionHistory);
            }
        }
        
        return true;
    }
}
