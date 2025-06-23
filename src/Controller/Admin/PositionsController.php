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
 * Position Controller
 *
 * @property \App\Model\Table\PositionTable $Position
 *
 * @method \App\Model\Entity\Position[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PositionsController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    protected \App\Model\Table\PositionsTable $Positions;

    public function initialize():void {
        parent::initialize();
        $this->Positions = $this->fetchTable('Positions');
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
            if(array_key_exists('Positions', $actionStatus))
            {
                $actionItems = $actionStatus['Positions'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( Position.`id`) AS count  FROM `positions` Position WHERE 1=1 ";

        $query['detail'] = "SELECT Position.`id`, Position.`title`, Position.`status` FROM `positions` Position WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManagePositionSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Positions', $actionStatus))
            {
                $actionItems = $actionStatus['Positions'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND Position.title LIKE '%".$search."%'";
        }

        $columns = array(
            0 => 'Position.id',
            1 => 'Position.title', 
            2 => 'Position.status'
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
            $nestedData[] = !empty($row["status"]) ? 'Active' : 'Inactive';
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                $view = '<a href="positions/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="positions/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-position_id="'.$row['id'].'" data-url="positions/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this Position?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $positionRes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($positionRes);die;
    }

    /**
     * View method
     *
     * @param string|null $id Position id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $positionRes = $this->Positions->get($id);
        $this->set('positionRes', $positionRes);
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
            if(array_key_exists('Positions', $actionStatus))
            {
                $actionItems = $actionStatus['Positions'];
            }
        }

        $positionRes = $this->Positions->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $postData['added_by'] = $authUserData['id'];
            $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
            $positionRes = $this->Positions->patchEntity($positionRes, $postData);
            
            if ($this->Positions->save($positionRes)) {
                //save to aircraft history table
                $this->AircraftHistory->savePositionHistory($positionRes);

                $this->Flash->success(__('The Position has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Position could not be saved. Please, try again.'));
        }
        $this->set(compact('positionRes', 'actionItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Position id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Positions', $actionStatus))
            {
                $actionItems = $actionStatus['Positions'];
            }
        }
        $positionRes = $this->Positions->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $postData['updated_by'] = $authUserData['id'];
            $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
            
            $positionRes = $this->Positions->patchEntity($positionRes, $postData);
            if ($this->Positions->save($positionRes)) {
                $this->Flash->success(__('The Position has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Position could not be saved. Please, try again.'));
        }
        $this->set(compact('positionRes', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Position id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $postData = $this->request->getData();
        if(!empty($postData['id'])){
            $id = $postData['id'];
            $this->request->allowMethod(['post', 'delete']);
            $positionRes = $this->Positions->get($id);
            try {
                if ($this->Positions->delete($positionRes)) {
                    $this->Flash->success(__('The Position has been deleted.'));
                } else {
                    $this->Flash->error(__('The Position could not be deleted. Please, try again.'));
                }
            } catch(\PDOException $e) {
                $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
            } catch (\Exception $e) {
                $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
            }    
        }else{
            $this->Flash->error(__('The Position could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
