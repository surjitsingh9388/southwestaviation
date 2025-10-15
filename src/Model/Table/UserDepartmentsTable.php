<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\Datasource\FactoryLocator;

class UserDepartmentsTable extends Table
{
    public function initialize(array $config):void
    {
        parent::initialize($config);

        $this->setTable('user_departments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        ////$this->Planes = FactoryLocator::get('Table')->get('Planes');
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
            $userDepartmentsModel = FactoryLocator::get('Table')->get('UserDepartments');            
            $userDepartments = $userDepartmentsModel->get($entity->id);

            $userDepartmentHistoriesModel = FactoryLocator::get('Table')->get('UserDepartmentHistories');
            
            $userDepartmentHistory = $userDepartmentHistoriesModel->newEmptyEntity();
            
            $userDepartmentHistory->user_department_id = $entity->id;

            $userDepartmentHistory->title = 'User Department '.$userDepartments->department_name.' was updated.';
            
            if(!empty($userDepartments->updated_at)){
                $modified_from = str_replace('-', '/', $userDepartments->updated_at);
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
            if($entity->department_name != $userDepartments->department_name){
                $description .= 'Department Name was changed from "'.$userDepartments->department_name.'" to "'.$entity->department_name.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $userDepartmentHistory->user_id = $entity->updated_by;
                $userDepartmentHistory->description = $description;
                $userDepartmentHistoriesModel->save($userDepartmentHistory);
            }

        }

        return true;
    }
    
}
