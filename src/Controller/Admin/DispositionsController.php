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
 * Dispositions Controller
 *
 * @property \App\Model\Table\DispositionsTable $Dispositions
 *
 * @method \App\Model\Entity\Disposition[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DispositionsController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize():void {
        parent::initialize();

        $this->loadComponent("AircraftHistory");
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
            if(array_key_exists('Dispositions', $actionStatus))
            {
                $actionItems = $actionStatus['Dispositions'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( Dispositions.`id`) AS count  FROM `dispositions` Dispositions WHERE 1=1 ";

        $query['detail'] = "SELECT Dispositions.`id`, Dispositions.`title`, Dispositions.`status` FROM `dispositions` Dispositions WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageDispositionsSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Dispositions', $actionStatus))
            {
                $actionItems = $actionStatus['Dispositions'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND Dispositions.title LIKE '%".$search."%'";
        }

        $columns = array(
            0 => 'Dispositions.id',
            1 => 'Dispositions.title', 
            2 => 'Dispositions.status'
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
                $view = '<a href="dispositions/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="dispositions/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-disposition_id="'.$row['id'].'" data-url="dispositions/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this Disposition?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $dispRes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($dispRes);die;
    }

    /**
     * View method
     *
     * @param string|null $id Disposition id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dispRes = $this->Dispositions->get($id);
        $this->set('dispRes', $dispRes);
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
            if(array_key_exists('Dispositions', $actionStatus))
            {
                $actionItems = $actionStatus['Dispositions'];
            }
        }
        $dispRes = $this->Dispositions->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $dispRes = $this->Dispositions->patchEntity($dispRes, $postData);

            if ($this->Dispositions->save($dispRes)) {
                //save to aircraft history table
                $this->AircraftHistory->saveDispositionHistory($dispRes);

                $this->Flash->success(__('The Disposition has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Disposition could not be saved. Please, try again.'));
        }
        $this->set(compact('dispRes', 'actionItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Disposition id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Dispositions', $actionStatus))
            {
                $actionItems = $actionStatus['Dispositions'];
            }
        }
        $dispRes = $this->Dispositions->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $dispRes = $this->Dispositions->patchEntity($dispRes, $postData);
            if ($this->Dispositions->save($dispRes)) {
                $this->Flash->success(__('The Disposition has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Disposition could not be saved. Please, try again.'));
        }
        $this->set(compact('dispRes', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Disposition id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $dispRes = $this->Dispositions->get($id);
        try {
            if ($this->Dispositions->delete($dispRes)) {
                $this->Flash->success(__('The Disposition has been deleted.'));
            } else {
                $this->Flash->error(__('The Disposition could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

   
}
