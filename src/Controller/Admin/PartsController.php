<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;
    use Cake\I18n\FrozenTime;

    class PartsController extends AppController
    {
        protected \App\Model\Table\UsersTable $users;
        protected \App\Model\Table\PlanesTable $planes;
        protected \App\Model\Table\PartsTable $parts;

        public function initialize(): void
        {
            parent::initialize();

            $this->Users = $this->fetchTable('Users');
            $this->Planes = $this->fetchTable('Planes');
            $this->Parts = $this->fetchTable('Parts');
        }

        public function index()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Parts', $actionStatus))
                {
                    $actionItems = $actionStatus['Parts'];
                }
                $this->set(compact('actionItems'));
            }
        }

        public function search()
        {
            $query = [];        
            
            $query['count'] = "SELECT count(Parts.id) AS count  FROM `parts` Parts WHERE 1=1";

            $query['detail'] = "SELECT Parts.id, Parts.`serial_number`, Parts.`description`, Parts.`location`, Parts.`lot_number`, Parts.`sku`, Parts.`date_received`, Parts.`part_classification`, Parts.`qty` FROM `parts` Parts WHERE 1=1 ";
            
            return $query;
        }

        public function ajaxManagePartsSearch(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Parts', $actionStatus))
                {
                    $actionItems = $actionStatus['Parts'];
                }
            }
            $this->autoRender = false;
            $this->viewBuilder()->setLayout('ajax');
            $requestData= $this->request->getData();

            $query = $this->search();

            $cond = "";
            if( isset($requestData['columns'][0]['search']['value']) && !empty($requestData['columns'][0]['search']['value']) && isset($requestData['columns'][1]['search']['value'])){
                $searchfld = str_replace(' #', '',strtolower($requestData['columns'][1]['search']['value']));
                $searchfld = $searchfld == 'part' ? 'part_number' : $searchfld;
                $search = $requestData['columns'][0]['search']['value'];
                if(empty($searchfld) || $searchfld == 'all'){
                    $cond.=" AND ( Parts.sku LIKE '%".$search."%' OR  Parts.description LIKE '%".$search."%' OR  Parts.part_number LIKE '%".$search."%')";
                }else{
                    $cond.=" AND ( Parts.$searchfld LIKE '%".$search."%')";
                }
                //echo $cond;exit;
            }
            
            $columns = array(
                0 => 'Parts.id',
                1 => 'Parts.sku',
                2 => 'Parts.part_number',
                3 => 'Parts.description',
                4 => 'Parts.date_received',
                5 => 'Parts.part_classification',
            );

            $count = $query['count'].$cond;
            $detail = $query['detail'].$cond;
            $totalCount = $query['count'];

            $conn = ConnectionManager::get('default');
            $results = $conn->execute($count)->fetchAll('assoc');
            $totalData = isset($results[0]['count']) ? $results[0]['count'] : 0;

            $totalFiltered = $totalData;
            $results = $conn->execute( $totalCount )->fetchAll('assoc');
            $totalRecords = isset($results[0]['count']) ? $results[0]['count'] : 0;

            $sidx = $columns[$requestData['order'][0]['column']];
            $sord = $requestData['order'][0]['dir'];
            $start = $requestData['start'];
            $length = PAGINATION_LIMIT;

            $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');

            $j=1;
            
            $data = array();
            $partclassifications = unserialize(PARTS_CLASSIFICATION);
            foreach ( $results as $row){
                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls" name="childcheckbox" value="'.$row['id'].'" >';
                $nestedData[] = $row["serial_number"];
                $nestedData[] = $row["description"];
                $nestedData[] = $row["location"];
                $nestedData[] = $row["sku"];
                $nestedData[] = $row["lot_number"];
                $nestedData[] = date('m-d-Y', strtotime($row["date_received"]));
                $nestedData[] = $partclassifications[$row["part_classification"]];
                $nestedData[] = $row["qty"];

                $data[] = $nestedData;
            }

            $roles = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval( $totalRecords ),
                "recordsFiltered" => intval( $totalFiltered ),
                "data"            => $data
            );
        
            echo json_encode($roles);die;
            
        }

        /**
         * Add method
         *
         * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
         */
        public function add()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Parts', $actionStatus))
                {
                    $actionItems = $actionStatus['Parts'];
                }
            }
            $part = $this->Parts->newEmptyEntity();
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['date_received'])) {
                    $postData['date_received'] = FrozenTime::createFromFormat('m/d/Y', $postData['date_received']);
                }
        
                if(!empty($postData['warranty_expires'])) {
                    $postData['warranty_expires'] = FrozenTime::createFromFormat('m/d/Y', $postData['warranty_expires']);
                }

                $part = $this->Parts->patchEntity($part, $postData);//print_r($part);exit;
                if ($this->Parts->save($part)) {
                    $this->Flash->success(__('The parts has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }

                //for test error while saving data to database
                /*$x = $part->errors();
                if ($x) {
                    debug($part);
                    debug($x);
                    return false;
                }*/

                $this->Flash->error(__('The parts could not be saved. Please, try again.'));
            }

            $roles = '1,'.PERMISSION_ROLE_ID;
        
            $allAdmins = $this->Users->find('list', array (
                                            'keyField' => 'id',
                                            'valueField' => 'full_name'
                                        ))
                                    ->where(['Users.role_id IN ('.$roles.')', 'Users.id !=' => 1, 'Users.suspended' => 0])
                                    ->toArray();

            $this->set(compact('part', 'actionItems', 'allAdmins'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Parts', $actionStatus))
                {
                    $actionItems = $actionStatus['Parts'];
                }
            }
            $part = $this->Parts->get($id);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                $part = $this->Parts->patchEntity($part, $postData);
                if ($this->Parts->save($part)) {
                    $this->Flash->success(__('The Parts has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The Parts could not be saved. Please, try again.'));
            }
            $this->set(compact('part', 'actionItems'));
        }

        public function bulkadd(){
            $params = $this->request->getData();
            $attachment = $postData['file_name']; 
            $name = $attachment->getClientFilename();
            $type = $attachment->getClientMediaType();
            $size = $attachment->getSize();
            $temp = $attachment->getStream()->getMetadata('uri');
            if(!empty($name) && !empty($temp)) 
            {
                $ext = substr(strrchr($name , '.'), 1);
                $arr_ext = array('csv');
                
                if (in_array($ext, $arr_ext)) {
                    $filelocation = WWW_ROOT . 'parts/' . $name;
                    if(move_uploaded_file($temp, $filelocation)) {
                        $handle = fopen($filelocation, "r");
                        $heading = false;
                        $headingarr = array();
                        while ($data = fgetcsv($handle)){
                            if(!$heading){
                                $headingarr = $data;
                                $heading = true;
                                continue;
                            }
                            $partdata=array_combine($headingarr,$data);//print_r($partdata);exit;
                            $part = $this->Parts->newEntity($partdata);
                            $this->Parts->save($part);
                        }
                        fclose($handle);
                        $result = array('status'=>'success', 'message'=>"Saved successfully.");
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

        public function delete()
        {
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $ids = explode(',',$_POST['id']);
                $this->request->allowMethod(['post', 'delete']);
                
                try {
                    if ($this->Parts->deleteAll(['Parts.id IN' => $ids])) {
                        $this->Flash->success(__('The Parts has been deleted.'));
                    } else {
                        $this->Flash->error(__('The Parts could not be deleted. Please, try again.'));
                    }
                } catch(\PDOException $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                } catch (\Exception $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                }    
                return $this->redirect(['action' => 'index']);
            }else{
                $this->Flash->error(__('Something went wrong. Please, try again.'));
            }
        }

    }

?>