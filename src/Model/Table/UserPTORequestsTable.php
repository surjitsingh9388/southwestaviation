<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\Datasource\FactoryLocator;

class UserPTORequestsTable extends Table
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

        $this->setTable('user_pto_requests');
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

        return $validator;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->id)){
            $userPTORequestsModel = FactoryLocator::get('Table')->get('UserPTORequests');
            $usersModel = FactoryLocator::get('Table')->get('Users');

            $userPTORequests = $userPTORequestsModel->get($entity->id);

            $users = $usersModel->get($entity->user_id);
            
            $userPtoRequestHistoriesModel = FactoryLocator::get('Table')->get('UserPtoRequestHistories');
            
            $userPtoRequestHistory = $userPtoRequestHistoriesModel->newEmptyEntity();
            
            $userPtoRequestHistory->pto_request_id = $entity->id;

            $userPtoRequestHistory->title = 'PTO Request of '.$users->full_name.' was updated.';
            
            if(!empty($userPTORequests->updated_at)){
                $modified_from = str_replace('-', '/', $userPTORequests->updated_at);
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
            if($entity->previous_balance != $userPTORequests->previous_balance){
                $description .= 'Previous Balance was changed from "'.$userPTORequests->previous_balance.'" to "'.$entity->previous_balance.'".<br/>';
            }
            if($entity->hours_used_gained != $userPTORequests->hours_used_gained){
                $description .= 'Hour Used/Gained was changed from "'.$userPTORequests->hours_used_gained.'" to "'.$entity->hours_used_gained.'".<br/>';
            }
            if($entity->new_balance != $userPTORequests->new_balance){
                $description .= 'New Balance was changed from  "'.$userPTORequests->new_balance.'" to "'.$entity->new_balance.'".<br/>';;
            }
            if($entity->pto_requests_status != $userPTORequests->pto_requests_status){
                $description .= 'Status was changed from  "'.$userPTORequests->pto_requests_status.'" to "'.$entity->pto_requests_status.'".<br/>';;
            }
            if($entity->is_first_paycheck != $userPTORequests->is_first_paycheck){
                $description .= 'Is First Pay Check was changed from "'.$userPTORequests->is_first_paycheck.'" to "'.$entity->is_first_paycheck.'".<br/>';
            }
            if($entity->is_pto_add != $userPTORequests->is_pto_add){
                $description .= 'Is PTO Add was changed from "'.$userPTORequests->is_pto_add.'" to "'.$entity->is_pto_add.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $userPtoRequestHistory->user_id = $entity->added_by;
                $userPtoRequestHistory->description = $description;
                $userPtoRequestHistoriesModel->save($userPtoRequestHistory);
            }

        }

        return true;
    }
    
}
