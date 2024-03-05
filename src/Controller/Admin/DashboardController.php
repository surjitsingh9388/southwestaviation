<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use App\View\Helper\DashboardHelper;

/**
 * Dashboard Controller
 */
class DashboardController extends AppController
{   
    public function initialize() {
        array_map(
            [
                $this, 
                'loadModel'
            ], 
            [
                'Events',
                'NewsFeeds',
            ]
        );

        array_map(
            [
                $this, 
                'loadComponent'
            ],
            [
                'User', 
                'CustomerOTC',
                'UserTimeClock',
                'Dashboard'
            ]
        );
        
        parent::initialize();
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
        $date_to = date("Y-m-d");
        $date_from = date('Y-m-d', strtotime('-7 days'));
        $user_id = $this->Auth->user('id');

        //$aircraftoptiondata = $this->CustomerOTC->getAircraftOptionDataWO();
        $usertimeclocklist = $this->UserTimeClock->getTimeClockDetailByUser($date_from, $date_to, $user_id);
        $filter = strtotime(date('Y-m'));
        $sessionUser = $this->request->session()->read('Auth.User');
        $user_role = $sessionUser['role_id'];
        
        $dashboardevents = $this->Dashboard->getSelectedMonthEventList($date_to);
        $dashboardnewsfeed = $this->Dashboard->getCurrentNewsFeed();
        
        $dashboardMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1'])->toArray();

        $this->set(compact('actionItems', 'usertimeclocklist', 'filter', 'user_role', 'dashboardevents', 'dashboardnewsfeed', 'dashboardMenuItems', 'user_id'));
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

    public function getPrevNextEventCalender(){
        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }else{
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
                $filter = !isset($postData['month']) ? strtotime(date('Y-m')) : strtotime($postData['month']);
                $date_from = !isset($postData['month']) ? date('Y-m-01') : date('Y-m-01', strtotime($postData['month']));
                
                $dashboardevents = $this->Dashboard->getSelectedMonthEventList($date_from);
                $this->set(compact('filter', 'dashboardevents'));

                $this->layout = 'ajax';
                $this->render('/Element/Dashboard/event_calender');
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
            $user_id = $this->Auth->user('id');
            $sessionUser = $this->request->session()->read('Auth.User');
            $user_role = $sessionUser['role_id'];

            $fileName = '/Element/Dashboard/';

            if($section == 'dashboard_event_list'){
                $fileName .= 'dashboard_event_list';
                
                $dashboardeventlist = $this->Dashboard->getEventList();
                
                $dashboardMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1', 'UserMenuItems.menu_item_id'=>'68'])->first();

                $this->set(compact('dashboardeventlist', 'dashboardMenuItems', 'user_id', 'user_role'));
            }else if($section == 'dashboard_event_add'){
                $fileName .= 'dashboard_event_add';
                if(!empty($postData['dashboard_event_id'])){
                    $dashboardevent = $this->Events->get($postData['dashboard_event_id']);
                }else{
                    $dashboardevent = $this->Events->newEntity();
                }

                $dashboardMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1', 'UserMenuItems.menu_item_id'=>'68'])->first();

                $this->set(compact('dashboardevent', 'dashboardMenuItems', 'user_id', 'user_role'));
            }else if($section == 'news_feed_list'){
                $fileName .= 'news_feed_list';
                
                $newsfeedlist = $this->Dashboard->getNewsFeedList();
                $dashboardMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1', 'UserMenuItems.menu_item_id'=>'67'])->first();

                $this->set(compact('newsfeedlist', 'dashboardMenuItems', 'user_id', 'user_role'));
            }else if($section == 'dashboard_news_feed_add'){
                $fileName .= 'news_feed_add';
                if(!empty($postData['news_feed_id'])){
                    $newsfeeds = $this->NewsFeeds->get($postData['news_feed_id']);
                }else{
                    $newsfeeds = $this->NewsFeeds->newEntity();
                }

                $dashboardMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.user_id' => $user_id, 'UserMenuItems.parent_id'=>'1', 'UserMenuItems.menu_item_id'=>'67'])->first();

                $this->set(compact('newsfeeds', 'dashboardMenuItems', 'user_id', 'user_role'));
            }

            $this->layout = 'ajax';
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

                if(empty($postData['dashboard_event_id'])){
                    $dashboardevent = $this->Events->newEntity();
                    $postData['added_by'] = $this->Auth->user('id');
                    $postData['created_at'] = date('Y-m-d H:i:s');
                }else{
                    $dashboardevent = $this->Events->get($postData['dashboard_event_id']);
                    $postData['updated_by'] = $this->Auth->user('id');
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                }
                
                $dashboardevent = $this->Events->patchEntity($dashboardevent, $postData);
                
                if ($this->Events->save($dashboardevent)) {
                    $dashboardeventlist = $this->Dashboard->getEventList();
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
                
                if(!empty($postData['dashboard_event_id'])){
                    $dashboardeventlist = $this->Events->get($postData['dashboard_event_id']);
                    $result = $this->Events->delete($dashboardeventlist);
                    
                    $dashboardeventlist = $this->Dashboard->getEventList();
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

                if(empty($postData['news_feed_id'])){
                    $dashboardnewsfeed = $this->NewsFeeds->newEntity();
                    $postData['added_by'] = $this->Auth->user('id');
                    $postData['created_at'] = date('Y-m-d H:i:s');
                }else{
                    $dashboardnewsfeed = $this->NewsFeeds->get($postData['news_feed_id']);
                    $postData['updated_by'] = $this->Auth->user('id');
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                }
                
                $dashboardnewsfeed = $this->NewsFeeds->patchEntity($dashboardnewsfeed, $postData);
                
                if ($this->NewsFeeds->save($dashboardnewsfeed)) {
                    $dashboardnewsfeedlist = $this->Dashboard->getNewsFeedList();
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
                    
                    $dashboardnewsfeedlist = $this->Dashboard->getNewsFeedList();
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