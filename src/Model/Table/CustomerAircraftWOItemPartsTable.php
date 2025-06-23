<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

/**
 * CustomerAircraftWOItemParts Model
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
class CustomerAircraftWOItemPartsTable extends Table
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
        $this->setTable('customer_aircraft_wo_item_parts');
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
            ->allowEmptyString('id');
        
        $validator
            ->integer('wo_item_id')
            ->notEmptyString('wo_item_id');
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if (!empty($entity->date_needed)) {
            $entity->date_needed = $service->dateFormatBeforeSave($entity->date_needed);
        }

        if (!empty($entity->date_received)) {
            $entity->date_received = $service->dateFormatBeforeSave($entity->date_received);
        }
        
        if (!empty($entity->warranty_expires)) {
            $entity->warranty_expires = $service->dateFormatBeforeSave($entity->warranty_expires);
        }

        if(!empty($entity->id)){
            $this->CustomerAircraftWOItemParts =  FactoryLocator::get('Table')->get('CustomerAircraftWOItemParts');
            $woitemparts = $this->CustomerAircraftWOItemParts->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Item Part was updated.';
            
            if(!empty($woitemparts->updated_at)){
                $modified_from = str_replace('-', '/', $woitemparts->updated_at);
                $modified_from = date("Y-m-d h:i A", strtotime($modified_from));
            }else{
                $modified_from = '';
            }

            if(!empty($entity->updated_at)){
                $modified_to = str_replace('-', '/', $entity->updated_at);
                $modified_to = date("Y-m-d h:i A", strtotime($modified_to));
            }else{
                $modified_to = '';
            }

            $description = '';
            
            if($entity->part_number != $woitemparts->part_number){
                $description .= 'Part No was changed from "'.$woitemparts->part_number.'" to "'.$entity->part_number.'".<br/>';
            }
            if($entity->superseding_part_number != $woitemparts->superseding_part_number){
                $description .= 'Superseding Part Number was changed from "'.$woitemparts->superseding_part_number.'" to "'.$entity->superseding_part_number.'".<br/>';
            }
            if($entity->part_description != $woitemparts->part_description){
                $description .= 'Description was changed from "'.$woitemparts->part_description.'" to "'.$entity->part_description.'".<br/>';
            }
            if($entity->qty_needed != $woitemparts->qty_needed){
                $description .= 'Qty Needed was changed from "'.$woitemparts->qty_needed.'" to "'.$entity->qty_needed.'".<br/>';
            }
            if($entity->qty_stock != $woitemparts->qty_stock){
                $description .= 'Qty Stk was changed from "'.$woitemparts->qty_stock.'" to "'.$entity->qty_stock.'".<br/>';
            }
            if($entity->qty_cust_owned != $woitemparts->qty_cust_owned){
                $description .= 'Qty (Cust. Owned) was changed from "'.$woitemparts->qty_cust_owned.'" to "'.$entity->qty_cust_owned.'".<br/>';
            }
            if($entity->price_each != $woitemparts->price_each){
                $description .= 'Price Each was changed from "'.$woitemparts->price_each.'" to "'.$entity->price_each.'".<br/>';
            }
            if($entity->give_discount_percentage != $woitemparts->give_discount_percentage){
                $description .= 'Give Discount was changed from "'.$woitemparts->give_discount_percentage.'" to "'.$entity->give_discount_percentage.'".<br/>';
            }
            if($entity->part_taxable != $woitemparts->part_taxable){
                $description .= 'Taxable was changed from "'.$woitemparts->part_taxable.'" to "'.$entity->part_taxable.'".<br/>';
            }
            if($entity->not_deduct_from_stock != $woitemparts->not_deduct_from_stock){
                $description .= 'Do not deduct from stock was changed from "'.$woitemparts->not_deduct_from_stock.'" to "'.$entity->not_deduct_from_stock.'".<br/>';
            }
            if($entity->is_loaner != $woitemparts->is_loaner){
                $description .= 'Is Loaner was changed from "'.$woitemparts->is_loaner.'" to "'.$entity->is_loaner.'".<br/>';
            }
            if($entity->part_conditions != $woitemparts->part_conditions){
                $description .= 'Condition was changed from "'.$woitemparts->part_conditions.'" to "'.$entity->part_conditions.'".<br/>';
            }
            if($entity->serial_number != $woitemparts->serial_number){
                $description .= 'Serial Number was changed from "'.$woitemparts->serial_number.'" to "'.$entity->serial_number.'".<br/>';
            }
            if($entity->date_needed != $woitemparts->date_needed){
                $description .= 'Date Needed was changed from "'.$woitemparts->date_needed.'" to "'.$entity->date_needed.'".<br/>';
            }
            if($entity->part_ship_in != $woitemparts->part_ship_in){
                $description .= 'Ship In was changed from "'.$woitemparts->part_ship_in.'" to "'.$entity->part_ship_in.'".<br/>';
            }
            if($entity->part_ship_out != $woitemparts->part_ship_out){
                $description .= 'Ship Out was changed from "'.$woitemparts->part_ship_out.'" to "'.$entity->part_ship_out.'".<br/>';
            }
            
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';

                $AircraftWOItemHistories->user_id = $entity->updated_by;
                $AircraftWOItemHistories->description = $description;
                $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
            }
        }

        return true;
    }
    
}
