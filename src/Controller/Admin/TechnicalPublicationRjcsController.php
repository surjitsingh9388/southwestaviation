<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use Dompdf\Dompdf;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;

    class TechnicalPublicationRjcsController extends AppController
    {
        protected \App\Model\Table\TechnicalPublicationsTable $TechnicalPublications;
        
        public function initialize():void {
            parent::initialize();
            $this->TechnicalPublications = $this->fetchTable('TechnicalPublications');
            $this->loadComponent('TechnicalPublication');
        }

        public function index(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('RJC', $actionStatus))
                {
                    $actionItems = $actionStatus['RJC'];
                }
            }
            $heading = 'RJC';
            $this->set(compact('actionItems', 'heading'));
            $subpage_id = '1';
            $this->createNewFolder($subpage_id, 'index');
        }

        public function hr(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('RJC HR', $actionStatus))
                {
                    $actionItems = $actionStatus['RJC HR'];
                }
            }
            $heading = 'HR';
            $this->set(compact('actionItems', 'heading'));
            $subpage_id = '1';
            $this->createNewFolder($subpage_id, 'hr');
        }

        public function fuel_system(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('RJC Fuel System', $actionStatus))
                {
                    $actionItems = $actionStatus['RJC Fuel System'];
                }
            }
            $heading = 'Fuel System';
            $this->set(compact('actionItems', 'heading'));
            $subpage_id = '2';
            $this->createNewFolder($subpage_id, 'fuel_system');
        }

        public function information_technology(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('RJC Information Technology', $actionStatus))
                {
                    $actionItems = $actionStatus['RJC Information Technology'];
                }
            }
            $heading = 'Information Technology';
            $this->set(compact('actionItems', 'heading'));
            $subpage_id = '3';
            $this->createNewFolder($subpage_id, 'information_technology');
        }

        public function inventory(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('RJC Inventory', $actionStatus))
                {
                    $actionItems = $actionStatus['RJC Inventory'];
                }
            }
            $heading = 'Inventory';
            $this->set(compact('actionItems', 'heading'));
            $subpage_id = '4';
            $this->createNewFolder($subpage_id, 'inventory');
        }

        public function delete(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('RJC', $actionStatus))
                {
                    $actionItems = $actionStatus['RJC'];
                }
            }
            $this->set(compact('actionItems'));
            
            $postData = $this->request->getData();//print_r($postData);exit;
            if(isset($postData['id']) && !empty($postData['id'])){
                $this->request->allowMethod(['post', 'delete']);
                $delete_folder_name = $postData['delete_folder_name'];
                try {
                    $mainfolder = 'RJC';
                    $is_deleted = $this->TechnicalPublication->deleteFolderFile($mainfolder, $postData);
                    if (!empty($is_deleted)) {
                        $this->Flash->success(__('`'.$delete_folder_name.'` has been deleted'));
                    } else {
                        $this->Flash->error(__('`'.$delete_folder_name.'` could not be deleted. Please, try again.'));
                    }
                } catch(\PDOException $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                } catch (\Exception $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                }
            }else{
                $this->Flash->error(__('Something went wrong. Please, try again.'));
            }

            return $this->redirect(['action' => $postData['folder_path']]);
        }

        public function uploadTechPublicationFile(){
            $postData = $this->request->getData();//print_r($postData);exit;
            $mainfolder = 'RJC';
            $result = $this->TechnicalPublication->uploadTechPublicationAttachment($mainfolder, $postData);
            echo json_encode($result);die;
        }

        public function createNewFolder($subpage_id, $action){
            $postData = $this->request->getData();
            $params = $this->request->getParam('pass');
            $mainfolder = 'RJC';
            $main_page_id = '3';
            if ($this->request->is('post') || $this->request->is('put')) {
                $redirecturl = $this->TechnicalPublication->createNewFolder($mainfolder,$main_page_id, $subpage_id, $postData, $params, $action);
                if(!empty($redirecturl)){
                    $this->redirect(['action' => $redirecturl]);
                }
            }
            $params = $this->request->getParam('pass');
            $folder_file = count($params) > 0 ? $params[count($params)-1] : '';
            
            $technicalPublications = $this->TechnicalPublication->getTechnicalPublications($main_page_id, $subpage_id, $folder_file);
            $folderarr = [$action];
            if(!empty($params)){
                $folderarr = array_merge($folderarr, $params);
            }
            $folderpath = implode(DS, $folderarr);
            $controllerName = 'TechnicalPublicationRjcs';
            $this->set(compact('technicalPublications', 'folderpath', 'folderarr', 'controllerName', 'params', 'action', 'main_page_id', 'subpage_id', 'mainfolder'));
        }

        public function downloadFolder(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('RJC', $actionStatus))
                {
                    $actionItems = $actionStatus['RJC'];
                }
            }
            $this->set(compact('actionItems'));
            
            if(!empty($this->request->getQuery('folder_path'))){
                $folderpath = WWW_ROOT.$this->request->getQuery('folder_path');
                $is_folder = $this->request->getQuery('is_folder');
                $zip_folder = $this->TechnicalPublication->downloadFolder($is_folder, $folderpath);
            }
        }
    }

?>