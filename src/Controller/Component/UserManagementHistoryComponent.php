<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class UserManagementHistoryComponent extends Component {
    public array $components = ['Authentication.Authentication'];

    protected \App\Model\Table\UserHistoriesTable $UserHistories;
    //protected \App\Model\Table\InventoryHistoriesTable $InventoryHistories;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    
    public function saveUsersHistory($entity){
        $userHistoriesModel = $this->getController()->fetchTable('UserHistories');

        $authUserData = $this->Authentication->getResult()->getData();

        $userHistories = $userHistoriesModel->newEmptyEntity();
        $userHistories->user_tbl_id = $entity->id;
        
        $userHistories->title = 'User '.$entity->full_name.' was created.';
        $description = serialize($entity);

        $userHistories->user_id = $authUserData['id'];
        $userHistories->description = $description;

        $userHistoriesModel->save($userHistories);
    }

    public function saveUserPTORequestHistory($entity){
        $userPtoRequestHistoriesModel = $this->getController()->fetchTable('UserPtoRequestHistories');

        $authUserData = $this->Authentication->getResult()->getData();

        $usersModel = $this->getController()->fetchTable('Users');

        $users = $usersModel->get($entity->user_id);

        $userPtoRequestHistories = $userPtoRequestHistoriesModel->newEmptyEntity();
        $userPtoRequestHistories->pto_request_id = $entity->id;
        
        $userPtoRequestHistories->title = 'PTO Request of '.$users->full_name.' was created.';
        $description = serialize($entity);

        $userPtoRequestHistories->user_id = $authUserData['id'];
        $userPtoRequestHistories->description = $description;

        $userPtoRequestHistoriesModel->save($userPtoRequestHistories);
    }

    public function saveRoleHistory($entity){
        $roleHistoriesModel = $this->getController()->fetchTable('RoleHistories');

        $authUserData = $this->Authentication->getResult()->getData();

        $userPtoRequestHistories = $roleHistoriesModel->newEmptyEntity();
        $userPtoRequestHistories->role_id = $entity->id;
        
        $userPtoRequestHistories->title = 'Role '.$entity->role_name.' was created.';
        $description = serialize($entity);
        
        $userPtoRequestHistories->user_id = $authUserData['id'];
        $userPtoRequestHistories->description = $description;

        $roleHistoriesModel->save($userPtoRequestHistories);
    }

    public function saveUserTimeClockHistory($entity){
        $userTimeClockHistoriesModel = $this->getController()->fetchTable('UserTimeClockHistories');
        $usersModel = $this->getController()->fetchTable('Users');

        $users = $usersModel->get($entity->user_id);

        $authUserData = $this->Authentication->getResult()->getData();

        $userTimeClockHistories = $userTimeClockHistoriesModel->newEmptyEntity();
        $userTimeClockHistories->user_time_clock_id = $entity->id;
        
        $userTimeClockHistories->title = 'User Time Clock for '.$users->full_name.' was created.';
        $description = serialize($entity);
        
        $userTimeClockHistories->user_id = $authUserData['id'];
        $userTimeClockHistories->description = $description;

        $userTimeClockHistoriesModel->save($userTimeClockHistories);
    }

    public function saveUserDepartmentHistory($entity){
        $userDepartmentHistoriesModel = $this->getController()->fetchTable('UserDepartmentHistories');
        
        $authUserData = $this->Authentication->getResult()->getData();

        $userDepartmentHistories = $userDepartmentHistoriesModel->newEmptyEntity();
        $userDepartmentHistories->user_department_id = $entity->id;
        
        $userDepartmentHistories->title = 'User Department '.$entity->department_name.' was created.';
        $description = serialize($entity);
        
        $userDepartmentHistories->user_id = $authUserData['id'];
        $userDepartmentHistories->description = $description;
        
        $userDepartmentHistoriesModel->save($userDepartmentHistories);
    }

    public function saveTechnicalPublicationHistory($entity){
        $technicalPublicationHistoriesModel = $this->getController()->fetchTable('TechnicalPublicationHistories');
        
        $authUserData = $this->Authentication->getResult()->getData();

        $technicalPublicationHistories = $technicalPublicationHistoriesModel->newEmptyEntity();
        $technicalPublicationHistories->technical_publication_id = $entity->id;

        $main_folder_name = 'SWAS';
        if($entity->main_page_id == '2'){
            $main_folder_name = 'BVAC';
        }else if($entity->main_page_id == '3'){
            $main_folder_name = 'RJC';
        }
        
        $technicalPublicationHistories->title = 'Technical Publication '.$main_folder_name.' was created.';
        $description = serialize($entity);
        
        $technicalPublicationHistories->user_id = $authUserData['id'];
        $technicalPublicationHistories->description = $description;
        
        $technicalPublicationHistoriesModel->save($technicalPublicationHistories);
    }

}

?>