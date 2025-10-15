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

class UserPTORequestLogsTable extends Table
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

        $this->setTable('user_pto_request_logs');
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
        $service = new AppService();

        if(!empty($entity->date_of_day)) {
            $entity->date_of_day = $service->dateFormatBeforeSave($entity->date_of_day);
        }
        
        if(!empty($entity->in_time)) {
            $entity->in_time = $service->dateFormatBeforeSave($entity->in_time);
        }

        if(!empty($entity->out_time)) {
            $entity->out_time = $service->dateFormatBeforeSave($entity->out_time);
        }

        if(!empty($entity->id)){
            $userPTORequestsModel = FactoryLocator::get('Table')->get('UserPTORequests');
            $userPTORequestLogsModel = FactoryLocator::get('Table')->get('UserPTORequestLogs');
            $usersModel = FactoryLocator::get('Table')->get('Users');

            $userPTORequests = $userPTORequestsModel->get($entity->pto_request_id);
            $userPTORequestLogs = $userPTORequestLogsModel->get($entity->id);
            
            if(!empty($userPTORequestLogs->date_of_day)) {
                $userPTORequestLogs->date_of_day = $service->dateFormatBeforeSave($userPTORequestLogs->date_of_day);
            }
            
            if(!empty($userPTORequestLogs->in_time)) {
                $userPTORequestLogs->in_time = $service->dateFormatBeforeSave($userPTORequestLogs->in_time);
            }

            if(!empty($userPTORequestLogs->out_time)) {
                $userPTORequestLogs->out_time = $service->dateFormatBeforeSave($userPTORequestLogs->out_time);
            }

            $users = $usersModel->get($userPTORequests->user_id);
            
            $userPtoRequestHistoriesModel = FactoryLocator::get('Table')->get('UserPtoRequestHistories');
            
            $userPtoRequestHistory = $userPtoRequestHistoriesModel->newEmptyEntity();
            
            $userPtoRequestHistory->pto_request_id = $entity->id;

            $userPtoRequestHistory->title = 'PTO Request of '.$users->full_name.' was updated.';
            
            if(!empty($userPTORequestLogs->updated_at)){
                $modified_from = str_replace('-', '/', $userPTORequestLogs->updated_at);
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
            if($entity->day_of_week != $userPTORequestLogs->day_of_week){
                $description .= 'Day of the week was changed from "'.$userPTORequestLogs->day_of_week.'" to "'.$entity->day_of_week.'".<br/>';
            }
            if($entity->date_of_day != $userPTORequestLogs->date_of_day){
                $description .= 'Date was changed from "'.$userPTORequestLogs->date_of_day.'" to "'.$entity->date_of_day.'".<br/>';
            }
            if($entity->time_from != $userPTORequestLogs->time_from){
                $description .= 'Time From was changed from  "'.$userPTORequestLogs->time_from.'" to "'.$entity->time_from.'".<br/>';;
            }
            if($entity->time_to != $userPTORequestLogs->time_to){
                $description .= 'Time To was changed from  "'.$userPTORequestLogs->time_to.'" to "'.$entity->time_to.'".<br/>';;
            }
            if($entity->pto_to_use != $userPTORequestLogs->pto_to_use){
                $description .= 'PTO Hrs. to Use was changed from "'.$userPTORequestLogs->pto_to_use.'" to "'.$entity->pto_to_use.'".<br/>';
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
