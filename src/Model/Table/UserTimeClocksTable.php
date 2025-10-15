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

class UserTimeClocksTable extends Table
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

        $this->setTable('user_time_clocks');
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
        $validator->requirePresence('id', 'create');

        return $validator;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if(!empty($entity->in_time)) {
            $entity->in_time = $service->dateFormatBeforeSave($entity->in_time);
        }

        if(!empty($entity->out_time)) {
            $entity->out_time = $service->dateFormatBeforeSave($entity->out_time);
        }

        if(!empty($entity->id)){
            $userTimeClocksModel = FactoryLocator::get('Table')->get('UserTimeClocks');            
            $userTimeClocks = $userTimeClocksModel->get($entity->id);

            $usersModel = FactoryLocator::get('Table')->get('Users');            
            $users = $usersModel->get($entity->user_id);

            if(!empty($userTimeClocks->in_time)) {
                $userTimeClocks->in_time = $service->dateFormatBeforeSave($userTimeClocks->in_time);
            }

            if(!empty($userTimeClocks->out_time)) {
                $userTimeClocks->out_time = $service->dateFormatBeforeSave($userTimeClocks->out_time);
            }

            $userTimeClockHistoriesModel = FactoryLocator::get('Table')->get('UserTimeClockHistories');
            
            $userTimeClockHistory = $userTimeClockHistoriesModel->newEmptyEntity();
            
            $userTimeClockHistory->user_time_clock_id = $entity->id;

            $userTimeClockHistory->title = 'User Time Clock for '.$users->full_name.' was updated.';
            
            if(!empty($userTimeClocks->updated_at)){
                $modified_from = str_replace('-', '/', $userTimeClocks->updated_at);
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
            if($entity->in_time != $userTimeClocks->in_time){
                $description .= 'In Time was changed from "'.$userTimeClocks->in_time.'" to "'.$entity->in_time.'".<br/>';
            }
            if($entity->out_time != $userTimeClocks->out_time){
                $description .= 'Out Time was changed from "'.$userTimeClocks->out_time.'" to "'.$entity->out_time.'".<br/>';
            }
            if($entity->status != $userTimeClocks->status){
                $description .= 'Status was changed from "'.$userTimeClocks->status.'" to "'.$entity->status.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $userTimeClockHistory->user_id = $entity->updated_by;
                $userTimeClockHistory->description = $description;

                $userTimeClockHistoriesModel->save($userTimeClockHistory);
            }

        }

        return true;
    }
    
}
