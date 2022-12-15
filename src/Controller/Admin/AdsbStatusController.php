<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;

/**
 * AdsbStatus Controller
 *
 * @property \App\Model\Table\AdsbStatusTable $AdsbStatus
 *
 * @method \App\Model\Entity\AdsbStatus[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdsbStatusController extends AppController
{
    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize() {
        parent::initialize();
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
            if(array_key_exists('AD/SB Status', $actionStatus))
            {
                $actionItems = $actionStatus['AD/SB Status'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( AdsbStatus.`id`) AS count  FROM `adsb_status` AdsbStatus WHERE 1=1 ";

        $query['detail'] = "SELECT AdsbStatus.`id`, AdsbStatus.`title`, AdsbStatus.`status` FROM `adsb_status` AdsbStatus WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageAdsbStatusSearch() {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('AD/SB Status', $actionStatus))
            {
                $actionItems = $actionStatus['AD/SB Status'];
            }
        }
        $this->autoRender = false;
        $this->layout = 'ajax';
        $requestData= $this->request->data;

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND AdsbStatus.title LIKE '%".$search."%'";
        }

        $columns = array(
            0 => 'AdsbStatus.id',
            1 => 'AdsbStatus.title', 
            2 => 'AdsbStatus.status'
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
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $this->Auth->user('id') == 1){
                $view = '<a href="adsb_status/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $this->Auth->user('id') == 1){
                $edit = ' <a href="adsb_status/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $this->Auth->user('id') == 1){
                $delete = ' <a data-adsb_status_id="'.$row['id'].'" data-url="adsb_status/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this AdsbStatus?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $adsbRes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($adsbRes);die;
    }

    /**
     * View method
     *
     * @param string|null $id AdsbStatus id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $adsbRes = $this->AdsbStatus->get($id);
        $this->set('adsbRes', $adsbRes);
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
            if(array_key_exists('AD/SB Status', $actionStatus))
            {
                $actionItems = $actionStatus['AD/SB Status'];
            }
        }
        $adsbRes = $this->AdsbStatus->newEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $adsbRes = $this->AdsbStatus->patchEntity($adsbRes, $postData);
            
            if ($this->AdsbStatus->save($adsbRes)) {
                $this->Flash->success(__('The AdsbStatus has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The AdsbStatus could not be saved. Please, try again.'));
        }
        $this->set(compact('adsbRes', 'actionItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AdsbStatus id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('AD/SB Status', $actionStatus))
            {
                $actionItems = $actionStatus['AD/SB Status'];
            }
        }
        $adsbRes = $this->AdsbStatus->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $adsbRes = $this->AdsbStatus->patchEntity($adsbRes, $postData);
            if ($this->AdsbStatus->save($adsbRes)) {
                $this->Flash->success(__('The AdsbStatus has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The AdsbStatus could not be saved. Please, try again.'));
        }
        $this->set(compact('adsbRes', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AdsbStatus id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $adsbRes = $this->AdsbStatus->get($id);
        try {
            if ($this->AdsbStatus->delete($adsbRes)) {
                $this->Flash->success(__('The AdsbStatus has been deleted.'));
            } else {
                $this->Flash->error(__('The AdsbStatus could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

   
}
