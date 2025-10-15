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
 * AirframeIssuingAuthorities Controller
 *
 * @property \App\Model\Table\AirframeIssuingAuthoritiesTable $AirframeIssuingAuthorities
 *
 * @method \App\Model\Entity\AirframeIssuingAuthorities[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeIssuingAuthoritiesController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    protected \App\Model\Table\AirframeIssuingAuthoritiesTable $AirframeIssuingAuthorities;
    protected \App\Model\Table\AirframeComponentPartsTable $AirframeComponentParts;

    public function initialize():void {
        parent::initialize();
        $this->AirframeIssuingAuthorities = $this->fetchTable('AirframeIssuingAuthorities');
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
            if(array_key_exists('Issuing Authority', $actionStatus))
            {
                $actionItems = $actionStatus['Issuing Authority'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( AirframeIssuingAuthorities.`id`) AS count  FROM `airframe_issuing_authorities` AirframeIssuingAuthorities WHERE 1=1 ";

        $query['detail'] = "SELECT AirframeIssuingAuthorities.`id`, AirframeIssuingAuthorities.`title`, AirframeIssuingAuthorities.`status` FROM `airframe_issuing_authorities` AirframeIssuingAuthorities WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageAirframeIssuingAuthoritiesSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Issuing Authority', $actionStatus))
            {
                $actionItems = $actionStatus['Issuing Authority'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND AirframeIssuingAuthorities.title LIKE '%".$search."%'";
        }

        $columns = array(
            0 => 'AirframeIssuingAuthorities.id',
            1 => 'AirframeIssuingAuthorities.title', 
            2 => 'AirframeIssuingAuthorities.status'
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
                $view = '<a href="airframe_issuing_authorities/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="airframe_issuing_authorities/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-item-type-id="'.$row['id'].'" data-url="airframe_issuing_authorities/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this Issuing Authority?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $issuingAuthoritiesRes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($issuingAuthoritiesRes);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeIssuingAuthorities id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $issuingAuthoritiesRes = $this->AirframeIssuingAuthorities->get($id);
        $this->set('issuingAuthoritiesRes', $issuingAuthoritiesRes);
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
            if(array_key_exists('Issuing Authority', $actionStatus))
            {
                $actionItems = $actionStatus['Issuing Authority'];
            }
        }
        $issuingAuthoritiesRes = $this->AirframeIssuingAuthorities->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $issuingAuthoritiesRes = $this->AirframeIssuingAuthorities->patchEntity($issuingAuthoritiesRes, $postData);
            
            if ($this->AirframeIssuingAuthorities->save($issuingAuthoritiesRes)) {
                //save to aircraft history table
                //$this->AircraftHistory->saveAdsbStatusHistory($issuingAuthoritiesRes);

                $this->Flash->success(__('The Issuing Authority has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Issuing Authority could not be saved. Please, try again.'));
        }
        $this->set(compact('issuingAuthoritiesRes', 'actionItems'));
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
            if(array_key_exists('Issuing Authority', $actionStatus))
            {
                $actionItems = $actionStatus['Issuing Authority'];
            }
        }
        $issuingAuthoritiesRes = $this->AirframeIssuingAuthorities->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $issuingAuthoritiesRes = $this->AirframeIssuingAuthorities->patchEntity($issuingAuthoritiesRes, $postData);
            if ($this->AirframeIssuingAuthorities->save($issuingAuthoritiesRes)) {
                $this->AirframeComponentParts->updateAll(
                                                            ['authority' => $issuingAuthoritiesRes->title],
                                                            ['issuing_authority_id' => $issuingAuthoritiesRes->id]
                                                        );

                $this->Flash->success(__('The Issuing Authority has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Issuing Authority could not be saved. Please, try again.'));
        }
        $this->set(compact('issuingAuthoritiesRes', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeIssuingAuthorities id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $issuingAuthoritiesRes = $this->AirframeIssuingAuthorities->get($id);
        try {
            if ($this->AirframeIssuingAuthorities->delete($issuingAuthoritiesRes)) {
                $this->Flash->success(__('The Issuing Authority has been deleted.'));
            } else {
                $this->Flash->error(__('The Issuing Authority could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

   
}
