<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

class CustomerAircraftWOItemToolsTable extends Table
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
        $this->setTable('customer_aircraft_wo_item_tools');
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

        if (!empty($entity->date_labeled)) {
            $entity->date_labeled = $service->dateFormatBeforeSave($entity->date_labeled);
        }

        if(!empty($entity->id)){
            $this->CustomerAircraftWOItemTools =  FactoryLocator::get('Table')->get('CustomerAircraftWOItemTools');
            $woitemtools = $this->CustomerAircraftWOItemTools->get($entity->id);
            $AircraftWOItemHistoriesModel =  FactoryLocator::get('Table')->get('AircraftWOItemHistories');
            
            $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
            
            $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;

            $AircraftWOItemHistories->title = 'Item Tool was updated.';
            
            if(!empty($woitemtools->updated_at)){
                $modified_from = str_replace('-', '/', $woitemtools->updated_at);
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
            if($entity->equipment_description != $woitemtools->equipment_description){
                $description .= 'Equipment Description was changed from "'.$woitemtools->equipment_description.'" to "'.$entity->equipment_description.'".<br/>';
            }
            if($entity->model_no != $woitemtools->model_no){
                $description .= 'Model No. was changed from "'.$woitemtools->model_no.'" to "'.$entity->model_no.'".<br/>';
            }
            
            if($entity->serial_no != $woitemtools->serial_no){
                $description .= 'Serial No. was changed from "'.$woitemtools->serial_no.'" to "'.$entity->serial_no.'".<br/>';
            }
            if($entity->date_labeled != $woitemtools->date_labeled){
                $description .= 'Date Labeled was changed from "'.$woitemtools->date_labeled.'" to "'.$entity->date_labeled.'".<br/>';
            }
            if($entity->certification != $woitemtools->certification){
                $description .= 'Certification was changed from "'.$woitemtools->certification.'" to "'.$entity->certification.'".<br/>';
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
