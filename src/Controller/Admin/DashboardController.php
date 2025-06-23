<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Mailer;
use App\View\Helper\DashboardHelper;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Datasource\FactoryLocator;
use Cake\I18n\FrozenTime;

/**
 * Dashboard Controller
 */
class DashboardController extends AppController
{   
    public $helpers = array('Session');
    protected \App\Model\Table\EventsTable $Events;
    protected \App\Model\Table\NewsFeedsTable $NewsFeeds;
    protected \App\Model\Table\UserPTORequestsTable $UserPTORequests;
    protected \App\Model\Table\EmailQueuesAuditsTable $EmailQueuesAudits;
    protected \App\Model\Table\EmailQueuesTable $EmailQueues;

    public function initialize(): void {
        $this->Events = $this->fetchTable('Events');
        $this->NewsFeeds = $this->fetchTable('NewsFeeds');
        $this->UserPTORequests = $this->fetchTable('UserPTORequests');

        $this->loadComponent('User');
        $this->loadComponent('CustomerOTC');
        $this->loadComponent('UserTimeClock');
        $this->loadComponent('DashboardComp');
        $this->loadComponent('PTORequests');
        
        parent::initialize();
    }
    
    public function index() {
        $actionItems = '';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Home', $actionStatus))
            {
                $actionItems = $actionStatus['Home'];
            }
        }
        $date_to = date("Y-m-d");
        $date_from = date('Y-m-d', strtotime('-7 days'));
        $user_id = $authUserData['id'];

        //$aircraftoptiondata = $this->CustomerOTC->getAircraftOptionDataWO();
        $usertimeclocklist = $this->UserTimeClock->getTimeClockDetailByUser($date_from, $date_to, $user_id);
        $filter = strtotime(date('Y-m'));
        $sessionUser = $this->request->getSession()->read('Auth');
        $user_role = $sessionUser['role_id'];
        
        $dashboardevents = $this->DashboardComp->getSelectedMonthEventList($date_to);
        $dashboardnewsfeed = $this->DashboardComp->getCurrentNewsFeed();
        
        $dashboardMenuItems = $this->fetchTable('UserMenuItems')->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1'])->toArray();

        $ptorequestlist = $this->fetchTable('UserPTORequests')->find('all', ['order'=>'id desc'])->where(['pto_requests_status'=>'2', 'user_id'=>$user_id])->limit(4);

