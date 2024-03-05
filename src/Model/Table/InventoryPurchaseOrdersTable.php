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
class InventoryPurchaseOrdersTable extends Table
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

        $this->setTable('inventory_purchase_orders');
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
            ->scalar('po_number')
            ->maxLength('po_number', 255)
            ->requirePresence('po_number', 'create')
            ->notEmpty('po_number')
            ->add('po_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['po_number']));

        return $rules;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->po_date)) {
            $entity->po_date = $this->Planes->dateFormatBeforeSave($entity->po_date);
        }
        
        if(!empty($entity->id)){
            $InventoryPurchaseOrdersModel = TableRegistry::get('InventoryPurchaseOrders');
            $inventorypurchaseorders = $InventoryPurchaseOrdersModel->get($entity->id);
            $InventoryPurchaseOrderHistoriesModel = TableRegistry::get('InventoryPurchaseOrderHistories');
            
            $inventoryPurchaseOrderHistory = $InventoryPurchaseOrderHistoriesModel->newEntity();
            
            $inventoryPurchaseOrderHistory->inventory_purchase_order_id = $entity->id;

            $inventoryPurchaseOrderHistory->title = 'Purchase Order '.$entity->po_number.' was updated.';
            
            if(!empty($inventorypurchaseorders->modified)){
                $modified_from = str_replace('-', '/', $inventorypurchaseorders->modified);
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
            if($entity->po_number != $inventorypurchaseorders->po_number){
                $description .= 'Purchase Number was changed from "'.$inventorypurchaseorders->po_number.'" to "'.$entity->po_number.'".<br/>';
            }
            if($entity->po_type != $inventorypurchaseorders->po_type){
                $description .= 'Type was changed from "'.$inventorypurchaseorders->po_type.'" to "'.$entity->po_type.'".<br/>';
            }
            if($entity->currency != $inventorypurchaseorders->currency){
                $description .= 'Currency was changed from "'.$inventorypurchaseorders->currency.'" to "'.$entity->currency.'".<br/>';
            }
            if($entity->bill_to_address != $inventorypurchaseorders->bill_to_address){
                $description .= 'Bill to was changed from "'.$inventorypurchaseorders->bill_to_address.'" to "'.$entity->bill_to_address.'".<br/>';
            }
            if($entity->ship_to_address != $inventorypurchaseorders->ship_to_address){
                $description .= 'Ship to was changed from "'.$inventorypurchaseorders->ship_to_address.'" to "'.$entity->ship_to_address.'".<br/>';
            }
            if($entity->account_code != $inventorypurchaseorders->account_code){
                $description .= 'Account Code was changed from "'.$inventorypurchaseorders->account_code.'" to "'.$entity->account_code.'".<br/>';
            }
            if($entity->requestor != $inventorypurchaseorders->requestor){
                $description .= 'Requestor was changed from "'.$inventorypurchaseorders->requestor.'" to "'.$entity->requestor.'".<br/>';
            }
            if($entity->vendor != $inventorypurchaseorders->vendor){
                $description .= 'Vendor was changed from "'.$inventorypurchaseorders->vendor.'" to "'.$entity->vendor.'".<br/>';
            }
            if($entity->reference != $inventorypurchaseorders->reference){
                $description .= 'Reference was changed from "'.$inventorypurchaseorders->reference.'" to "'.$entity->reference.'".<br/>';
            }
            if($entity->sales_person != $inventorypurchaseorders->sales_person){
                $description .= 'Sales Person was changed from "'.$inventorypurchaseorders->sales_person.'" to "'.$entity->sales_person.'".<br/>';
            }
            if($entity->ship_via != $inventorypurchaseorders->ship_via){
                $description .= 'Ship via was changed from "'.$inventorypurchaseorders->ship_via.'" to "'.$entity->ship_via.'".<br/>';
            }
            if($entity->special_instructions != $inventorypurchaseorders->special_instructions){
                $description .= 'Special instruction was changed from "'.$inventorypurchaseorders->special_instructions.'" to "'.$entity->special_instructions.'".<br/>';
            }
            if($entity->po_status != $inventorypurchaseorders->po_status){
                $description .= 'Status was changed from "'.$inventorypurchaseorders->po_status.'" to "'.$entity->po_status.'".<br/>';
            }
            if($entity->exchange_status != $inventorypurchaseorders->exchange_status){
                $description .= 'Exchange Status was changed from "'.$inventorypurchaseorders->exchange_status.'" to "'.$entity->exchange_status.'".<br/>';
            }
            if($entity->exchange_note != $inventorypurchaseorders->exchange_note){
                $description .= 'Exchange note was changed from "'.$inventorypurchaseorders->exchange_note.'" to "'.$entity->exchange_note.'".<br/>';
            }
            if($entity->request != $inventorypurchaseorders->request){
                $description .= 'Request was changed from "'.$inventorypurchaseorders->request.'" to "'.$entity->request.'".<br/>';
            }
            if(strtotime($entity->po_date) != strtotime($inventorypurchaseorders->po_date)){
                if(!empty($inventorypurchaseorders->po_date)){
                    $po_date_from = str_replace('-', '/', $inventorypurchaseorders->po_date);
                    $po_date_from = date("l, F d, Y", strtotime($po_date_from));
                }else{
                    $po_date_from = '';
                }

                if(!empty($entity->po_date)){
                    $po_date_to = str_replace('-', '/', $entity->po_date);
                    $po_date_to = date("l, F d, Y", strtotime($po_date_to));
                }else{
                    $po_date_to = '';
                }
                $description .= 'PO Date was changed from "'.$po_date_from.'" to "'.$po_date_to.'".<br/>';
            }

            $inventoryPurchaseOrderHistory->user_id = $entity->updated_by;
            $inventoryPurchaseOrderHistory->description = $description;
            $InventoryPurchaseOrderHistoriesModel->save($inventoryPurchaseOrderHistory);
        }

        return true;
    }
}
