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
class InventoryRepairOrdersTable extends Table
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

        $this->setTable('inventory_repair_orders');
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
            ->scalar('ro_number')
            ->maxLength('ro_number', 255)
            ->requirePresence('ro_number', 'create')
            ->notEmpty('ro_number')
            ->add('ro_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['ro_number']));

        return $rules;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->ro_date)) {
            $entity->ro_date = $this->Planes->dateFormatBeforeSave($entity->ro_date);
        }

        if(!empty($entity->id)){
            $InventoryRepairOrdersModel = TableRegistry::get('InventoryRepairOrders');
            $inventoryrepairorders = $InventoryRepairOrdersModel->get($entity->id);
            $InventoryRepairOrderHistoriesModel = TableRegistry::get('InventoryRepairOrderHistories');
            
            $inventoryRepairOrderHistory = $InventoryRepairOrderHistoriesModel->newEntity();
            
            $inventoryRepairOrderHistory->inventory_repair_order_id = $entity->id;

            $inventoryRepairOrderHistory->title = 'Repair Order '.$entity->ro_number.' was updated.';
            
            if(!empty($inventoryrepairorders->modified)){
                $modified_from = str_replace('-', '/', $inventoryrepairorders->modified);
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
            if($entity->ro_number != $inventoryrepairorders->ro_number){
                $description .= 'Repair Number was changed from "'.$inventoryrepairorders->ro_number.'" to "'.$entity->ro_number.'".<br/>';
            }
            if($entity->currency != $inventoryrepairorders->currency){
                $description .= 'Currency was changed from "'.$inventoryrepairorders->currency.'" to "'.$entity->currency.'".<br/>';
            }
            if($entity->bill_to_address != $inventoryrepairorders->bill_to_address){
                $description .= 'Bill to was changed from "'.$inventoryrepairorders->bill_to_address.'" to "'.$entity->bill_to_address.'".<br/>';
            }
            if($entity->ship_to_address != $inventoryrepairorders->ship_to_address){
                $description .= 'Ship to was changed from "'.$inventoryrepairorders->ship_to_address.'" to "'.$entity->ship_to_address.'".<br/>';
            }
            if($entity->account_code != $inventoryrepairorders->account_code){
                $description .= 'Account Code was changed from "'.$inventoryrepairorders->account_code.'" to "'.$entity->account_code.'".<br/>';
            }
            if($entity->requestor != $inventoryrepairorders->requestor){
                $description .= 'Requestor was changed from "'.$inventoryrepairorders->requestor.'" to "'.$entity->requestor.'".<br/>';
            }
            if($entity->vendor != $inventoryrepairorders->vendor){
                $description .= 'Vendor was changed from "'.$inventoryrepairorders->vendor.'" to "'.$entity->vendor.'".<br/>';
            }
            if($entity->reference != $inventoryrepairorders->reference){
                $description .= 'Reference was changed from "'.$inventoryrepairorders->reference.'" to "'.$entity->reference.'".<br/>';
            }
            if($entity->contact != $inventoryrepairorders->contact){
                $description .= 'Contact was changed from "'.$inventoryrepairorders->contact.'" to "'.$entity->contact.'".<br/>';
            }
            if($entity->ship_via != $inventoryrepairorders->ship_via){
                $description .= 'Ship via was changed from "'.$inventoryrepairorders->ship_via.'" to "'.$entity->ship_via.'".<br/>';
            }
            if($entity->special_instructions != $inventoryrepairorders->special_instructions){
                $description .= 'Special instruction was changed from "'.$inventoryrepairorders->special_instructions.'" to "'.$entity->special_instructions.'".<br/>';
            }
            if($entity->ro_status != $inventoryrepairorders->ro_status){
                $description .= 'Status was changed from "'.$inventoryrepairorders->ro_status.'" to "'.$entity->ro_status.'".<br/>';
            }
            if(strtotime($entity->ro_date) != strtotime($inventoryrepairorders->ro_date)){
                if(!empty($inventoryrepairorders->ro_date)){
                    $ro_date_from = str_replace('-', '/', $inventoryrepairorders->ro_date);
                    $ro_date_from = date("l, F d, Y", strtotime($ro_date_from));
                }else{
                    $ro_date_from = '';
                }

                if(!empty($entity->ro_date)){
                    $ro_date_to = str_replace('-', '/', $entity->ro_date);
                    $ro_date_to = date("l, F d, Y", strtotime($ro_date_to));
                }else{
                    $ro_date_to = '';
                }
                $description .= 'RO Date was changed from "'.$ro_date_from.'" to "'.$ro_date_to.'".<br/>';
            }

            $inventoryRepairOrderHistory->user_id = $entity->updated_by;
            $inventoryRepairOrderHistory->description = $description;
            $InventoryRepairOrderHistoriesModel->save($inventoryRepairOrderHistory);
        }

        return true;
    }
}
