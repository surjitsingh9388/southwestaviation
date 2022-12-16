<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;

    class PartsController extends AppController
    {
        public function initialize() {
            parent::initialize();
            $this->loadModel('Users');
            $this->loadModel('Planes');
        }

        public function index()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
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

            $query['detail'] = "SELECT Parts.id, Parts.`serial_number`, Parts.`description`, Parts.`location`, Parts.`shelf_life`, Parts.`lot`, Parts.`owner_of_part`, Parts.`part_classification`, Parts.`qty` FROM `parts` Parts WHERE 1=1 ";
            
            return $query;
        }

        public function ajaxManagePartsSearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Parts', $actionStatus))
                {
                    $actionItems = $actionStatus['Parts'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = $this->search();

            $cond = "";
            if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
                $search = $requestData['search']['value'];
                $cond.=" AND ( Parts.serial_number LIKE '%".$search."%' OR  Parts.description LIKE '%".$search."%'
                )";
            }

            $columns = array(
                0 => 'Parts.id',
                1 => 'Parts.serial_number',
                2 => 'Parts.description',
                3 => 'Parts.location',
                4 => 'Parts.shelf_life',
                5 => 'Parts.lot',
                6 => 'Parts.owner_of_part',
                7 => 'Parts.part_classification',
                8 => 'Parts.qty',
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

            //$sidx = $columns[$requestData['order'][0]['column']];
            //$sord = $requestData['order'][0]['dir'];
            $start = $requestData['start'];
            $length = PAGINATION_LIMIT;

            //$SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
            $SQL = $detail." ORDER BY id desc LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');

            $j=1;
            
            $data = array();
            $view = $edit = $delete = '';
            foreach ( $results as $row){
                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls" name="childcheckbox" value="'.$row['id'].'" >';
                $nestedData[] = $row["serial_number"];
                $nestedData[] = $row["description"];
                $nestedData[] = $row["location"];
                $nestedData[] = $row["shelf_life"];
                $nestedData[] = $row["lot"];
                $nestedData[] = $row["owner_of_part"];
                $nestedData[] = $row["part_classification"];
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
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Parts', $actionStatus))
                {
                    $actionItems = $actionStatus['Parts'];
                }
            }
            $part = $this->Parts->newEntity();
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                $part = $this->Parts->patchEntity($part, $postData);
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
            if($this->Auth->user('id') != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Parts', $actionStatus))
                {
                    $actionItems = $actionStatus['Parts'];
                }
            }
            $part = $this->Parts->get($id, [
                'contain' => []
            ]);
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
    }

?>