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
 * AirframeRequirementTypes Controller
 *
 * @property \App\Model\Table\AirframeRequirementTypesTable $AirframeRequirementTypes
 *
 * @method \App\Model\Entity\AirframeRequirementTypes[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeRequirementTypesController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    protected \App\Model\Table\AirframeRequirementTypesTable $AirframeRequirementTypes;
    protected \App\Model\Table\AirframeComponentPartsTable $AirframeComponentParts;

    public function initialize():void {
        parent::initialize();
        $this->AirframeRequirementTypes = $this->fetchTable('AirframeRequirementTypes');
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
            if(array_key_exists('Requirement Type', $actionStatus))
            {
                $actionItems = $actionStatus['Requirement Type'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( AirframeRequirementTypes.`id`) AS count  FROM `airframe_requirement_types` AirframeRequirementTypes WHERE 1=1 ";

        $query['detail'] = "SELECT AirframeRequirementTypes.`id`, AirframeRequirementTypes.`title`, AirframeRequirementTypes.`status` FROM `airframe_requirement_types` AirframeRequirementTypes WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageAirframeRequirementTypesSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Requirement Type', $actionStatus))
            {
                $actionItems = $actionStatus['Requirement Type'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND AirframeRequirementTypes.title LIKE '%".$search."%'";
        }

        $columns = array(
            0 => 'AirframeRequirementTypes.id',
            1 => 'AirframeRequirementTypes.title', 
            2 => 'AirframeRequirementTypes.status'
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
                $view = '<a href="airframe_requirement_types/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="airframe_requirement_types/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-item-type-id="'.$row['id'].'" data-url="airframe_requirement_types/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this Requirement Type?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $requirementTypeRes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($requirementTypeRes);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeRequirementTypes id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $requirementTypeRes = $this->AirframeRequirementTypes->get($id);
        $this->set('requirementTypeRes', $requirementTypeRes);
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
            if(array_key_exists('Requirement Type', $actionStatus))
            {
                $actionItems = $actionStatus['Requirement Type'];
            }
        }
        $requirementTypeRes = $this->AirframeRequirementTypes->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $requirementTypeRes = $this->AirframeRequirementTypes->patchEntity($requirementTypeRes, $postData);
            
            if ($this->AirframeRequirementTypes->save($requirementTypeRes)) {
                //save to aircraft history table
                //$this->AircraftHistory->saveAdsbStatusHistory($requirementTypeRes);

                $this->Flash->success(__('The Requirement Type has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Requirement Type could not be saved. Please, try again.'));
        }
        $this->set(compact('requirementTypeRes', 'actionItems'));
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
            if(array_key_exists('Requirement Type', $actionStatus))
            {
                $actionItems = $actionStatus['Requirement Type'];
            }
        }
        $requirementTypeRes = $this->AirframeRequirementTypes->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $requirementTypeRes = $this->AirframeRequirementTypes->patchEntity($requirementTypeRes, $postData);
            if ($this->AirframeRequirementTypes->save($requirementTypeRes)) {
                 $this->AirframeComponentParts->updateAll(
                                                            ['requirement_type' => $requirementTypeRes->title],
                                                            ['requirement_type_id' => $requirementTypeRes->id]
                                                        );

                $this->Flash->success(__('The Requirement Type has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Requirement Type could not be saved. Please, try again.'));
        }
        $this->set(compact('requirementTypeRes', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeRequirementTypes id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $requirementTypeRes = $this->AirframeRequirementTypes->get($id);
        try {
            if ($this->AirframeRequirementTypes->delete($requirementTypeRes)) {
                $this->Flash->success(__('The Requirement Type has been deleted.'));
            } else {
                $this->Flash->error(__('The Requirement Type could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

   
}
