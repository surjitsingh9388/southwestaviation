<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use Cake\Database\Expression\QueryExpression;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;

    class SettingsController extends AppController
    {
        protected \App\Model\Table\SettingsTable $Settings;
        protected \App\Model\Table\StatementsTable $Statements;

        public function initialize():void {
            parent::initialize();

            $this->Settings = $this->fetchTable('Settings');
            $this->Statements = $this->fetchTable('Statements');
            $this->loadComponent('InventoryAttachment');
        }

        public function index()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Settings', $actionStatus))
                {
                    $actionItems = $actionStatus['Settings'];
                }
                $this->set(compact('actionItems'));
            }

            $orderby = array('order'=>'Settings.id DESC');
            $settingsdata = $this->Settings->find('all', $orderby)->select($this->Settings);
            $statementdata = $this->Statements->find('all', ['order'=>'id DESC'])->select($this->Statements);

            $this->set(compact('settingsdata', 'statementdata'));
        }

        public function fetchSettingPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
    
                $fileName = '/element/Settings/';
    
                $fileName .= 'setting_add';
                if(!empty($postData['setting_id'])){
                    $settings = $this->Settings->get($postData['setting_id']);
                }else{
                    $settings = $this->Settings->newEmptyEntity();
                }

                $sessionUser = $this->request->getSession()->read('Auth');;
                $user_role = $sessionUser['role_id'];

                $this->set(compact('settings', 'user_role'));
    
                $this->viewBuilder()->setLayout('ajax');
                $this->render($fileName);
            }
        }

        public function uploadSettingLogo(){
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt','mp4');
                
                $attachment = $postData['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');
                $ext = substr(strrchr($name , '.'), 1);
                
                if($isvalidfile){
                    $foldername = 'settings_logo';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tblrow = $this->InventoryAttachment->uploadSelectedFilesToServer($postData, $filelocation, $foldername);
                    
                    if($tblrow != ''){
                        $result = array('status'=>'success', 'message'=>"Saved successfully.", 'tblrow'=>$tblrow);
                    } else {
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                } else {
                    $result = array('status'=>'failed', 'message'=>'Please upload correct format.');
                }
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                echo json_encode($result);die;
            }
        }

        public function saveSettings(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    if(empty($postData['filenames'])){
                        $response = ['status'=>'failure', 'message'=>'Please upload Logo.'];
                        echo json_encode($response);die;
                    }else if(empty($postData['office_address'])){
                        $response = ['status'=>'failure', 'message'=>'Please fill Office Address.'];
                        echo json_encode($response);die;
                    }
                    $postData['logo'] = $postData['filenames'][0];
                    if(empty($postData['setting_id'])){
                        $settings = $this->Settings->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $settings = $this->Settings->get($postData['setting_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $settings = $this->Settings->patchEntity($settings, $postData);
                    
                    if ($this->Settings->save($settings)) {
                        $response = ['status'=>'success', 'message'=>''];
                        echo json_encode($response);die;
                    }else{
                        /*$x = $settings->errors();
                        if ($x) {
                            debug($settings);
                            debug($x);
                            return false;
                        }*/
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }
    
        public function deleteSettings(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['setting_id'])){
                        $settingslist = $this->Settings->get($postData['setting_id']);
                        $result = $this->Settings->delete($settingslist);
    
                        $response = ['status'=>'success', 'message'=>''];
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

        public function fetchStatementPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
    
                $fileName = '/element/Settings/';
    
                $fileName .= 'statement_add';
                if(!empty($postData['statement_id'])){
                    $statements = $this->Statements->get($postData['statement_id']);
                }else{
                    $statements = $this->Statements->newEmptyEntity();
                }

                $sessionUser = $this->request->getSession()->read('Auth');;
                $user_role = $sessionUser['role_id'];

                $this->set(compact('statements', 'user_role'));
    
                $this->viewBuilder()->setLayout('ajax');
                $this->render($fileName);
            }
        }

        public function saveStatements(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    if(empty($postData['statement_name'])){
                        $response = ['status'=>'failure', 'message'=>'Please fill Statement Name.'];
                        echo json_encode($response);die;
                    }
                    
                    if(empty($postData['statement_id'])){
                        $statements = $this->Statements->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $statements = $this->Statements->get($postData['statement_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $statements = $this->Statements->patchEntity($statements, $postData);
                    
                    if ($this->Statements->save($statements)) {
                        $response = ['status'=>'success', 'message'=>''];
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

        public function deleteStatements(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['statement_id'])){
                        $statementlist = $this->Statements->get($postData['statement_id']);
                        $result = $this->Statements->delete($statementlist);
    
                        $response = ['status'=>'success', 'message'=>''];
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
    }

?>