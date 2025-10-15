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
 * AirframeItemTypes Controller
 *
 * @property \App\Model\Table\AirframeItemTypesTable $AirframeItemTypes
 *
 * @method \App\Model\Entity\AirframeItemTypes[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeItemTypesController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    protected \App\Model\Table\AirframeItemTypesTable $AirframeItemTypes;
    protected \App\Model\Table\AirframeComponentPartsTable $AirframeComponentParts;

    public function initialize():void {
        parent::initialize();
        $this->AirframeItemTypes = $this->fetchTable('AirframeItemTypes');
        $this->AirframeComponentParts = $this->fetchTable('AirframeComponentParts');
        
        //$this->loadComponent('AircraftHistory');
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
            if(array_key_exists('Item Type', $actionStatus))
            {
                $actionItems = $actionStatus['Item Type'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( AirframeItemTypes.`id`) AS count  FROM `airframe_item_types` AirframeItemTypes WHERE 1=1 ";

        $query['detail'] = "SELECT AirframeItemTypes.`id`, AirframeItemTypes.`title`, AirframeItemTypes.`sort_title`, AirframeItemTypes.`status` FROM `airframe_item_types` AirframeItemTypes WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageAirframeItemTypesSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Item Type', $actionStatus))
            {
                $actionItems = $actionStatus['Item Type'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND AirframeItemTypes.title LIKE '%".$search."%'";
        }

        $columns = array(
            0 => 'AirframeItemTypes.id',
            1 => 'AirframeItemTypes.title', 
            2 => 'AirframeItemTypes.sort_title', 
            3 => 'AirframeItemTypes.status'
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
            $nestedData   = [];
            $nestedData[] = $j++;
            $nestedData[] = $row["title"];
            $nestedData[] = $row["sort_title"];
            $nestedData[] = $row["status"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                $view = '<a href="airframe_item_types/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="airframe_item_types/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-item-type-id="'.$row['id'].'" data-url="airframe_item_types/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this Item Type?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $itemTypeRes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($itemTypeRes);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeItemTypes id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $itemTypeRes = $this->AirframeItemTypes->get($id);
        $this->set('itemTypeRes', $itemTypeRes);
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
            if(array_key_exists('Item Type', $actionStatus))
            {
                $actionItems = $actionStatus['Item Type'];
            }
        }
        $itemTypeRes = $this->AirframeItemTypes->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $itemTypeRes = $this->AirframeItemTypes->patchEntity($itemTypeRes, $postData);
            
            if ($this->AirframeItemTypes->save($itemTypeRes)) {
                //save to aircraft history table
                //$this->AircraftHistory->saveAdsbStatusHistory($itemTypeRes);

                $this->Flash->success(__('The Item Type has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Item Type could not be saved. Please, try again.'));
        }
        $this->set(compact('itemTypeRes', 'actionItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AirframeItemType id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Item Type', $actionStatus))
            {
                $actionItems = $actionStatus['Item Type'];
            }
        }
        $itemTypeRes = $this->AirframeItemTypes->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $itemTypeRes = $this->AirframeItemTypes->patchEntity($itemTypeRes, $postData);
            if ($this->AirframeItemTypes->save($itemTypeRes)) {
                $this->AirframeComponentParts->updateAll(
                                                            ['item_type' => $itemTypeRes->sort_title],
                                                            ['item_type_id' => $itemTypeRes->id]
                                                        );

                $this->Flash->success(__('The Item Type has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Item Type could not be saved. Please, try again.'));
        }
        $this->set(compact('itemTypeRes', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeItemTypes id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $itemTypeRes = $this->AirframeItemTypes->get($id);
        try {
            if ($this->AirframeItemTypes->delete($itemTypeRes)) {
                $this->Flash->success(__('The Item Type has been deleted.'));
            } else {
                $this->Flash->error(__('The Item Type could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

   
}
