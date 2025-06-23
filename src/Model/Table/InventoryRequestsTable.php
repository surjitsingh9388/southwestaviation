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
class InventoryRequestsTable extends Table
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

        $this->setTable('inventory_requests');
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
            ->scalar('request_number')
            ->maxLength('request_number', 100)
            ->requirePresence('request_number', 'create')
            ->notEmptyString('request_number')
            ->add('request_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
    public function buildRules(RulesChecker $rules):RulesChecker
    {
        $rules->add($rules->isUnique(['request_number']));

        return $rules;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if(!empty($entity->need_by)) {
            $entity->need_by = $service->dateFormatBeforeSave($entity->need_by);
        }

        if(!empty($entity->id)){
            $InventoryRequestsModel =  FactoryLocator::get('Table')->get('InventoryRequests');
            $inventoryrequests = $InventoryRequestsModel->get($entity->id);
            $InventoryRequestHistoriesModel =  FactoryLocator::get('Table')->get('InventoryRequestHistories');
            
            $inventoryRequestHistory = $InventoryRequestHistoriesModel->newEmptyEntity();
            
            $inventoryRequestHistory->inventory_request_id = $entity->id;

            $inventoryRequestHistory->title = 'Inventory request '.$entity->request_number.' was updated.';
            
            if(!empty($inventoryrequests->modified)){
                $modified_from = str_replace('-', '/', $inventoryrequests->modified);
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
            if($entity->request_number != $inventoryrequests->request_number){
                $description .= 'Request Number was changed from "'.$inventoryrequests->request_number.'" to "'.$entity->request_number.'".<br/>';
            }
            if($entity->title != $inventoryrequests->title){
                $description .= 'Title was changed from "'.$inventoryrequests->title.'" to "'.$entity->title.'".<br/>';
            }
            if($entity->description != $inventoryrequests->description){
                $description .= 'Description was changed from "'.$inventoryrequests->description.'" to "'.$entity->description.'".<br/>';
            }
            if($entity->requested_by != $inventoryrequests->requested_by){
                $description .= 'Requested By was changed from "'.$inventoryrequests->requested_by.'" to "'.$entity->requested_by.'".<br/>';
            }
            if(strtotime($entity->need_by) != strtotime($inventoryrequests->need_by)){
                if(!empty($inventoryrequests->need_by)){
                    $need_by_from = str_replace('-', '/', $inventoryrequests->need_by);
                    $need_by_from = date("l, F d, Y", strtotime($need_by_from));
                }else{
                    $need_by_from = '';
                }

                if(!empty($entity->need_by)){
                    $need_by_to = str_replace('-', '/', $entity->need_by);
                    $need_by_to = date("l, F d, Y", strtotime($need_by_to));
                }else{
                    $need_by_to = '';
                }
                $description .= 'Need By was changed from "'.$need_by_from.'" to "'.$need_by_to.'".<br/>';
            }
            if($entity->urgency != $inventoryrequests->urgency){
                $description .= 'Urgency was changed from "'.$inventoryrequests->urgency.'" to "'.$entity->urgency.'".<br/>';
            }
            if($entity->request_status != $inventoryrequests->request_status){
                $description .= 'Status was changed from "'.$inventoryrequests->request_status.'" to "'.$entity->request_status.'".<br/>';
            }
            if($entity->status != $inventoryrequests->status){
                $description .= 'Status was changed from "'.$inventoryrequests->status.'" to "'.$entity->status.'".<br/>';
            }
            if($entity->comment != $inventoryrequests->comment){
                $description .= 'Comment was changed from "'.$inventoryrequests->comment.'" to "'.$entity->comment.'".<br/>';
            }
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            }

            $inventoryRequestHistory->user_id = $entity->updated_by;
            $inventoryRequestHistory->description = $description;
            $InventoryRequestHistoriesModel->save($inventoryRequestHistory);
        }

        return true;
    }
}
