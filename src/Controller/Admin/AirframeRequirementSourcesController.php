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
 * AirframeRequirementSources Controller
 *
 * @property \App\Model\Table\AirframeRequirementSourcesTable $AirframeRequirementSources
 *
 * @method \App\Model\Entity\AirframeRequirementSources[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeRequirementSourcesController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    protected \App\Model\Table\AirframeRequirementSourcesTable $AirframeRequirementSources;

    public function initialize():void {
        parent::initialize();
        $this->AirframeRequirementSources = $this->fetchTable('AirframeRequirementSources');
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
            if(array_key_exists('Requirement Source', $actionStatus))
            {
                $actionItems = $actionStatus['Requirement Source'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( AirframeRequirementSources.`id`) AS count  FROM `airframe_requirement_sources` AirframeRequirementSources WHERE 1=1 ";

        $query['detail'] = "SELECT AirframeRequirementSources.`id`, AirframeRequirementSources.`title`, AirframeRequirementSources.`status` FROM `airframe_requirement_sources` AirframeRequirementSources WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageAirframeRequirementSourcesSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Requirement Source', $actionStatus))
            {
                $actionItems = $actionStatus['Requirement Source'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND AirframeRequirementSources.title LIKE '%".$search."%'";
        }

        $columns = array(
            0 => 'AirframeRequirementSources.id',
            1 => 'AirframeRequirementSources.title', 
            2 => 'AirframeRequirementSources.status'
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
            $nestedData[] = $row["status"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                $view = '<a href="airframe_requirement_sources/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="airframe_requirement_sources/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-item-type-id="'.$row['id'].'" data-url="airframe_requirement_sources/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this Requirement Source?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $requirementSourceRes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($requirementSourceRes);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeRequirementSources id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $requirementSourceRes = $this->AirframeRequirementSources->get($id);
        $this->set('requirementSourceRes', $requirementSourceRes);
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
            if(array_key_exists('Requirement Source', $actionStatus))
            {
                $actionItems = $actionStatus['Requirement Source'];
            }
        }
        $requirementSourceRes = $this->AirframeRequirementSources->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $requirementSourceRes = $this->AirframeRequirementSources->patchEntity($requirementSourceRes, $postData);
            
            if ($this->AirframeRequirementSources->save($requirementSourceRes)) {
                //save to aircraft history table
                //$this->AircraftHistory->saveAdsbStatusHistory($requirementSourceRes);

                $this->Flash->success(__('The Requirement Source has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Requirement Source could not be saved. Please, try again.'));
        }
        $this->set(compact('requirementSourceRes', 'actionItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AirframeRequirementType id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Requirement Source', $actionStatus))
            {
                $actionItems = $actionStatus['Requirement Source'];
            }
        }
        $requirementSourceRes = $this->AirframeRequirementSources->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $requirementSourceRes = $this->AirframeRequirementSources->patchEntity($requirementSourceRes, $postData);
            if ($this->AirframeRequirementSources->save($requirementSourceRes)) {
                $this->Flash->success(__('The Requirement Source has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Requirement Source could not be saved. Please, try again.'));
        }
        $this->set(compact('requirementSourceRes', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeRequirementSources id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $requirementSourceRes = $this->AirframeRequirementSources->get($id);
        try {
            if ($this->AirframeRequirementSources->delete($requirementSourceRes)) {
                $this->Flash->success(__('The Requirement Source has been deleted.'));
            } else {
                $this->Flash->error(__('The Requirement Source could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

   
}
