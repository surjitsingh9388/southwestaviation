<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Mailer;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Dashboard Controller
 */
class UserDepartmentsController extends AppController
{   
    protected \App\Model\Table\UserDepartmentsTable $UserDepartments;

    public function initialize():void {
        $this->UserDepartments = $this->fetchTable('UserDepartments');
        
        parent::initialize();
    }
    
    public function index() {
        $actionItems = '';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('User Department', $actionStatus))
            {
                $actionItems = $actionStatus['Home'];
            }
        }
        
        $userdepartmentlist = $this->getUserDepartments();

        $this->set(compact('actionItems', 'userdepartmentlist'));
    }

    public function saveUserDepartments(){
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }else{
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
                //echo "<pre>";print_r($postData);exit;

                $authUserData = $this->Authentication->getResult()->getData();

                $department_name = $postData['department_name'];
                if(!empty($department_name)){
                    $userdepartments = $this->UserDepartments->newEmptyEntity();    
                    if(!empty($postData['user_department_id'])){
                        $userdepartments = $this->UserDepartments->get($postData['user_department_id']);
                    }
                    $postData['added_by'] = $authUserData['id'];
                    $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                    $userdepartments = $this->UserDepartments->patchEntity($userdepartments, $postData);
                    if ($this->UserDepartments->save($userdepartments)) {
                        $tblhtml = $this->getUserDepartmentsHTML();

                        $response = ['status'=>'success', 'message'=>'Department/Title saved successfully', 'tblhtml'=>$tblhtml];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }else{
                $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                echo json_encode($response);die;
            }
        }
    }

    public function getUserDepartments($department_id = ''){
        $userdepartmentlist = $this->UserDepartments->find('all', ['order'=>'UserDepartments.department_name'])
                                            ->select($this->UserDepartments)
                                            ->select(['users.full_name'])
                                            ->join([
                                                'users'=>[
                                                    'table'=>'users',
                                                    'type'=>'INNER',
                                                    'conditions'=>'users.id = UserDepartments.added_by'
                                                ]
                                            ]);

        if(!empty($department_id)){
            $userdepartmentlist = $userdepartmentlist->where(['UserDepartments.id'=>$department_id])->first();
        }

        return $userdepartmentlist;
    }

    public function getUserDepartmentsHTML(){
        $userdepartmentlist = $this->getUserDepartments();
        $tblhtml = '';
        foreach($userdepartmentlist as $departments){
            $tblhtml .= '<tr>';
            $tblhtml .= '<td>'.$departments['department_name'].'</td>';
            $tblhtml .= '<td>'.$departments['users']['full_name'].'</td>';
            $tblhtml .= '<td>'.date('m/d/Y', strtotime($departments['created_at'])).'</td>';
            $tblhtml .= '<td>
                <button type="button" class="btn btn-default user_department_edit" data-val="'.$departments['id'].'">Edit</button>
                <button type="button" class="btn btn-default user_department_delete" data-val="'.$departments['id'].'">Delete</button>
            </td>';
            $tblhtml .= '</tr>';
        }

        return $tblhtml;
    }

    public function deleteUserDepartments()
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('User Department', $actionStatus))
            {
                $actionItems = $actionStatus['User Department'];
            }
        }
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $postData = $this->request->getData();

            $this->request->allowMethod(['post', 'delete']);
            $user_department_id = $postData['user_department_id'];
            try {
                if ($this->UserDepartments->deleteAll(['id'=>$user_department_id])) {
                    $tblhtml = $this->getUserDepartmentsHTML();

                    $response = ['status'=>'success', 'message'=>'Department/Title deleted successfully', 'tblhtml'=>$tblhtml];
                    echo json_encode($response);die;
                } else {
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            } catch (\Exception $e) {
                $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                echo json_encode($response);die;
            }
        }else{
            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
            echo json_encode($response);die;
        }
    }
}
?>