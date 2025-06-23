<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;

class AircraftWOItemServiceLogsTable extends Table
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
        $this->setTable('aircraft_wo_item_service_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp'); 

        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        if(!empty($entity->id)){
            $AircraftWOItemServiceLogs =  FactoryLocator::get('Table')->get('AircraftWOItemServiceLogs');
            $woitemservices = $AircraftWOItemServiceLogs->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Service was updated.';
            
            if(!empty($woitemservices->updated_at)){
                $modified_from = str_replace('-', '/', $woitemservices->updated_at);
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

            $technicianBillingStyle = unserialize(TECHNICIANBILLINGSTYLE);

            $description = '';

            if($entity->login_time != $woitemservices->login_time){
                $description .= 'Login Time was changed from "'.$woitemservices->login_time.'" to "'.$entity->login_time.'".<br/>';
            }
            if($entity->logout_time != $woitemservices->logout_time){
                $description .= 'Logout Time was changed from "'.$woitemservices->logout_time.'" to "'.$entity->logout_time.'".<br/>';
            }
            
            if($entity->currently_on_overtime != $woitemservices->currently_on_overtime){
                $description .= 'Currently on Overtime was changed from "'.$woitemservices->currently_on_overtime.'" to "'.$entity->currently_on_overtime.'".<br/>';
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
