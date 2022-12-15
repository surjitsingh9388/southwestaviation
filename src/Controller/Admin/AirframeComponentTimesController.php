<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;

/**
 * AirframeComponentTimes Controller
 *
 * @property \App\Model\Table\AirframeComponentTimesTable $AirframeComponentTimes
 *
 * @method \App\Model\Entity\AirframeComponentTime[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeComponentTimesController extends AppController
{
    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('AirframeComponent');
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
            if(array_key_exists('Airframe Component Times', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Times'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $airIds = $this->Plane->getSelectedAircraft();
        $query['count']  = "SELECT count( AirframeComponentTimes.`id`) AS count  FROM `airframe_component_times` AirframeComponentTimes LEFT JOIN `planes` Planes ON AirframeComponentTimes.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON AirframeComponentTimes.`airframe_component_id` = AirframeComponents.`id` WHERE ".$airIds['airIds'];

        $query['detail'] = "SELECT AirframeComponentTimes.`id`, Planes.`plane_code`, AirframeComponents.`log_book`,  AirframeComponentTimes.`log_date`, AirframeComponentTimes.`hours`, AirframeComponentTimes.`cycles` FROM `airframe_component_times` AirframeComponentTimes LEFT JOIN `planes` Planes ON AirframeComponentTimes.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON AirframeComponentTimes.`airframe_component_id` = AirframeComponents.`id` WHERE ".$airIds['airIds'];
        
        return $query;
    }

    public function ajaxManageCompTimesSearch() {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();

            if(array_key_exists('Airframe Component Times', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Times'];
            }
        }
        $this->autoRender = false;
        $this->layout = 'ajax';
        $requestData= $this->request->data;

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Planes.plane_code LIKE '%".$search."%' OR AirframeComponents.log_book LIKE '%".$search."%' OR  AirframeComponentTimes.log_date LIKE '%".$search."%' OR  AirframeComponentTimes.hours LIKE '%".$search."%' OR  AirframeComponentTimes.cycles LIKE '%".$search."%'
            )";
        }

        $columns = array(
            0 => 'AirframeComponentTimes.id',
            1 => 'Planes.plane_code',
            2 => 'AirframeComponents.log_book',
            3 => 'AirframeComponentTimes.log_date', 
            4 => 'AirframeComponentTimes.hours',
            5 => 'AirframeComponentTimes.cycles'
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
            $nestedData[] = !empty($row["log_date"]) ? date('d M Y', strtotime($row["log_date"])) : '';
            $nestedData[] = $row["hours"];
            $nestedData[] = $row["cycles"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $this->Auth->user('id') == 1){
                $view = '<a href="airframe_component_times/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $this->Auth->user('id') == 1){
                $edit = ' <a href="airframe_component_times/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $this->Auth->user('id') == 1){
                $delete = ' <a data-airframe_component_time_id="'.$row['id'].'" data-url="airframe_component_times/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete '.$row["log_book"].' Component Time?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $airCompTimes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($airCompTimes);die;
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
        $airCompTimes = $this->AirframeComponentTimes->get($id, [
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
        $this->set('airCompTimes', $airCompTimes);
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
            if(array_key_exists('Airframe Component Times', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Times'];
            }
        }
        $airCompTimes = $this->AirframeComponentTimes->newEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            
            $airCompTimes = $this->AirframeComponentTimes->patchEntity($airCompTimes, $postData);

            if ($this->AirframeComponentTimes->save($airCompTimes)) {
                $this->Flash->success(__('The Airframe Component Time has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Airframe Component Time could not be saved. Please, try again.'));
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $airComps = $this->AirframeComponent->getAirComps();
        $this->set(compact('airCompTimes', 'actionItems', 'planes', 'airComps'));
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
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Times', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Times'];
            }
        }
        $airCompTimes = $this->AirframeComponentTimes->get($id, [
            'contain' => ['Planes']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $airCompTimes = $this->AirframeComponentTimes->patchEntity($airCompTimes, $postData);
            if ($this->AirframeComponentTimes->save($airCompTimes)) {
                $this->Flash->success(__('The airframe component time has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The airframe component time could not be saved. Please, try again.'));
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $airComps = $this->AirframeComponent->getRAirComps($airCompTimes->plane_id);
        $this->set(compact('airCompTimes', 'actionItems', 'planes', 'airComps'));
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
        $airCompCats = $this->AirframeComponentTimes->get($id);
        try {
            if ($this->AirframeComponentTimes->delete($airCompCats)) {
                $this->Flash->success(__('The airframe component time has been deleted.'));
            } else {
                $this->Flash->error(__('The airframe component time could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

}
