<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * SubComponents Controller
 *
 * @property \App\Model\Table\SubComponentsTable $SubComponents
 *
 * @method \App\Model\Entity\SubComponents[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SubComponentsController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    protected \App\Model\Table\SubComponentsTable $SubComponents;

    public function initialize():void {
        parent::initialize();

        $this->SubComponents = $this->fetchTable('SubComponents');

        $this->loadComponent('Plane');
        $this->loadComponent('AirframeComponent');
        $this->loadComponent('SubComponent');
        $this->loadComponent('AircraftHistory');
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
            if(array_key_exists('Sub Components', $actionStatus))
            {
                $actionItems = $actionStatus['Sub Components'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $airIds = $this->Plane->getSelectedAircraft();
        $query['count']  = "SELECT count( SubComponents.`id`) AS count  FROM `sub_components` SubComponents LEFT JOIN `planes` Planes ON SubComponents.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON SubComponents.`airframe_component_id` = AirframeComponents.`id` WHERE `parent_id`=0 AND ".$airIds['airIds'];

        $query['detail'] = "SELECT SubComponents.`id`, SubComponents.`title`, Planes.`plane_code`, AirframeComponents.`log_book` FROM `sub_components` SubComponents LEFT JOIN `planes` Planes ON SubComponents.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON SubComponents.`airframe_component_id` = AirframeComponents.`id` WHERE `parent_id`=0 AND ".$airIds['airIds'];
        
        return $query;
    }

    public function ajaxManageSubCompSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();

            if(array_key_exists('Sub Components', $actionStatus))
            {
                $actionItems = $actionStatus['Sub Components'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Planes.plane_code LIKE '%".$search."%' OR AirframeComponents.log_book LIKE '%".$search."%' OR  SubComponents.title LIKE '%".$search."%'
            )";
        }

        $columns = array(
            0 => 'SubComponents.id',
            1 => 'Planes.plane_code',
            2 => 'AirframeComponents.log_book',
            3 => 'SubComponents.title'
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
        foreach ($results as $row) {
            $nestedData= [];
            $nestedData[] = $j++;
            $nestedData[] = $row["plane_code"];
            $nestedData[] = $row["log_book"];
            $nestedData[] = $row["title"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                $view = '<a href="sub_components/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="sub_components/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-sub_component_id="'.$row['id'].'" data-url="sub_components/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete '.$row["log_book"].' Sub Component?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $subComps = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($subComps);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeComponentTime id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $subComps = $this->SubComponents->get($id, [
                'contain' => [
                        'Planes'=>[
                            'fields'=>[
                                'Planes.id', 'Planes.plane_code'
                            ]
                        ], 
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id', 
                                'AirframeComponents.log_book'
                            ]
                        ]
                    ]
            ]);
        $this->set('subComps', $subComps);
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
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Sub Components', $actionStatus))
            {
                $actionItems = $actionStatus['Sub Components'];
            }
        }
        $subComps = $this->SubComponents->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            
            $subComps = $this->SubComponents->patchEntity($subComps, $postData);

            if ($this->SubComponents->save($subComps)) {
                //save to aircraft history table
                $this->AircraftHistory->saveSubComponentHistory($subComps);

                $this->Flash->success(__('The sub component has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sub component could not be saved. Please, try again.'));
        }
        
        $planes = $this->Plane->getAllPlanes();
        $airComps = $this->AirframeComponent->getAirComps();
        $this->set(compact('subComps', 'actionItems', 'planes', 'airComps'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AirframeComponentTime id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Sub Components', $actionStatus))
            {
                $actionItems = $actionStatus['Sub Components'];
            }
        }
        $subComps = $this->SubComponents->get($id, [
            'contain' => ['Planes']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $subComps = $this->SubComponents->patchEntity($subComps, $postData);
            if ($this->SubComponents->save($subComps)) {
                $this->Flash->success(__('The sub component has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sub component could not be saved. Please, try again.'));
        }
        
        $planes = $this->Plane->getAllPlanes();
        $airComps = $this->AirframeComponent->getRAirComps($subComps->plane_id);
        $this->set(compact('subComps', 'actionItems', 'planes', 'airComps'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeComponentTime id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $airCompCats = $this->SubComponents->get($id);
        try {
            if ($this->SubComponents->delete($airCompCats)) {
                $this->Flash->success(__('The sub component has been deleted.'));
            } else {
                $this->Flash->error(__('The sub component could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }


    /*********************** Sub Component 2 ****************************/

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index2()
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Sub Components', $actionStatus))
            {
                $actionItems = $actionStatus['Sub Components'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search2()
    {
        $query = array();
        $airIds = $this->Plane->getSelectedAircraft();
        $query['count']  = "SELECT count( SubComponents.`id`) AS count  FROM `sub_components` SubComponents LEFT JOIN `planes` Planes ON SubComponents.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON SubComponents.`airframe_component_id` = AirframeComponents.`id` WHERE `parent_id` != 0 AND ".$airIds['airIds'];

        $query['detail'] = "SELECT SubComponents.`id`, SubComponents.`title`, SubComponents.`parent_id`, Planes.`plane_code`, AirframeComponents.`log_book` FROM `sub_components` SubComponents LEFT JOIN `planes` Planes ON SubComponents.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON SubComponents.`airframe_component_id` = AirframeComponents.`id` WHERE `parent_id` != 0 AND ".$airIds['airIds'];
        
        return $query;
    }

    public function ajaxManageSubComp2Search() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();

            if(array_key_exists('Sub Components', $actionStatus))
            {
                $actionItems = $actionStatus['Sub Components'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search2();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Planes.plane_code LIKE '%".$search."%' OR AirframeComponents.log_book LIKE '%".$search."%' OR  SubComponents.title LIKE '%".$search."%'
            )";
        }

        $columns = array(
            0 => 'SubComponents.id',
            1 => 'Planes.plane_code',
            2 => 'AirframeComponents.log_book',
            3 => 'SubComponents.parent_id',
            4 => 'SubComponents.title'
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
        foreach ($results as $row) {
            $nestedData= [];
            $nestedData[] = $j++;
            $nestedData[] = $row["plane_code"];
            $nestedData[] = $row["log_book"];
            $nestedData[] = $this->SubComponent->subCompName($row["parent_id"]);
            $nestedData[] = $row["title"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                $view = '<a href="view2/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="edit2/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-sub_component_id="'.$row['id'].'" data-url="delete2" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete '.$row["log_book"].' Sub Component?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $subComps = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($subComps);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeComponentTime id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view2($id = null)
    {
        $subComps = $this->SubComponents->get($id, [
                'contain' => [
                        'Planes'=>[
                            'fields'=>[
                                'Planes.id', 'Planes.plane_code'
                            ]
                        ], 
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id', 
                                'AirframeComponents.log_book'
                            ]
                        ]
                    ]
            ]);
        //pr($subComps);die;
        $subCompM = $this->SubComponent;
        $this->set(compact('subComps', 'subCompM'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add2()
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Sub Components', $actionStatus))
            {
                $actionItems = $actionStatus['Sub Components'];
            }
        }
        $subComps = $this->SubComponents->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            
            $subComps = $this->SubComponents->patchEntity($subComps, $postData);

            if ($this->SubComponents->save($subComps)) {
                //save to aircraft history table
                $this->AircraftHistory->saveSubComponent2History($subComps);
                
                $this->Flash->success(__('The sub component has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sub component could not be saved. Please, try again.'));
        }
        
        $planes = $this->Plane->getAllPlanes();
        $airComps = $this->AirframeComponent->getAirComps();
        $this->set(compact('subComps', 'actionItems', 'planes', 'airComps'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AirframeComponentTime id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit2($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Sub Components', $actionStatus))
            {
                $actionItems = $actionStatus['Sub Components'];
            }
        }
        $subComps = $this->SubComponents->get($id, [
            'contain' => ['Planes']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $subComps = $this->SubComponents->patchEntity($subComps, $postData);
            if ($this->SubComponents->save($subComps)) {
                $this->Flash->success(__('The sub component has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sub component could not be saved. Please, try again.'));
        }
        
        $planes = $this->Plane->getAllPlanes();
        $airComps = $this->AirframeComponent->getRAirComps($subComps->plane_id);
        $subCompArr = $this->SubComponent->getSubComps($subComps->plane_id, $subComps->parent_id);
        $this->set(compact('subComps', 'actionItems', 'planes', 'airComps', 'subCompArr'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeComponentTime id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete2($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $airCompCats = $this->SubComponents->get($id);
        try {
            if ($this->SubComponents->delete($airCompCats)) {
                $this->Flash->success(__('The sub component has been deleted.'));
            } else {
                $this->Flash->error(__('The sub component could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

    //Get Sub Components
    public function getSubComponents() {
        $this->viewBuilder()->setLayout('ajax');
        $compId = $this->request->getData()['compId'];
        $subComps = $this->SubComponents->find('all')->where(['SubComponents.airframe_component_id'=>$compId, 'SubComponents.parent_id'=>0])->select(['id', 'title'])->enableHydration(false)->toArray();
        //pr($subComps);
        $results = array();
        foreach ($subComps as $key => $value) {
            $results[$value['id']] = $value['title'];
        }
        $this->set('subComps', $results);
    }

}
