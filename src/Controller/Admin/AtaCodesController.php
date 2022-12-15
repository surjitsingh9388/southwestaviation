<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;

/**
 * AtaCodes Controller
 *
 * @property \App\Model\Table\AtaCodesTable $AtaCodes
 *
 * @method \App\Model\Entity\AtaCode[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AtaCodesController extends AppController
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
            if(array_key_exists('ATA Codes', $actionStatus))
            {
                $actionItems = $actionStatus['ATA Codes'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( AtaCodes.`id`) AS count  FROM `ata_codes` AtaCodes WHERE 1=1 ";

        $query['detail'] = "SELECT AtaCodes.`id`, AtaCodes.`ata_code`, AtaCodes.`status` FROM `ata_codes` AtaCodes WHERE 1=1 ";
        
        return $query;
    }

    public function ajaxManageAtaCodesSearch() {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('ATA Codes', $actionStatus))
            {
                $actionItems = $actionStatus['ATA Codes'];
            }
        }
        $this->autoRender = false;
        $this->layout = 'ajax';
        $requestData= $this->request->data;

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND AtaCodes.ata_code LIKE '%".$search."%'";
        }

        $columns = array(
            0 => 'AtaCodes.id',
            1 => 'AtaCodes.ata_code', 
            2 => 'AtaCodes.status'
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
            $nestedData[] = $row["ata_code"];
            $nestedData[] = $row["status"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $this->Auth->user('id') == 1){
                $view = '<a href="ata_codes/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $this->Auth->user('id') == 1){
                $edit = ' <a href="ata_codes/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $this->Auth->user('id') == 1){
                $delete = ' <a data-ata_code_id="'.$row['id'].'" data-url="ata_codes/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this ATA Code?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $ataCodes = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($ataCodes);die;
    }

    /**
     * View method
     *
     * @param string|null $id AtaCode id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ataCodes = $this->AtaCodes->get($id);
        $this->set('ataCodes', $ataCodes);
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
            if(array_key_exists('ATA Codes', $actionStatus))
            {
                $actionItems = $actionStatus['ATA Codes'];
            }
        }
        $ataCodes = $this->AtaCodes->newEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            //pr($postData);die;
            $ataCodes = $this->AtaCodes->patchEntity($ataCodes, $postData);
            //pr($ataCodes);die;
            if ($this->AtaCodes->save($ataCodes)) {
                $this->Flash->success(__('The ATA Code has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ATA Code could not be saved. Please, try again.'));
        }
        $this->set(compact('ataCodes', 'actionItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AtaCode id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('AtaCodes', $actionStatus))
            {
                $actionItems = $actionStatus['AtaCodes'];
            }
        }
        $ataCodes = $this->AtaCodes->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            
            $ataCodes = $this->AtaCodes->patchEntity($ataCodes, $postData);
            if ($this->AtaCodes->save($ataCodes)) {
                $this->Flash->success(__('The ATA Code has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ATA Code could not be saved. Please, try again.'));
        }
        $this->set(compact('ataCodes', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AtaCode id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $ataCodes = $this->AtaCodes->get($id);
        try {
            if ($this->AtaCodes->delete($ataCodes)) {
                $this->Flash->success(__('The ATA Code has been deleted.'));
            } else {
                $this->Flash->error(__('The ATA Code could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

   
}
