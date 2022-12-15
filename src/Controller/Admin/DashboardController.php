<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

/**
 * Dashboard Controller
 */
class DashboardController extends AppController
{   
    public function initialize() {
        parent::initialize();
        $this->loadComponent('User');
    }
    
    public function index() {
        $actionItems = '';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Home', $actionStatus))
            {
                $actionItems = $actionStatus['Home'];
            }
        }

        $dashboardData = array();       
        $this->set(compact('dashboardData', 'actionItems'));
    }

    public function getEmailContent()
    {
        $this->layout = 'ajax';
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $emailQueuesAuditsModel = TableRegistry::get('EmailQueuesAudits');
            $emailQueueData = $emailQueuesAuditsModel->find()->where(['DATE(created) >= ' => $postData['startDate'], 'DATE(created) <= ' => $postData['endDate']])->order(['id DESC'])->toArray();
            $this->set(compact('emailQueueData'));
        }
    }
    

    /**
     * displayEmail method
     *
     * @param array $id.
     * @return return json
     */    
    public function displayEmail() {
        $this->layout = 'ajax';
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $emailsModel = TableRegistry::get('EmailQueuesAudits');
            $emailsData = $emailsModel->find()
                ->select(['id', 'email_type', 'email_data'])
                ->where(['id' => $postData['id']])
                ->first();
            if (!empty($emailsData)) {
                if ($emailsData['email_type'] == 'User_Welcome') {
                    $emailTemplate = 'welcome_user_email';
                } else if ($emailsData['email_type'] == 'Forgot_Password'){
                    $emailTemplate = 'forgot_password';
                } else if ($emailsData['email_type'] == 'Register_Now'){
                    $emailTemplate = 'registerN_now';
                } else if ($emailsData['email_type'] == 'Contact_Us'){
                    $emailTemplate = 'contact_us';
                }
                $data = json_decode($emailsData['email_data'], true);
                $this->set('data', $data);
                $this->render('/Email/html/'.$emailTemplate);
            }
        }
    }

    public function saveEmailQueue($id)
    {
        $emailsAuditModel = TableRegistry::get('EmailQueuesAudits');
        $emailsAuditData = $emailsAuditModel->find()
                // ->select(['id', 'email_type', 'email_data'])
                ->where(['id' => $id])
                ->first();
        // $emailsAuditData = (array)$emailsAuditData;
        if (!empty($emailsAuditData)) {
            $addData['email_type'] = $emailsAuditData->email_type;
            $addData['email_data'] = $emailsAuditData->email_data;
            $addData['status'] = 0;
            $addData['updated_by'] = $emailsAuditData->updated_by;
            $emailsModel = TableRegistry::get('EmailQueues');
            $emailEntity = $emailsModel->newEntity();
            $emailEntity = $emailsModel->patchEntity($emailEntity, $addData);
            if ($emailsModel->save($emailEntity)) {
                $this->Flash->success(__('Email has been sent successfully.'));
            }else{
                $this->Flash->error(__('Unable to send email.'));
            }
        }else{
            $this->Flash->error(__('No record found.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}