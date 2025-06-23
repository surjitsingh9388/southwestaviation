<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

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
class InventoryShippingOrdersTable extends Table
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

        $this->setTable('inventory_shipping_orders');
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
            ->scalar('shipping_order_number')
            ->maxLength('shipping_order_number', 255)
            ->requirePresence('shipping_order_number', 'create')
            ->notEmptyString('shipping_order_number')
            ->add('shipping_order_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['shipping_order_number']));

        return $rules;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if(!empty($entity->shipping_order_date)) {
            $entity->shipping_order_date = $service->dateFormatBeforeSave($entity->shipping_order_date);
        }

        if(!empty($entity->id)){
            $InventoryShippingOrdersModel =  FactoryLocator::get('Table')->get('InventoryShippingOrders');
            $inventoryshippingorders = $InventoryShippingOrdersModel->get($entity->id);
            $InventoryShippingOrderHistoriesModel =  FactoryLocator::get('Table')->get('InventoryShippingOrderHistories');
            
            $inventoryShippingOrderHistory = $InventoryShippingOrderHistoriesModel->newEmptyEntity();
            
            $inventoryShippingOrderHistory->inventory_shipping_order_id = $entity->id;

            $inventoryShippingOrderHistory->title = 'Shipping Order '.$entity->shipping_order_number.' was updated.';
            
            if(!empty($inventoryshippingorders->modified)){
                $modified_from = str_replace('-', '/', $inventoryshippingorders->modified);
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
            if($entity->shipping_order_number != $inventoryshippingorders->shipping_order_number){
                $description .= 'Shipping Order Number was changed from "'.$inventoryshippingorders->shipping_order_number.'" to "'.$entity->shipping_order_number.'".<br/>';
            }
            if($entity->currency != $inventoryshippingorders->currency){
                $description .= 'Currency was changed from "'.$inventoryshippingorders->currency.'" to "'.$entity->currency.'".<br/>';
            }
            if($entity->from_address != $inventoryshippingorders->from_address){
                $description .= 'Ship From was changed from "'.$inventoryshippingorders->from_address.'" to "'.$entity->from_address.'".<br/>';
            }
            if($entity->to_address != $inventoryshippingorders->to_address){
                $description .= 'Ship to was changed from "'.$inventoryshippingorders->to_address.'" to "'.$entity->to_address.'".<br/>';
            }
            if($entity->account_code != $inventoryshippingorders->account_code){
                $description .= 'Account Code was changed from "'.$inventoryshippingorders->account_code.'" to "'.$entity->account_code.'".<br/>';
            }
            if($entity->requestor != $inventoryshippingorders->requestor){
                $description .= 'Requestor was changed from "'.$inventoryshippingorders->requestor.'" to "'.$entity->requestor.'".<br/>';
            }
            if($entity->vendor != $inventoryshippingorders->vendor){
                $description .= 'Vendor was changed from "'.$inventoryshippingorders->vendor.'" to "'.$entity->vendor.'".<br/>';
            }
            if($entity->reference != $inventoryshippingorders->reference){
                $description .= 'Reference was changed from "'.$inventoryshippingorders->reference.'" to "'.$entity->reference.'".<br/>';
            }
            if($entity->destination != $inventoryshippingorders->destination){
                $description .= 'Shipping address type id was changed from "'.$inventoryshippingorders->destination.'" to "'.$entity->destination.'".<br/>';
            }
            if($entity->ship_via != $inventoryshippingorders->ship_via){
                $description .= 'Ship via was changed from "'.$inventoryshippingorders->ship_via.'" to "'.$entity->ship_via.'".<br/>';
            }
            if($entity->special_instructions != $inventoryshippingorders->special_instructions){
                $description .= 'Special instruction was changed from "'.$inventoryshippingorders->special_instructions.'" to "'.$entity->special_instructions.'".<br/>';
            }
            if($entity->attention != $inventoryshippingorders->attention){
                $description .= 'Attention was changed from "'.$inventoryshippingorders->attention.'" to "'.$entity->attention.'".<br/>';
            }
            if($entity->shipper != $inventoryshippingorders->shipper){
                $description .= 'Shipper was changed from "'.$inventoryshippingorders->shipper.'" to "'.$entity->shipper.'".<br/>';
            }
            if($entity->description != $inventoryshippingorders->description){
                $description .= 'Description was changed from "'.$inventoryshippingorders->description.'" to "'.$entity->description.'".<br/>';
            }
            if($entity->shipping_order_status != $inventoryshippingorders->shipping_order_status){
                $description .= 'Shipping order was changed from "'.$inventoryshippingorders->shipping_order_status.'" to "'.$entity->shipping_order_status.'".<br/>';
            }
            if($entity->status != $inventoryshippingorders->status){
                $description .= 'Shipping order was changed from "'.$inventoryshippingorders->status.'" to "'.$entity->status.'".<br/>';
            }
            if(strtotime($entity->shipping_order_date) != strtotime($inventoryshippingorders->shipping_order_date)){
                if(!empty($inventoryshippingorders->shipping_order_date)){
                    $shipping_order_date_from = str_replace('-', '/', $inventoryshippingorders->shipping_order_date);
                    $shipping_order_date_from = date("l, F d, Y", strtotime($shipping_order_date_from));
                }else{
                    $shipping_order_date_from = '';
                }

                if(!empty($entity->shipping_order_date)){
                    $shipping_order_date_to = str_replace('-', '/', $entity->shipping_order_date);
                    $shipping_order_date_to = date("l, F d, Y", strtotime($shipping_order_date_to));
                }else{
                    $shipping_order_date_to = '';
                }
                $description .= 'Order Date was changed from "'.$shipping_order_date_from.'" to "'.$shipping_order_date_to.'".<br/>';
            }
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            }
            
            $inventoryShippingOrderHistory->user_id = $entity->updated_by;
            $inventoryShippingOrderHistory->description = $description;
            $InventoryShippingOrderHistoriesModel->save($inventoryShippingOrderHistory);
        }

        return true;
    }
}
