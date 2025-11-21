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
    use Cake\I18n\FrozenTime;

    class InventoryVendorsController extends AppController
    {
        public function initialize():void {
            parent::initialize();
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
                if(array_key_exists('Vendors', $actionStatus))
                {
                    $actionItems = $actionStatus['Vendors'];
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
            $query['count'] = "SELECT count(inv.id) AS count  FROM `inventory_vendors` as inv left join countries c on inv.country = c.id left join states as s on inv.state = s.id WHERE 1=1 $cond ";

            $query['detail'] = "SELECT inv.id, inv.name, inv.city, inv.province, inv.country, inv.primaryphone, c.name as country_name, s.name as state_name FROM `inventory_vendors` as inv left join countries c on inv.country = c.id left join states as s on inv.state = s.id WHERE 1=1 $cond ";
            
            return $query;
        }

        public function ajaxInventoryVendorsearch(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Vendors', $actionStatus))
                {
                    $actionItems = $actionStatus['Vendors'];
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
            
            if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
                $search = $requestData['searchItem'];
                $cond.=" AND ( inv.name LIKE '%".$search."%' OR  inv.city LIKE '%".$search."%' OR  inv.province LIKE '%".$search."%' OR  s.name LIKE '%".$search."%' OR inv.primaryphone LIKE '%".$search."%' OR c.name LIKE '%".$search."%')"; 
            }
            $requestData= $this->request->getData();
            $columns = array(
                0 => 'name',
                1 => 'city',
                2 => 'province',
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
                if(array_key_exists('Vendors', $actionStatus))
                {
                    $actionItems = $actionStatus['Vendors'];
                }
            }

            $inventoryvendors = $this->InventoryVendors->newEmptyEntity();
            if ($this->request->is('post')) {
                $postData = $this->request->getData();

                $exists = $this->InventoryVendors->exists([
                    'LOWER(name)' => strtolower($postData['name'])
                ]);

                if (!$exists) {
                    $postData['province'] = $postData['country'] != '231' ? $postData['province'] : '';
                    $postData['state'] = $postData['country'] == '231' ? $postData['state'] : '';
                    $postData['added_by'] = $authUserData['id'];

                    $inventoryvendors = $this->InventoryVendors->patchEntity($inventoryvendors, $postData);
                    if ($this->InventoryVendors->save($inventoryvendors)) {
                        $id = $inventoryvendors->id;
                        
                        $this->Flash->success(__('The vendor has been saved.'));
                        return $this->redirect(['action' => 'detail', $id]);
                    }

                    $this->Flash->error(__('The vendor could not be saved. Please, try again.'));
                }else{
                    $this->Flash->error(__('Vendor already exists.'));
                }
            }
            
            $countries = $this->Address->getCountryList();
            $states = '';
            $this->set(compact('inventoryvendors', 'actionItems', 'countries', 'states'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Vendors', $actionStatus))
                {
                    $actionItems = $actionStatus['Vendors'];
                }
            }
            
            $inventoryvendors = $this->InventoryVendors->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {
                
                $postData = $this->request->getData();

                $exists = $this->InventoryVendors->exists([
                    'LOWER(name)' => strtolower($postData['name']),
                    'id !=' => $id
                ]);

                if (!$exists) {
                    $postData['province'] = $postData['country'] != '231' ? $postData['province'] : '';
                    $postData['state'] = $postData['country'] == '231' ? $postData['state'] : '';

                    $postData['updated_by'] = $authUserData['id'];

                    $inventoryvendors = $this->InventoryVendors->patchEntity($inventoryvendors, $postData);//print_r($part);exit;
                    if ($this->InventoryVendors->save($inventoryvendors)) {
                        $this->Flash->success(__('The vendor has been saved.'));
                        return $this->redirect(['action' => 'detail', $id]);
                    }

                    $this->Flash->error(__('The vendor could not be saved. Please, try again.'));
                }else{
                    $this->Flash->error(__('Vendor already exists.'));
                }
            }
            
            $countries = $this->Address->getCountryList();
            $states = !empty($inventoryvendors->country) ? $this->Address->getStateListByCountryId($inventoryvendors->country) : '';
            $this->set(compact('inventoryvendors', 'actionItems', 'countries', 'states'));
        }
        
        public function detail($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Vendors', $actionStatus))
                {
                    $actionItems = $actionStatus['Vendors'];
                }
            }
            
            $inventoryvendors = $this->InventoryVendors->get($id);
            
            $countries = $this->Address->getCountryList();
            $states = !empty($inventoryvendors->country) ? $this->Address->getStateListByCountryId($inventoryvendors->country) : '';
            $this->set(compact('inventoryvendors', 'actionItems', 'countries', 'states'));
        }

        public function saveInventoryVendor(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                
                $authUserData = $this->Authentication->getResult()->getData();
                $postData = $this->request->getData();

                $exists = $this->InventoryVendors->exists([
                    'LOWER(name)' => strtolower($postData['name'])
                ]);

                if (!$exists) {
                    $postData['added_by'] = $authUserData['id'];
                    $inventoryvendors = $this->InventoryVendors->newEmptyEntity();
                    $inventoryvendors = $this->InventoryVendors->patchEntity($inventoryvendors, $postData);
                    if ($this->InventoryVendors->save($inventoryvendors)) {
                        $id = $inventoryvendors->id;

                        $invvendor = array('id'=>$id, 'name'=>$postData['name']);
                        $result = array('status'=>'success', 'message'=>"Saved successfully.", 'invvendor'=>$invvendor);
                    } else {
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Vendor already exists.');
                }

                echo json_encode($result);die;
            }
        }

        public function updatevendorstatus($id=null){
            $this->request->allowMethod(['post', 'delete']);
            $id = $_POST['id'];
            $inventoryvendors = $this->InventoryVendors->get($id);
            if(empty($inventoryvendors)){
                $msg = 'Invalid request.';
                $this->Flash->error(__($msg));

                return $this->redirect(['action' => 'detail', $id]);
            }
            try {
                $postData = array();
                $status = $_POST['status'];
                
                $postData['status'] = $status;
                $inventoryvendors = $this->InventoryVendors->patchEntity($inventoryvendors, $postData);
                if ($this->InventoryVendors->save($inventoryvendors)) {
                    if($status == '0'){
                        $msg = 'Vendor `'.$inventoryvendors->name.'` Deactivated.`';
                    }else{
                        $msg = 'Vendor `'.$inventoryvendors->name.'` Activated.`';
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