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
    use Cake\Event\EventInterface;

    class InventoryManufacturersController extends AppController
    {
        protected \App\Model\Table\InventoryPartManufacturersTable $InventoryPartManufacturers;
        
        public function initialize():void {
            parent::initialize();

            $this->InventoryPartManufacturers = $this->fetchTable('InventoryPartManufacturers');

            $this->loadComponent('Address');
            $this->loadComponent('Inventory');
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
                if(array_key_exists('Manufacturers', $actionStatus))
                {
                    $actionItems = $actionStatus['Manufacturers'];
                }
            }
            $countries = $this->Address->getCountryList();
            $states = $this->Address->getStateListByCountryId(231);

            $this->set(compact('actionItems', 'countries', 'states'));
        }

        public function search()
        {
            $query = [];        
            $cond = ' and inv.status = "1"';
            $query['count'] = "SELECT count(inv.id) AS count  FROM `inventory_part_manufacturers` as inv left join countries c on inv.country = c.id left join states as s on inv.state = s.id WHERE 1=1 $cond ";

            $query['detail'] = "SELECT inv.id, inv.name, inv.city, inv.province, inv.country, inv.primaryphone, c.name as country_name, s.name as state_name, inv.province FROM `inventory_part_manufacturers` as inv left join countries c on inv.country = c.id left join states as s on inv.state = s.id WHERE 1=1 $cond ";
            
            return $query;
        }

        public function ajaxInventoryManufacturersearch(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Manufacturers', $actionStatus))
                {
                    $actionItems = $actionStatus['Manufacturers'];
                }
            }
            $this->autoRender = false;
            $this->viewBuilder()->setLayout('ajax');
            $requestData= $this->request->getData();

            $query = $this->search();

            $cond = "";
            
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }
            
            if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
                $search = $requestData['searchItem'];
                $cond.=" AND ( inv.name LIKE '%".$search."%' OR  inv.city LIKE '%".$search."%' OR  inv.province LIKE '%".$search."%' OR  s.name LIKE '%".$search."%' OR inv.primaryphone LIKE '%".$search."%' OR c.name LIKE '%".$search."%')";
            }

            if(!empty($requestData['vendor_state'])){
                $cond.=" AND state = '".$requestData['vendor_state']."'";
            }

            if(!empty($requestData['vendor_country'])){
                $cond.=" AND country = '".$requestData['vendor_country']."'";
            }

            if(isset($requestData['status'])){
                $statusarr = array('1');
                if($requestData['status'] == '2'){
                    $statusarr[] = '0';
                }
                $statusarr = "'" . implode ( "', '", $statusarr ) . "'";
                $cond.=" AND status in (".$statusarr.")";
            }else{
                $cond .= ' AND status="1"';
            }
            
            $requestData= $this->request->getData();
            $columns = array(
                0 => 'name',
                1 => 'city',
                2 => 'state',
                3 => 'country',
                4 => 'primaryphone',
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
            $sort = $requestData['order'][0]['dir'];
            $start = $requestData['start'];
            $length = PAGINATION_LIMIT;

            $SQL = $detail." ORDER BY $sidx $sort LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');

            $data = array();
            $urgency = unserialize(URGENCY);
            foreach ( $results as $row){
                $nestedData= [];
                $nestedData[] = '<input type="hidden" value="'.$row['id'].'" class="chkBoxCls">';
                $nestedData[] = $row["name"];
                $nestedData[] = $row["city"];
                $nestedData[] = !empty($row["province"]) ? $row["province"] : $row['state_name'];
                $nestedData[] = $row["country_name"];
                $nestedData[] = $row["primaryphone"];

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
        public function create()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Manufacturers', $actionStatus))
                {
                    $actionItems = $actionStatus['Manufacturers'];
                }
            }

            $inventorymanufacturers = $this->InventoryPartManufacturers->newEmptyEntity();
            if ($this->request->is('post')) {
                $postData = $this->request->getData();

                $exists = $this->InventoryPartManufacturers->exists([
                    'LOWER(name)' => strtolower($postData['name'])
                ]);

                if (!$exists) {
                    $postData['province'] = $postData['country'] != '231' ? $postData['province'] : '';
                    $postData['state'] = $postData['country'] == '231' ? $postData['state'] : '';
                    $postData['added_by'] = $authUserData['id'];
                    
                    $inventorymanufacturers = $this->InventoryPartManufacturers->patchEntity($inventorymanufacturers, $postData);
                    if ($this->InventoryPartManufacturers->save($inventorymanufacturers)) {
                        $id = $inventorymanufacturers->id;
                        
                        $this->Flash->success(__('The manufacturer has been saved.'));
                        return $this->redirect(['action' => 'detail', $id]);
                    }

                    $this->Flash->error(__('The manufacturer could not be saved. Please, try again.'));
                }else{
                    $this->Flash->error(__('Manufacturer already exists.'));
                }
            }
            
            $countries = $this->Address->getCountryList();
            $states = '';
            $this->set(compact('inventorymanufacturers', 'actionItems', 'countries', 'states'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Manufacturers', $actionStatus))
                {
                    $actionItems = $actionStatus['Manufacturers'];
                }
            }
            $inventorymanufacturers = $this->InventoryPartManufacturers->newEmptyEntity();
            $inventorymanufacturers = $this->InventoryPartManufacturers->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {
                
                $postData = $this->request->getData();
                $exists = $this->InventoryPartManufacturers->exists([
                    'LOWER(name)' => strtolower($postData['name']),
                    'id !=' => $id
                ]);

                if (!$exists) {
                    $postData['province'] = $postData['country'] != '231' ? $postData['province'] : '';
                    $postData['state'] = $postData['country'] == '231' ? $postData['state'] : '';

                    $postData['updated_by'] = $authUserData['id'];
                    $inventorymanufacturers = $this->InventoryPartManufacturers->patchEntity($inventorymanufacturers, $postData);//print_r($part);exit;
                    if ($this->InventoryPartManufacturers->save($inventorymanufacturers)) {
                        $this->Flash->success(__('The manufacturer has been saved.'));
                        return $this->redirect(['action' => 'detail', $id]);
                    }

                    $this->Flash->error(__('The manufacturer could not be saved. Please, try again.'));
                }else{
                    $this->Flash->error(__('Manufacturer already exists.'));
                }
            }
            
            $countries = $this->Address->getCountryList();
            $states = !empty($inventorymanufacturers->country) ? $this->Address->getStateListByCountryId($inventorymanufacturers->country) : '';
            $this->set(compact('inventorymanufacturers', 'actionItems', 'countries', 'states'));
        }

        public function detail($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Manufacturers', $actionStatus))
                {
                    $actionItems = $actionStatus['Manufacturers'];
                }
            }
            
            $inventorymanufacturers = $this->InventoryPartManufacturers->get($id);
            
            $countries = $this->Address->getCountryList();
            $states = !empty($inventorymanufacturers->country) ? $this->Address->getStateListByCountryId($inventorymanufacturers->country) : '';
            $this->set(compact('inventorymanufacturers', 'actionItems', 'countries', 'states'));
        }

        public function saveInventoryManufacturer(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                
                $authUserData = $this->Authentication->getResult()->getData();
                $postData = $this->request->getData();
                $postData['added_by'] = $authUserData['id'];

                $exists = $this->InventoryPartManufacturers->exists([
                    'LOWER(name)' => strtolower($postData['name'])
                ]);

                if (!$exists) {
                    $inventorypartmanufacturers = $this->InventoryPartManufacturers->newEmptyEntity();
                    $inventorypartmanufacturers = $this->InventoryPartManufacturers->patchEntity($inventorypartmanufacturers, $postData);
                    if ($this->InventoryPartManufacturers->save($inventorypartmanufacturers)) {
                        $id = $inventorypartmanufacturers->id;

                        $invmanufacturers = array('id'=>$id, 'name'=>$postData['name']);
                        $result = array('status'=>'success', 'message'=>"Manufacturer saved successfully.", 'invmanufacturers'=>$invmanufacturers);
                    } else {
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Manufacturer already exists.');
                }

                echo json_encode($result);die;
            }
        }

        public function updateManufacturerStatus($id=null){
            $this->request->allowMethod(['post', 'delete']);
            $id = $_POST['id'];
            $inventorymanufacturers = $this->InventoryPartManufacturers->get($id);
            if(empty($inventorymanufacturers)){
                $msg = 'Invalid request.';
                $this->Flash->error(__($msg));

                return $this->redirect(['action' => 'detail', $id]);
            }
            try {
                $postData = array();
                $status = $_POST['status'];
                
                $postData['status'] = $status;
                $inventorymanufacturers = $this->InventoryPartManufacturers->patchEntity($inventorymanufacturers, $postData);
                if ($this->InventoryPartManufacturers->save($inventorymanufacturers)) {
                    if($status == '1'){
                        $msg = 'Manufacturer `'.$inventorymanufacturers->name.'` Activated.`';
                    }else{
                        $msg = 'Manufacturer `'.$inventorymanufacturers->name.'` Deactivated.`';
                    }
                    
                    $this->Flash->success(__($msg));
                } else {
                    $msg = 'Something went wrong!';
                    $this->Flash->error(__($msg));
                }
            } catch(\PDOException $e) {
                $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
            } catch (\Exception $e) {
                $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
            }

            return $this->redirect(['action' => 'detail', $id]);
        }

    }

?>