        $this->set(compact('actionItems', 'usertimeclocklist', 'filter', 'user_role', 'dashboardevents', 'dashboardnewsfeed', 'dashboardMenuItems', 'user_id', 'ptorequestlist', 'sessionUser'));
    }

    public function getEmailContent()
    {
        $this->viewBuilder()->setLayout('ajax');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $emailQueuesAuditsModel = $this->fetchTable('EmailQueuesAudits');
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
        $this->viewBuilder()->setLayout('ajax');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $emailsModel = $this->fetchTable('EmailQueuesAudits');
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
        $emailsAuditModel = $this->fetchTable('EmailQueuesAudits');
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
            $emailsModel = $this->fetchTable('EmailQueues');
            $emailEntity = $emailsModel->newEmptyEntity();
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

    public function getPrevNextEventCalender(){
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }else{
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
                $filter = !isset($postData['month']) ? strtotime(date('Y-m')) : strtotime($postData['month']);
                $date_from = !isset($postData['month']) ? date('Y-m-01') : date('Y-m-01', strtotime($postData['month']));
                
                $dashboardevents = $this->DashboardComp->getSelectedMonthEventList($date_from);
                $this->set(compact('filter', 'dashboardevents'));

                $this->viewBuilder()->setLayout('ajax');
                $this->render('/element/Dashboard/event_calender');
            }else{
                echo 'Failed';die;
            }
        }
    }

    public function fetchDashboardPopup(){
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }else{
            $postData = $this->request->getData();
            $section = $postData['section'];
            $authUserData = $this->Authentication->getResult()->getData();
            $user_id = $authUserData['id'];
            $sessionUser = $this->request->getSession()->read('Auth');
            $user_role = $sessionUser['role_id'];

            $dashboardEvents = $this->fetchTable('Events');

            $fileName = '/element/Dashboard/';

            $this->set(compact('sessionUser'));
            if($section == 'dashboard_event_list'){
                $fileName .= 'dashboard_event_list';
                
                $dashboardeventlist = $this->DashboardComp->getEventList();
                
                $dashboardMenuItems = $this->fetchTable('UserMenuItems')->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1', 'UserMenuItems.menu_item_id'=>'68'])->first();

                $this->set(compact('dashboardeventlist', 'dashboardMenuItems', 'user_id', 'user_role'));
            }else if($section == 'dashboard_event_add'){
                $fileName .= 'dashboard_event_add';
                if(!empty($postData['dashboard_event_id'])){
                    $dashboardevent = $dashboardEvents->get($postData['dashboard_event_id']);
                }else{
                    $dashboardevent = $dashboardEvents->newEmptyEntity();
                }

                $dashboardMenuItems = $this->fetchTable('UserMenuItems')->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1', 'UserMenuItems.menu_item_id'=>'68'])->first();

                $this->set(compact('dashboardevent', 'dashboardMenuItems', 'user_id', 'user_role'));
            }else if($section == 'news_feed_list'){
                $fileName .= 'news_feed_list';
                
                $newsfeedlist = $this->DashboardComp->getNewsFeedList();
                $dashboardMenuItems = $this->fetchTable('UserMenuItems')->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1', 'UserMenuItems.menu_item_id'=>'67'])->first();

                $this->set(compact('newsfeedlist', 'dashboardMenuItems', 'user_id', 'user_role'));
            }else if($section == 'dashboard_news_feed_add'){
                $fileName .= 'news_feed_add';
                if(!empty($postData['news_feed_id'])){
                    $newsfeeds = $this->fetchTable('NewsFeeds')->findById($postData['news_feed_id'])->first();
                }else{
                    $newsfeeds = [];
                }

                $dashboardMenuItems = $this->fetchTable('UserMenuItems')->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1', 'UserMenuItems.menu_item_id'=>'67'])->first();

                $this->set(compact('newsfeeds', 'dashboardMenuItems', 'user_id', 'user_role'));
            }else if($section == 'pto_request_history'){
                $fileName .= 'pto_request_history';
                $ptorequestslist = $this->PTORequests->getPTOReqestList($authUserData['id']);
                $this->set(compact('ptorequestslist'));
            }

            $this->viewBuilder()->setLayout('ajax');
            $this->render($fileName);
        }
    }

    public function saveDashboardEvent(){
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }else{
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
                //echo "<pre>";print_r($postData);exit;
                $authUserData = $this->Authentication->getResult()->getData();

                $dashboardEvents = $this->fetchTable('Events');

                if(empty($postData['dashboard_event_id'])){
                    $dashboardevent = $dashboardEvents->newEmptyEntity();
                    $postData['added_by'] = $authUserData['id'];
                    $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                }else{
                    $dashboardevent = $dashboardEvents->get($postData['dashboard_event_id']);
                    $postData['updated_by'] = $authUserData['id'];
                    $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                }
                if(!empty($postData['event_start_date'])) {
                    $postData['event_start_date'] = FrozenTime::createFromFormat('m/d/Y', $postData['event_start_date']);
                }
        
                if(!empty($postData['event_end_date'])) {
                    $postData['event_end_date'] = FrozenTime::createFromFormat('m/d/Y', $postData['event_end_date']);
                }
                $dashboardevent = $dashboardEvents->patchEntity($dashboardevent, $postData);
                //print_r($dashboardevent);exit;
                if ($dashboardEvents->save($dashboardevent)) {
                    $dashboardeventlist = $this->DashboardComp->getEventList();
                    $DashboardHelper = new DashboardHelper(new \Cake\View\View());
                    $eventhtml = $DashboardHelper->getDashboardEventListHTML($dashboardeventlist);

                    $response = ['status'=>'success', 'message'=>'', 'eventhtml'=>$eventhtml];
                    echo json_encode($response);die;
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

    public function deleteDashboardEvent(){
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }else{
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
                //echo "<pre>";print_r($postData);exit;
                $dashboardEvents = $this->fetchTable('Events');

                if(!empty($postData['dashboard_event_id'])){
                    $dashboardeventlist = $dashboardEvents->get($postData['dashboard_event_id']);
                    $result = $dashboardEvents->delete($dashboardeventlist);
                    
                    $dashboardeventlist = $this->DashboardComp->getEventList();
                    $DashboardHelper = new DashboardHelper(new \Cake\View\View());
                    $eventhtml = $DashboardHelper->getDashboardEventListHTML($dashboardeventlist);

                    $response = ['status'=>'success', 'message'=>'', 'eventhtml'=>$eventhtml];
                    echo json_encode($response);die;
                }else{
                    $message = 'Something went wrong, please try again';
                    echo $message;die;
                }
                
            }else{
                $message = 'Something went wrong, please try again';
                echo $message;die;
            }
        }
    }

    public function saveDashboardNewsFeed(){
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }else{
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
                //echo "<pre>";print_r($postData);exit;
                $authUserData = $this->Authentication->getResult()->getData();
                $dashboardnewsfeed = $this->fetchTable('NewsFeeds');

                if(empty($postData['news_feed_id'])){
                    $newsfeed = $dashboardnewsfeed->newEmptyEntity();
                    
                    $newsfeed->news_feed = $postData['news_feed'];
                    $newsfeed->news_feed_speed = $postData['news_feed_speed'];
                    $newsfeed->status = $postData['status'];
                    $newsfeed->added_by = $authUserData['id'];
                    $newsfeed->created_at = new \Cake\I18n\FrozenTime('now');
                }else{
                    $newsfeed = $dashboardnewsfeed->get($postData['news_feed_id']);
                    $postData['updated_by'] = $authUserData['id'];
                    $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');

                    $newsfeed = $dashboardnewsfeed->patchEntity($newsfeed, $postData);
                }
                
                if ($dashboardnewsfeed->save($newsfeed)) {
                    $dashboardnewsfeedlist = $this->DashboardComp->getNewsFeedList();
                    $DashboardHelper = new DashboardHelper(new \Cake\View\View());
                    $newsfeedhtml = $DashboardHelper->getNewsFeedListHTML($dashboardnewsfeedlist);

                    $response = ['status'=>'success', 'message'=>'', 'newsfeedhtml'=>$newsfeedhtml];
                    echo json_encode($response);die;
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

    public function deleteDashboardNewsFeed(){
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }else{
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
                //echo "<pre>";print_r($postData);exit;
                
                if(!empty($postData['news_feed_id'])){
                    $dashboardnewsfeedlist = $this->NewsFeeds->get($postData['news_feed_id']);
                    $result = $this->NewsFeeds->delete($dashboardnewsfeedlist);
                    
                    $dashboardnewsfeedlist = $this->DashboardComp->getNewsFeedList();
                    $DashboardHelper = new DashboardHelper(new \Cake\View\View());
                    $newsfeedhtml = $DashboardHelper->getNewsFeedListHTML($dashboardnewsfeedlist);

                    $response = ['status'=>'success', 'message'=>'', 'newsfeedhtml'=>$newsfeedhtml];
                    echo json_encode($response);die;
                }else{
                    $message = 'Something went wrong, please try again';
                    echo $message;die;
                }
                
            }else{
                $message = 'Something went wrong, please try again';
                echo $message;die;
            }
        }
    }

    public function uploadNewsFeedImage(){
        $postData = current($_FILES);
        if(!empty($postData['tmp_name'])) 
        {
            $isvalidfile = 1;
            $arr_ext = array('bmp','jpg','jpeg', 'png','tif');
            
            $temp = $postData['tmp_name'];
            $name = $postData['name'];
            $ext = substr(strrchr($name , '.'), 1);
            
            if (!in_array($ext, $arr_ext)) {
                $isvalidfile = 0;
            }
            
            if($isvalidfile){
                $foldername = 'newsfeed_images';
                $filelocation = WWW_ROOT . $foldername.'/' . $name;
                
                if(move_uploaded_file($temp, $filelocation)) {
                    $filelocation = Router::url('/', true).$foldername.'/' . $name;
                    echo json_encode(array('location' => $filelocation));die;
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                header("HTTP/1.1 500 Server Error");
            }
            
        } else {
            header("HTTP/1.1 500 Server Error");
        }
    }
}