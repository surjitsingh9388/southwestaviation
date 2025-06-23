<?php
    
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use App\View\Helper\InventoryToolHTMLHelper;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;
    use Cake\Event\EventInterface;
    use Cake\I18n\FrozenTime;

    class InventoryToolsController extends AppController
    {
        protected \App\Model\Table\UsersTable $Users;
        protected \App\Model\Table\InventoryToolsTable $InventoryTools;
        protected \App\Model\Table\InventoryToolCertificationHistoriesTable $InventoryToolCertificationHistories;

        public function initialize():void {
            parent::initialize();

            $this->Users = $this->fetchTable('Users');
            $this->InventoryTools = $this->fetchTable('InventoryTools');
            $this->InventoryToolCertificationHistories = $this->fetchTable('InventoryToolCertificationHistories');

            $this->loadComponent('InventoryAttachment');
            $this->loadComponent('Inventory');
            $this->loadComponent('CustomerOTC');
        }

        public function beforeFilter(EventInterface $event) {
            parent::beforeFilter($event);

            $authUserData = $this->Authentication->getResult()->getData();
            $this->set('userData', $authUserData);
        }

        public function index()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Tools', $actionStatus))
                {
                    $actionItems = $actionStatus['Tools'];
                }
                $this->set(compact('actionItems'));
            }

            $orderby = array('order'=>'InventoryTools.id DESC');
            $toolsdata = $this->InventoryTools->find('all', $orderby)->select($this->InventoryTools);
            $this->set(compact('toolsdata'));
        }

        public function openToolAddPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();

                $fileName = '/element/InventoryPopup/add_new_tool';
                $this->viewBuilder()->setLayout('ajax');
                $this->render($fileName);
            }
        }

        public function saveInventoryTool(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $authUserData = $this->Authentication->getResult()->getData();
                $postData = $this->request->getData();
                $inventorytool = $this->InventoryTools->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $inventorytooldata = '';
                    if(!empty($postData['tool_id'])){
                        $inventorytool = $this->InventoryTools->get($postData['tool_id']);
                    }else{
                        if(!empty($postData['tool_name'])){
                            $inventorytooldata = $this->InventoryTools->exists(['tool_name'=>$postData['tool_name']]);
                        }else{
                            $inventorytool = $this->InventoryTools->newEmptyEntity();
                        }
                    }
                    //echo "<pre>";print_r($inventorytooldata);exit;
                    if(!$inventorytooldata){
                        $postData['calibration_status'] = '1';
                        $postData['added_by'] = $authUserData['id'];

                        $inventorytool = $this->InventoryTools->patchEntity($inventorytool, $postData);

                        if ($this->InventoryTools->save($inventorytool)) {
                            $tool_id = $inventorytool->id;

                            $toolsdata = $this->InventoryTools->find('all')->select($this->InventoryTools);

                            $InventoryToolHTMLHelper = new InventoryToolHTMLHelper(new \Cake\View\View());
                            $toolshtml = $InventoryToolHTMLHelper->toolsTableHTML($toolsdata);

                            $reponsearr = ['status'=>'success', 'message'=>'', 'tool_id'=>$tool_id, 'toolshtml'=>$toolshtml];
                        }else{
                            $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again.'];
                        }
                    }else{
                        $message = 'Tool Name already exist.';
                        $reponsearr = ['status'=>'failed', 'message'=>'Tool Name already exist.'];
                    }
                }else{
                    $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again.'];
                }

                echo json_encode($reponsearr);die;
            }
        }

        public function openToolDetailPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $tool_id = $postData['tool_id'];

                $tooltabdata = $this->CustomerOTC->getInventoryToolTabData($postData);
                extract($tooltabdata);

                $this->set(compact('inventorytools', 'vendorlist', 'certificationhistories', 'wohistories', 'invtoolphotoes', 'invtoolfiles', 'tool_id'));

                $fileName = '/element/InventoryPopup/inventory_tool_detail';
                $this->viewBuilder()->setLayout('ajax');
                $this->render($fileName);
            }
        }

        public function openCertificationHistoryPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $tool_id = $postData['tool_id'];

                $certificationhistories = $this->InventoryToolCertificationHistories->newEmptyEntity();

                $this->set(compact('certificationhistories', 'tool_id'));

                $fileName = '/element/InventoryPopup/tool_certified_history_add';
                $this->viewBuilder()->setLayout('ajax');
                $this->render($fileName);
            }
        }

        public function saveToolCertifiedHistory(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $authUserData = $this->Authentication->getResult()->getData();
                $postData = $this->request->getData();
                
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $certifiedhistories = $this->InventoryToolCertificationHistories->newEmptyEntity();
                    
                    $postData['added_by'] = $authUserData['id'];
                    
                    $certifiedhistories = $this->InventoryToolCertificationHistories->patchEntity($certifiedhistories, $postData);

                    if ($this->InventoryToolCertificationHistories->save($certifiedhistories)) {
                        $certifiedhistories = $this->CustomerOTC->getToolCertifiedHistoryList($postData['tool_id']);

                        $InventoryToolHTMLHelper = new InventoryToolHTMLHelper(new \Cake\View\View());
                        $certifhisthtml = $InventoryToolHTMLHelper->certifielHistoryTableHTML($certifiedhistories);
                        
                        $reponsearr = ['status'=>'success', 'message'=>'', 'certifhisthtml'=>$certifhisthtml];
                    }else{
                        $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again.'];
                    }
                }else{
                    $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again.'];
                }

                echo json_encode($reponsearr);die;
            }
        }

        public function deleteToolCertifiedHistory(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['certified_history_id'])){
                        $certifiedhistory = $this->InventoryToolCertificationHistories->get($postData['certified_history_id']);
                        $tool_id = $certifiedhistory->tool_id;
                        $result = $this->InventoryToolCertificationHistories->delete($certifiedhistory);

                        $certifiedhistories = $this->CustomerOTC->getToolCertifiedHistoryList($tool_id);
                        
                        $InventoryToolHTMLHelper = new InventoryToolHTMLHelper(new \Cake\View\View());
                        $certifhisthtml = $InventoryToolHTMLHelper->certifielHistoryTableHTML($certifiedhistories);

                        $response = ['status'=>'success', 'message'=>'', 'certifhisthtml'=>$certifhisthtml];
                        echo json_encode($response);die;
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                        echo json_encode($response);die;
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    echo json_encode($response);die;
                }
            }
        }

        public function uploadToolFiles(){
            
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                $isvalidfile = 1;
                $arr_ext = array('pdf','doc', 'docx', 'xls', 'xlsx');
                
                $attachment = $postData['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');
                $ext = substr(strrchr($name , '.'), 1);
                
                if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }
                
                if($isvalidfile){
                    $foldername = 'inventorytools';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tableName = 'inventory_tool_file';

                    $tblrow = $this->InventoryAttachment->uploadInventoryToolFilesToServer($postData, $filelocation, $foldername, $tableName);
                    
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

        public function uploadToolPhotos(){
            
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                $isvalidfile = 1;
                $arr_ext = array('bmp','jpg','jpeg', 'png','tif');
                
                $attachment = $postData['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');
                $ext = substr(strrchr($name , '.'), 1);
                
                if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }
                
                if($isvalidfile){
                    $foldername = 'inventorytools';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tableName = 'inventory_tool_photo';

                    $tblrow = $this->InventoryAttachment->uploadInventoryToolFilesToServer($postData, $filelocation, $foldername, $tableName);
                    
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

        public function getInventoryToolNextPrev(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['tool_id'])){
                        $postData = $this->request->getData();
                        $tool_id = $postData['tool_id'];
        
                        $tooltabdata = $this->CustomerOTC->getInventoryToolTabData($postData);
                        extract($tooltabdata);
                        
                        if(!empty($inventorytools)){
                            $this->set(compact('inventorytools', 'vendorlist', 'certificationhistories', 'wohistories', 'invtoolphotoes', 'invtoolfiles', 'tool_id'));

                            $this->viewBuilder()->setLayout('ajax');
                            $this->render('/element/Inventory/inventory_tool_detail_block');
                        }else{
                            echo "Empty";die;
                        }
                    }else{
                        echo "Failed";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function deleteToolDetail(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['tool_id'])){
                        $invtool = $this->InventoryTools->get($postData['tool_id']);
                        $result = $this->InventoryTools->delete($invtool);

                        $orderby = array('order'=>'InventoryTools.id DESC');
                        $toolsdata = $this->InventoryTools->find('all', $orderby)->select($this->InventoryTools);

                        $InventoryToolHTMLHelper = new InventoryToolHTMLHelper(new \Cake\View\View());
                        $toolshtml = $InventoryToolHTMLHelper->toolsTableHTML($toolsdata);

                        $response = ['status'=>'success', 'message'=>'', 'toolshtml'=>$toolshtml];
                        echo json_encode($response);die;
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                        echo json_encode($response);die;
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    echo json_encode($response);die;
                }
            }
        }
    }
?>