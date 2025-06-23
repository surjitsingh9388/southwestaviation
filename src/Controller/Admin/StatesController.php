<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * StatesController Controller
 *
 * @property \App\Model\Table\StatesTable $States
 *
 * @method \App\Model\Entity\States[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatesController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        /*$query = $this->States->find()->contain(array('States'));
        $States = $this->paginate($query);
        $this->set(compact('States'));*/
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('States', $actionStatus))
            {
                $actionItems = $actionStatus['States'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        //$this->viewBuilder()->layout('datatables');
        $query = array();
        $query['count']  = "SELECT count( States.id) AS count  FROM `states` States INNER JOIN `countries` Country ON Country.id = States.country_id WHERE 1=1 ";

        $query['detail'] = "SELECT States.id, States.`name` state_name, Country.`name` as country_name FROM `states` States INNER JOIN `countries` Country ON Country.id = States.country_id WHERE  1=1 ";
        //$this->request->session()->write('query', $query);
        return $query;
    }

    public function ajaxManageStateSearch(){
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('States', $actionStatus))
            {
                $actionItems = $actionStatus['States'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();
        //pr($requestData);exit;
        $query = $this->search();
        //pr($query);exit;
        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( States.name LIKE '".$search."%' OR Country.name LIKE '".$search."%')";
        }else{
            $search = 'United States';
            $cond.=" AND ( Country.name LIKE '".$search."%' OR States.name LIKE '".$search."%')";
        }


        $columns = array(
            0 => 'States.id',
            1 => 'States.name',
            2 => 'Country.name'
        );

        $count = $query['count'].$cond;
        $detail = $query['detail'].$cond;
        $totalCount = $query['count'];
        //pr($count);
        //pr($detail);
        $conn = ConnectionManager::get('default');
        $results = $conn->execute($count)->fetchAll('assoc');
        $totalData = isset($results[0]['count']) ? $results[0]['count'] : 0;
        //pr($totalData);
        $totalFiltered = $totalData;
        $results = $conn->execute( $totalCount )->fetchAll('assoc');
        $totalRecords = isset($results[0]['count']) ? $results[0]['count'] : 0;
        //pr($totalRecords);exit;
        $sidx = $columns[$requestData['order'][0]['column']];
        $sord = $requestData['order'][0]['dir'];
        $start = $requestData['start'];
        $sn = $requestData['start']+1;
        $length = PAGINATION_LIMIT;

        $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
        $results = $conn->execute( $SQL )->fetchAll('assoc');
        //pr($count);
        //pr($results);exit;
        $i = 0;
        $j=1;
        
        $data = array();
        //$data['url'] = Router::url('/admin/roles/', true);
        $view = $edit = $delete = '';
        foreach ( $results as $row){
            $nestedData= [];
            $nestedData[] = $sn++;
            $nestedData[] = $row["state_name"];            
            $nestedData[] = $row["country_name"];
            //if(!empty($actionItems)){
                if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                    $view = '<a href="states/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
                }
                if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                    $edit = ' <a href="states/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
                }
                if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                    $delete = ' <a data-state_id="'.$row['id'].'" data-url="states/delete"  href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this State: '.$row["state_name"].'"><i class="fa fa-trash-o"></i> Delete</a>';
                }
            //}
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }
        //pr($data);exit;
        $roles = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       // var_dump($roles);die;
        /*$this->set(compact('roles'));
        $this->set('_serialize', ['roles']);*/
        echo json_encode($roles);die;
        //$this->request->session()->write('roles', $roles);
        //pr($json_data);exit;
        //echo json_encode($roles);exit;
    }

    /**
     * View method
     *
     * @param string|null $id Tier Level id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $states = $this->States->get($id, [
            'contain' => ['Countries']
        ]);
        $this->set('states', $states);
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
            if(array_key_exists('States', $actionStatus))
            {
                $actionItems = $actionStatus['States'];
            }
        }
        $states = $this->States->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            
            //Check if tier_level is already selected for membership_type
            if ($this->States->exists(['name' => $postData['name'], 'country_id' => $postData['country_id']])) {
                $this->Flash->error(__('This State already exists for selected country.'));
            } else {
                $postData['updated_by'] = $authUserData['id'];
                $states = $this->States->patchEntity($states, $postData);
                //echo "<pre>";print_r($states);die;
                if ($this->States->save($states)) {
                    $this->Flash->success(__('The State has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The State could not be saved. Please, try again.'));
            }    
        }
        $countries = $this->getCountriesList();
        $this->set(compact('states', 'countries', 'actionItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Tier Level id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('States', $actionStatus))
            {
                $actionItems = $actionStatus['States'];
            }
        }
        try {
            $states = $this->States->get($id,[
                'contain' => 'Countries'
            ]);
        } catch (\Exception $e) {
            $this->Flash->error(__('No records found for this State id.'));
            return $this->redirect(['action' => 'index']);
        }    
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            //Check if tier_level is already selected for membership_type
            if ($this->States->exists(['name' => $postData['name'], 'country_id' => $postData['country_id'], 'id !=' => $id])) {
                $this->Flash->error(__('This State already exists for selected state.'));
                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $states = $this->States->patchEntity($states, $postData);
                //echo "<pre>";print_r($states);die;
                $states->updated_by = $authUserData['id'];
                if ($this->States->save($states)) {
                    $this->Flash->success(__('The State has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The State could not be saved. Please, try again.'));
            }    
        }
        $countries = $this->getCountriesList();
        $this->set(compact('states', 'countries', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Tier Level id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $state = $this->States->get($id);
        try {
            if ($this->States->delete($state)) {
                $this->Flash->success(__('The state has been deleted.'));
            } else {
                $this->Flash->error(__('The state could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

    /**
     * getCountriesList method
     * This function is used to get list of all countries.
     *
     * @return array.
     */
    public function getCountriesList($country_id = null) {
        $states = $this->States->Countries->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'name'
        ))->toArray();
        return $states;
    }
}
