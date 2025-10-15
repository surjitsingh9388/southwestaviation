<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;

    class TechnicalPublicationSwasController extends AppController
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
                if(array_key_exists('SWAS', $actionStatus))
                {
                    $actionItems = $actionStatus['SWAS'];
                }
            }
            $heading = 'SWAS';
            $usersArr = $this->TechnicalPublication->getUserDropdownList();
            $this->set(compact('actionItems', 'heading', 'usersArr'));
            $subpage_id = '1';
            $this->createNewFolder($subpage_id, 'index');
        }
        
        public function hr(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('SWAS HR', $actionStatus))
                {
                    $actionItems = $actionStatus['SWAS HR'];
                }
            }
            $heading = 'SWAS';
            $this->set(compact('actionItems', 'heading'));
            $subpage_id = '1';
            $this->createNewFolder($subpage_id, 'hr');
        }

        public function maintenance(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('SWAS Maintenance', $actionStatus))
                {
                    $actionItems = $actionStatus['SWAS Maintenance'];//echo "<pre>";print_r($actionStatus);exit;
                }
            }
            $heading = 'Maintenance';
            $this->set(compact('actionItems', 'heading'));
            $subpage_id = '2';
            $this->createNewFolder($subpage_id, 'maintenance');
        }

        public function information_technology(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('SWAS Information Technology', $actionStatus))
                {
                    $actionItems = $actionStatus['SWAS Information Technology'];
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
                if(array_key_exists('SWAS Inventory', $actionStatus))
                {
                    $actionItems = $actionStatus['SWAS Inventory'];
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
                if(array_key_exists('SWAS', $actionStatus))
                {
                    $actionItems = $actionStatus['SWAS'];
                }
            }
            $this->set(compact('actionItems'));
            
            $postData = $this->request->getData();//print_r($postData);exit;
            if(isset($postData['id']) && !empty($postData['id'])){
                $this->request->allowMethod(['post', 'delete']);
                $delete_folder_name = $postData['delete_folder_name'];
                try {
                    $mainfolder = 'SWAS';
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
            $postData = $this->request->getData();
            $mainfolder = 'SWAS';
            $result = $this->TechnicalPublication->uploadTechPublicationAttachment($mainfolder, $postData);
            echo json_encode($result);die;
        }

        public function createNewFolder($subpage_id, $action){
            $postData = $this->request->getData();
            $params = $this->request->getParam('pass');
            $mainfolder = 'SWAS';
            $main_page_id = '1';
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
            $controllerName = 'TechnicalPublicationSwas';
            $this->set(compact('technicalPublications', 'folderpath', 'folderarr', 'controllerName', 'params', 'action', 'main_page_id', 'subpage_id', 'mainfolder'));
        }

        public function techPublPermissionPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $technical_publication_id = $postData['technical_publication_id'];
                    if(!empty($technical_publication_id)){
                        $technicalpublicationdata = $this->TechnicalPublications->get($technical_publication_id);
                        $userIdArr = !empty($technicalpublicationdata->permission_user_ids) ? explode(',', $technicalpublicationdata->permission_user_ids) : [];

                        $usersArr = $this->TechnicalPublication->getUserDropdownList();

                        $this->set(compact('technicalpublicationdata', 'userIdArr', 'usersArr'));
                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/TechnicalPublications/technical_publication_permission');
                    }else{
                        echo 'Failed';die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function saveTechPublPermission(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $authUserData = $this->Authentication->getResult()->getData();

                    $technical_publication_id = $postData['technical_publication_id'];
                    $permission_user_ids = $postData['permission_user_ids'];
                    if(!empty($technical_publication_id) && !empty($permission_user_ids)){
                        $technicalPublications = $this->TechnicalPublications->get($technical_publication_id);
                        $permission_userids = implode(',', $permission_user_ids);

                        $postData['permission_user_ids'] = $permission_userids;
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');

                        $technicalPublications = $this->TechnicalPublications->patchEntity($technicalPublications, $postData);
                        
                        if ($this->TechnicalPublications->save($technicalPublications)) {
                            $result = array('status'=>'success', 'message'=>"Permission Saved successfully.");
                        }else{
                            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                        }
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                echo json_encode($result);exit;
            }
        }

        public function downloadFolder(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('SWAS', $actionStatus))
                {
                    $actionItems = $actionStatus['SWAS'];
                }
            }
            $this->set(compact('actionItems'));
            
            if(!empty($this->request->getQuery('folder_path'))){
                $folderpath = WWW_ROOT.$this->request->getQuery('folder_path');
                $is_folder = $this->request->getQuery('is_folder');
                $zip_folder = $this->TechnicalPublication->downloadFolder($is_folder, $folderpath);
            }
        }

        public function editFolderFile()
        {
            $this->request->allowMethod(['post']);
            //pr($this->request->getData());exit;
            $authUserData = $this->Authentication->getResult()->getData();

            $id = $this->request->getData('technical_publication_id');
            $newName = $this->request->getData('folder_file_name');
            $folderFilePath = $this->request->getData('folder_file_path');

            $techPubl = $this->TechnicalPublications->get($id);
            $oldName = $techPubl->folder_file_name;
            if(!empty($this->request->getData('added_by'))){
                $techPubl->added_by = $this->request->getData('added_by');
            }
            // Update database
            $techPubl->folder_file_name = $newName;
            $techPubl->updated_by = $authUserData['id'];
            $techPubl->updated_at = new \Cake\I18n\FrozenTime('now');

            if ($this->TechnicalPublications->save($techPubl)) {
                // Also rename file/folder physically if exists
                $oldPath = WWW_ROOT . $folderFilePath. DS . $oldName;
                $newPath = WWW_ROOT . $folderFilePath. DS . $newName;

                if (file_exists($oldPath)) {
                    rename($oldPath, $newPath);
                }

                $result = array('status'=>'success', 'message'=>"Folder/File Name updated successfully.");
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);exit;
        }

    }

?>