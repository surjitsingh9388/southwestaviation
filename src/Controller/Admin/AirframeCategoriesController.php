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
 * AirframeCategories Controller
 *
 * @property \App\Model\Table\AirframeCategoriesTable $AirframeCategories
 *
 * @method \App\Model\Entity\AirframeCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeCategoriesController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize():void {
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
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Categories', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Categories'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( AirframeCategories.`id`) AS count  FROM `airframe_categories` AirframeCategories LEFT JOIN `planes` Planes ON AirframeCategories.`plane_id` = Planes.`id` WHERE 1=1 ";

        $query['detail'] = "SELECT AirframeCategories.`id`, Planes.`plane_code`, AirframeCategories.`category_name`, AirframeCategories.`serial_number` FROM `airframe_categories` AirframeCategories LEFT JOIN `planes` Planes ON AirframeCategories.`plane_id` = Planes.`id` WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageCategoriesSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Categories', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Categories'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Planes.plane_code LIKE '%".$search."%' OR  AirframeCategories.category_name LIKE '%".$search."%' OR  AirframeCategories.serial_number LIKE '%".$search."%'
            )";
        }

        $columns = array(
            0 => 'AirframeCategories.id',
            1 => 'Planes.plane_code',
            2 => 'AirframeCategories.category_name', 
            3 => 'AirframeCategories.serial_number'
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
            $nestedData[] = $row["category_name"];
            $nestedData[] = $row["serial_number"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                $view = '<a href="airframe_categories/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="airframe_categories/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-airframe_category_id="'.$row['id'].'" data-url="airframe_categories/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this category?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $airCats = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($airCats);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeCategory id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $airCats = $this->AirframeCategories->get($id, [
                'contain' => ['Planes'=>['fields'=>['Planes.id', 'Planes.plane_code']]]
            ]);
        $this->set('airCats', $airCats);
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
            if(array_key_exists('Airframe Categories', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Categories'];
            }
        }
        $airCats = $this->AirframeCategories->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $airCats = $this->AirframeCategories->patchEntity($airCats, $postData);
            if ($this->AirframeCategories->save($airCats)) {
                $this->Flash->success(__('The Airframe Category has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Airframe Category could not be saved. Please, try again.'));
        }

        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $this->set(compact('airCats', 'actionItems', 'planes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AirframeCategory id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Categories', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Categories'];
            }
        }
        $airCats = $this->AirframeCategories->get($id, [
            'contain' => ['Planes']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $airCats = $this->AirframeCategories->patchEntity($airCats, $postData);
            if ($this->AirframeCategories->save($airCats)) {
                $this->Flash->success(__('The Airframe Category has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Airframe Category could not be saved. Please, try again.'));
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $this->set(compact('airCats', 'actionItems', 'planes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeCategory id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $airCats = $this->AirframeCategories->get($id);
        try {
            if ($this->AirframeCategories->delete($airCats)) {
                $this->Flash->success(__('The Airframe Category has been deleted.'));
            } else {
                $this->Flash->error(__('The Airframe Category could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

    //Get Airframe Categories
    public function getCategories() {
        $this->viewBuilder()->setLayout('ajax');
        $planeId = $this->request->getData()['planeId'];
        
        $airCats = array();
        $airCats = $this->AirframeCategories->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'category_name'
        ))->where(['AirframeCategories.plane_id' => $planeId])->toArray();

        $this->set('airCats', $airCats);
    }

   
}
