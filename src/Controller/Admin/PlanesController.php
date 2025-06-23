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

/**
 * Planes Controller
 *
 * @property \App\Model\Table\PlanesTable $Planes
 *
 * @method \App\Model\Entity\Plane[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PlanesController extends AppController
{
    /*private $airCatObj;
    private $airCompObj;
    private $airCompTimeObj;
    private $airCompPartObj;
    private $airCompLastCwObj;
    private $groupObj;
    private $partInstTimeObj;*/

    protected \App\Model\Table\AirframeCategoriesTable $airCatObj;
    protected \App\Model\Table\AirframeComponentsTable $airCompObj;
    protected \App\Model\Table\AirframeComponentTimesTable $airCompTimeObj;
    protected \App\Model\Table\AirframeComponentPartsTable $airCompPartObj;
    protected \App\Model\Table\AirframeComponentLastCwTable $airCompLastCwObj;
    protected \App\Model\Table\GroupsTable $groupObj;
    protected \App\Model\Table\PartInstalledTimesTable $partInstTimeObj;
    protected \App\Model\Table\PlaneHistoriesTable $PlaneHistories;
    protected \App\Model\Table\AirframeComponentHistoriesTable $AirframeComponentHistories;
    protected \App\Model\Table\SubComponentHistoriesTable $SubComponentHistories;
    protected \App\Model\Table\AirframeComponentTimeHistoriesTable $AirframeComponentTimeHistories;
    protected \App\Model\Table\AirframeComponentPartHistoriesTable $AirframeComponentPartHistories;

    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize():void {
        parent::initialize();

        $this->airCatObj        = $this->fetchTable('AirframeCategories');
        $this->airCompObj       = $this->fetchTable('AirframeComponents');
        $this->airCompTimeObj   = $this->fetchTable('AirframeComponentTimes');
        $this->airCompPartObj   = $this->fetchTable('AirframeComponentParts');
        $this->airCompLastCwObj = $this->fetchTable('AirframeComponentLastCw');
        $this->groupObj         = $this->fetchTable('Groups');
        $this->partInstTimeObj  = $this->fetchTable('PartInstalledTimes'); 
        $this->PlaneHistories  = $this->fetchTable('PlaneHistories'); 
        $this->AirframeComponentHistories  = $this->fetchTable('AirframeComponentHistories'); 
        $this->AirframeComponentPartHistories  = $this->fetchTable('AirframeComponentPartHistories'); 
        $this->AirframeComponentTimeHistories  = $this->fetchTable('AirframeComponentTimeHistories'); 
        $this->SubComponentHistories  = $this->fetchTable('SubComponentHistories'); 

        $this->loadComponent('AircraftHistory');
        $this->loadComponent('Plane');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = [];        
        $airIds = $this->Plane->getSelectedAircraft();
        $query['count'] = "SELECT count(Planes.id) AS count  FROM `planes` Planes WHERE ".$airIds['airIds'];

        $query['detail'] = "SELECT Planes.id, Planes.`plane_name`, Planes.`plane_type`, Planes.`plane_code`, Planes.`plane_serial_number`, Planes.`airworthiness_date`, Planes.`hours`, Planes.`cycles` FROM `planes` Planes WHERE ".$airIds['airIds'];
        
        return $query;
    }

    public function ajaxManagePlanesSearch(){
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Planes.plane_name LIKE '%".$search."%' OR  Planes.plane_type LIKE '%".$search."%' OR  Planes.plane_code LIKE '%".$search."%' OR Planes.plane_serial_number LIKE '%".$search."%' OR  Planes.airworthiness_date LIKE '%".$search."%' OR  Planes.hours LIKE '%".$search."%' OR  Planes.cycles LIKE '%".$search."%'
            )";
        }

        $columns = array(
            0 => 'Planes.id',
            1 => 'Planes.plane_code',
            2 => 'Planes.plane_type',
            3 => 'Planes.plane_serial_number',
            4 => 'Planes.airworthiness_date',
            5 => 'Planes.plane_name',
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

        $i = 0;
        $j=1;
        
        $data = array();
        $view = $edit = $delete = '';
        foreach ( $results as $row){
            $nestedData= [];
            $nestedData[] = $j++;
            $nestedData[] = $row["plane_code"];
            $nestedData[] = $row["plane_type"];
            $nestedData[] = $row["plane_serial_number"];
            $nestedData[] = !empty($row["airworthiness_date"]) ? date('d-M-Y', strtotime($row["airworthiness_date"])) : '';
            $nestedData[] = $row["plane_name"];            
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                $view = '<a href="planes/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="planes/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = '<a data-plane_id="'.$row['id'].'" data-url="planes/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete the Aircraft? NOTE: It will delete all the related Components and Parts of this Aircraft."><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
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
     * View method
     *
     * @param string|null $id Plane id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $plane = $this->Planes->get($id);
        $this->set('plane', $plane);
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
            if(array_key_exists('Aircraft', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft'];
            }
        }
        $plane = $this->Planes->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            
            $plane = $this->Planes->patchEntity($plane, $postData);
            if ($this->Planes->save($plane)) {
                //save to aircraft history table
                $this->AircraftHistory->savePlaneHistory($plane);

                $this->Flash->success(__('The aircraft has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The aircraft could not be saved. Please, try again.'));
        }
        $this->set(compact('plane', 'actionItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Plane id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft'];
            }
        }
        $plane = $this->Planes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $plane = $this->Planes->patchEntity($plane, $postData);
            if ($this->Planes->save($plane)) {
                $this->Flash->success(__('The aircraft has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The aircraft could not be saved. Please, try again.'));
        }
        $this->set(compact('plane', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Plane id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);

        //Delete Groups
        $grp = array('Groups.plane_id' => $id);
        $this->groupObj->deleteAll($grp,false);

        //remove history data from table
        $partInstTimeHistory = array('AirframeComponentTimeHistories.plane_id' => $id);
        $this->AirframeComponentTimeHistories->deleteAll($partInstTimeHistory,false);

        $airCompPartHistory = array('AirframeComponentPartHistories.plane_id' => $id);
        $this->AirframeComponentPartHistories->deleteAll($airCompPartHistory,false);

        $airCompHistory = array('AirframeComponentHistories.plane_id' => $id);
        $this->AirframeComponentHistories->deleteAll($airCompHistory,false);

        $connection = ConnectionManager::get('default');
        $connection->execute('DELETE FROM airframe_component_part_files WHERE plane_id = :id', ['id' => $id]);
        
        //Delete installed/uninstalled parts data
        $partInstTime = array('PartInstalledTimes.plane_id' => $id);
        $this->partInstTimeObj->deleteAll($partInstTime,false);

        //Delete Aircraft Parts related data
        $airCompLastCw = array('AirframeComponentLastCw.plane_id' => $id);
        $this->airCompLastCwObj->deleteAll($airCompLastCw,false);

        //Delete Aircraft Component Parts data
        $airCompPart = array('AirframeComponentParts.plane_id' => $id);
        $this->airCompPartObj->deleteAll($airCompPart,false);

        //Delete Aircraft Component Times data
        $airCompTime = array('AirframeComponentTimes.plane_id' => $id);
        $this->airCompTimeObj->deleteAll($airCompTime,false);

        //Delete Aircraft Components data
        $airComp = array('AirframeComponents.plane_id' => $id);
        $this->airCompObj->deleteAll($airComp,false);

        //Delete Aircraft Components data
        $airCat = array('AirframeCategories.plane_id' => $id);
        $this->airCatObj->deleteAll($airCat,false);

        $plane = $this->Planes->get($id);
        try {
            $planeHistory = array('PlaneHistories.plane_id' => $id);
            $this->PlaneHistories->deleteAll($planeHistory,false);
            
            if ($this->Planes->delete($plane)) {
                $this->Flash->success(__('The aircraft has been deleted.'));
            } else {
                $this->Flash->error(__('The aircraft could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

    /**
     * IsPlaneExist method
     * This function is used to check is plane name already exist.
     * 
     * @return boolean true/false
     */
    public function isPlaneExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $planeCode = $this->request->getData('plane_code');

            $exists = $this->Planes->exists(['plane_code' => trim($planeCode)]);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    //Get all aircraft details
    public function allAircraftDetails()
    {
        $results = $this->Planes->find()
                    ->select(['id', 'plane_code', 'plane_type', 'plane_serial_number'])
                    ->enableHydration(false)->toArray();

        $aircraftHtml = '';
        if(!empty($results)) {
            $aircraftHtml = '<div class="action-bar">
                <table id="aircraftUtilization" class="table table-hover table-header-dark">
                    <thead>
                        <tr>
                            <th width="10%"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                            <th width="20%">Registration</th>
                            <th width="10%">Div</th>
                            <th width="20%">Make & Model</th>
                            <th width="20%">Serial Number</th>
                            <th width="20%">&nbsp</th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach ($results as $key => $value) {
                $aircraftHtml .= '<tr>
                        <td><input type="checkbox" class="chkBoxCls" name="childcheckbox" value=""></td>
                        <td>'.$value['plane_code'].'</td>
                        <td> - </td>
                        <td>'.$value['plane_type'].'</td>
                        <td>'.$value['plane_serial_number'].'</td>
                        <td><i class="fa fa-clock-o" aria-hidden="true"></i></td>
                    </tr>';            
            }

            $aircraftHtml .= '</tbody>
                    </table>
                </div>';

            $result = array('status'=>'success', 'data'=>$aircraftHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$aircraftHtml);
            echo json_encode($result);die;
        }
    }

}
