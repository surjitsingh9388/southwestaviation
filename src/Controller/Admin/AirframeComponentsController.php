<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;

/**
 * AirframeComponents Controller
 *
 * @property \App\Model\Table\AirframeComponentsTable $AirframeComponents
 *
 * @method \App\Model\Entity\AirframeComponent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeComponentsController extends AppController
{
    private $airCompTimeObj;
    private $airCompPartObj;
    private $airCompLastCwObj;
    private $groupObj;
    private $partInstTimeObj;

    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Plane');

        $this->airCompTimeObj   = TableRegistry::get('AirframeComponentTimes');
        $this->airCompPartObj   = TableRegistry::get('AirframeComponentParts');
        $this->airCompLastCwObj = TableRegistry::get('AirframeComponentLastCw');
        $this->groupObj         = TableRegistry::get('Groups');
        $this->partInstTimeObj  = TableRegistry::get('PartInstalledTimes'); 
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft Components', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft Components'];
            }
        }

        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanesWithCode();
        //pr($planes);die;
        $this->set(compact('actionItems', 'planes'));
    }

    public function search()
    {
        $query = array();
        $airIds = $this->Plane->getSelectedAircraft();
        $query['count']  = "SELECT count( AirframeComponents.`id`) AS count  FROM `airframe_components` AirframeComponents LEFT JOIN `planes` Planes ON AirframeComponents.`plane_id` = Planes.`id` WHERE ".$airIds['airIds'];

        $query['detail'] = "SELECT AirframeComponents.`id`, AirframeComponents.`log_book`, AirframeComponents.`position`, AirframeComponents.`serial_no`, Planes.`plane_code` FROM `airframe_components` AirframeComponents LEFT JOIN `planes` Planes ON AirframeComponents.`plane_id` = Planes.`id` WHERE ".$airIds['airIds'];
        
        return $query;
    }

    public function ajaxManageComponentsSearch() {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft Components', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft Components'];
            }
        }
        $this->autoRender = false;
        $this->layout = 'ajax';
        $requestData= $this->request->data;

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Planes.plane_code LIKE '%".$search."%' OR  AirframeComponents.log_book LIKE '%".$search."%' OR  AirframeComponents.position LIKE '%".$search."%' OR  AirframeComponents.serial_no LIKE '%".$search."%'
            )";
        }

        $columns = array(
            0 => 'AirframeComponents.id',
            1 => 'Planes.plane_code',
            2 => 'AirframeComponents.log_book',
            3 => 'AirframeComponents.position', 
            4 => 'AirframeComponents.serial_no'
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
        $sortby = $columns[$requestData['order'][0]['column']].' '.$requestData['order'][0]['dir'];
        if(!empty($requestData['order'][1]['column'])){
            $sortby .= ', '.$columns[$requestData['order'][1]['column']].' '.$requestData['order'][1]['dir'];
        }
        $start = $requestData['start'];
        $length = PAGINATION_LIMIT;

        $SQL = $detail." ORDER BY $sortby LIMIT $start , $length ";
        $results = $conn->execute( $SQL )->fetchAll('assoc');

        $i = 0;
        $j=1;
        
        $data = array();
        $view = $edit = $delete = '';
        foreach ($results as $row) {
            $nestedData= [];
            $nestedData[] = $j++;
            $nestedData[] = $row["plane_code"];
            $nestedData[] = $row["log_book"];
            $nestedData[] = $row["position"];
            $nestedData[] = $row["serial_no"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $this->Auth->user('id') == 1){
                $view = '<a href="airframe_components/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $this->Auth->user('id') == 1){
                $edit = ' <a href="airframe_components/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $this->Auth->user('id') == 1){
                $delete = ' <a data-airframe_components_id="'.$row['id'].'" data-url="airframe_components/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete '.$row["log_book"].' Component?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $airComp = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($airComp);die;
    }

    /**
     * View method
     *
     * @param string|null $id Plane id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $airComp = $this->AirframeComponents->get($id, [
                'contain' => ['Planes']
            ]);
        $this->set('airComp', $airComp);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft Components', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft Components'];
            }
        }
        $airComp = $this->AirframeComponents->newEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();

            $airComp = $this->AirframeComponents->patchEntity($airComp, $postData);
            if ($this->AirframeComponents->save($airComp)) {
                $this->Flash->success(__('The Aircraft Component has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Aircraft Component could not be saved. Please, try again.'));
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $this->set(compact('airComp', 'actionItems', 'planes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AirframeComponent id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft Components', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft Components'];
            }
        }
        $airComp = $this->AirframeComponents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $airComp = $this->AirframeComponents->patchEntity($airComp, $postData);
            if ($this->AirframeComponents->save($airComp)) {
                $this->Flash->success(__('The Aircraft component has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Aircraft component could not be saved. Please, try again.'));
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $this->set(compact('airComp', 'actionItems', 'planes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeComponent id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);

        //Delete Groups
        $grp = array('Groups.airframe_component_id' => $id);
        $this->groupObj->deleteAll($grp,false);

        //Delete installed/uninstalled parts data
        $partInstTime = array('PartInstalledTimes.airframe_component_id' => $id);
        $this->partInstTimeObj->deleteAll($partInstTime,false);

        //Delete Aircraft Parts related data
        $airCompLastCw = array('AirframeComponentLastCw.airframe_component_id' => $id);
        $this->airCompLastCwObj->deleteAll($airCompLastCw,false);

        //Delete Aircraft Component Parts data
        $allParts = $this->airCompPartObj->find()
                        ->select('id')
                        ->where(['AirframeComponentParts.airframe_component_id'=>$id])
                        ->enableHydration(false)->toArray();
        
        $partIds = [];
        if(!empty($allParts)) {
            foreach ($allParts as $key => $value) {
                $partIds[] = $value['id'];
            }

            //Delete Aircraft Parts related data
            $airCompLastCw = array('AirframeComponentLastCw.airframe_component_part_id IN'=>$partIds);
            $this->airCompLastCwObj->deleteAll($airCompLastCw,false);
        }
        
        $airCompPart = array('AirframeComponentParts.id IN'=>$partIds);
        $this->airCompPartObj->deleteAll($airCompPart,false);

        //Delete Aircraft Component Times data
        $airCompTime = array('AirframeComponentTimes.airframe_component_id' => $id);
        $this->airCompTimeObj->deleteAll($airCompTime,false);

        $airComp = $this->AirframeComponents->get($id);
        try {
            if ($this->AirframeComponents->delete($airComp)) {
                $this->Flash->success(__('The Aircraft component has been deleted.'));
            } else {
                $this->Flash->error(__('The Aircraft component could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

    //Get Airframe Components
    public function getComponents() {
        $this->layout = 'ajax';
        $planeId = $this->request->data['planeId'];
        
        $airComps = $this->AirframeComponents->find('all')->where(['AirframeComponents.plane_id' => $planeId])->select(['id', 'log_book', 'position'])->enableHydration(false)->toArray();
        $results = array();
        foreach ($airComps as $key => $value) {
            if($value['log_book'] == 'Airframe') {
                $results[$value['id']] = $value['log_book'];
            } else {
                $results[$value['id']] = $value['log_book'].$value['position'];
            }
        }
        $this->set('airComps', $results);
    }


}
