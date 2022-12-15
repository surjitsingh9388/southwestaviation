<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;

/**
 * AirframeComponentLastCw Controller
 *
 * @property \App\Model\Table\AirframeComponentLastCwTable $AirframeComponentLastCw
 *
 * @method \App\Model\Entity\AirframeComponentLastCw[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeComponentLastCwController extends AppController
{
    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('AirframeComponent');
        $this->loadComponent('AirframeComponentPart');
        $this->loadComponent('AirframeCategory');
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
            if(array_key_exists('Airframe Component Last CW', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Last CW'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( AirframeComponentLastCw.`id`) AS count  FROM `airframe_component_last_cw` AirframeComponentLastCw LEFT JOIN `planes` Planes ON AirframeComponentLastCw.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON AirframeComponentLastCw.`airframe_component_id` = AirframeComponents.`id` WHERE 1=1 ";

        $query['detail'] = "SELECT AirframeComponentLastCw.`id`, Planes.`plane_name`, AirframeComponents.`log_book`,  AirframeComponentLastCw.`last_cw_date`, AirframeComponentLastCw.`hrs`, AirframeComponentLastCw.`afl` FROM `airframe_component_last_cw` AirframeComponentLastCw LEFT JOIN `planes` Planes ON AirframeComponentLastCw.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON AirframeComponentLastCw.`airframe_component_id` = AirframeComponents.`id` WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageCompLastCWSearch() {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Last CW', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Last CW'];
            }
        }
        $this->autoRender = false;
        $this->layout = 'ajax';
        $requestData= $this->request->data;

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Planes.plane_name LIKE '%".$search."%' OR AirframeComponents.log_book LIKE '%".$search."%' OR  AirframeComponentLastCw.last_cw_date LIKE '%".$search."%' OR  AirframeComponentLastCw.hrs LIKE '%".$search."%' OR  AirframeComponentLastCw.afl LIKE '%".$search."%'
            )";
        }

        $columns = array(
            0 => 'AirframeComponentLastCw.id',
            1 => 'Planes.plane_name',
            2 => 'AirframeComponents.log_book',
            3 => 'AirframeComponentLastCw.hrs',
            4 => 'AirframeComponentLastCw.afl',
            5 => 'AirframeComponentLastCw.last_cw_date' 
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
            $nestedData[] = $row["plane_name"];
            $nestedData[] = $row["log_book"];
            $nestedData[] = $row["hrs"];
            $nestedData[] = $row["afl"];
            $nestedData[] = !empty($row["last_cw_date"]) ? date('d M Y', strtotime($row["last_cw_date"])) : '';
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $this->Auth->user('id') == 1){
                $view = '<a href="airframe_component_last_cw/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $this->Auth->user('id') == 1){
                $edit = ' <a href="airframe_component_last_cw/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $this->Auth->user('id') == 1){
                $delete = ' <a data-airframe_component_last_cw_id="'.$row['id'].'" data-url="airframe_component_last_cw/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this record?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $airCompLastCW = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($airCompLastCW);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeComponentLastCw id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $airCompLastCW = $this->AirframeComponentLastCw->get($id, [
                'contain' => ['Planes'=>['fields'=>['Planes.id', 'Planes.plane_name']], 'AirframeComponents'=>['fields'=>['AirframeComponents.id', 'AirframeComponents.log_book']], 'AirframeComponentCategories'=>['fields'=>['AirframeComponentCategories.id', 'AirframeComponentCategories.category_name']], 'AirframeComponentParts'=>['fields'=>['AirframeComponentParts.id', 'AirframeComponentParts.ata_code']]]
            ]);
        $this->set('airCompLastCW', $airCompLastCW);
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
            if(array_key_exists('Airframe Component Last CW', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Last CW'];
            }
        }
        $airCompLastCW = $this->AirframeComponentLastCw->newEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $airCompLastCW = $this->AirframeComponentLastCw->patchEntity($airCompLastCW, $postData);
            if ($this->AirframeComponentLastCw->save($airCompLastCW)) {
                $this->Flash->success(__('The Airframe Component Last CW has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Airframe Component Last CW could not be saved. Please, try again.'));
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $airComps = $this->AirframeComponent->getAirComps();
        $airCompCats = $this->AirframeCategory->getAirCompCats();
        $manufacturers = $this->Manufacturer->getManufacturers();
        $airCompParts = $this->AirframeComponentPart->getAirCompParts();
        $this->set(compact('airCompLastCW', 'actionItems', 'planes', 'airComps', 'airCompCats', 'manufacturers', 'airCompParts'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AirframeComponentLastCw id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Last CW', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Last CW'];
            }
        }
        $airCompLastCW = $this->AirframeComponentLastCw->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $airCompLastCW = $this->AirframeComponentLastCw->patchEntity($airCompLastCW, $postData);
            if ($this->AirframeComponentLastCw->save($airCompLastCW)) {
                $this->Flash->success(__('The airframe component last cw has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The airframe component last cw could not be saved. Please, try again.'));
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();
        
        $airComps = $this->AirframeComponent->getRAirComps($airCompLastCW->plane_id);
        $airCompCats = $this->AirframeCategory->getAirCompCats();
        $manufacturers = $this->Manufacturer->getManufacturers();
        $airCompParts = $this->AirframeComponentPart->getAirCompParts();
        $this->set(compact('airCompLastCW', 'actionItems', 'planes', 'airComps', 'airCompCats', 'manufacturers', 'airCompParts'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeComponentLastCw id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $airCompLastCW = $this->AirframeComponentLastCw->get($id);
        try {
            if ($this->AirframeComponentLastCw->delete($airCompLastCW)) {
                $this->Flash->success(__('The airframe component last cw has been deleted.'));
            } else {
                $this->Flash->error(__('The airframe component last cw could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

   
}
