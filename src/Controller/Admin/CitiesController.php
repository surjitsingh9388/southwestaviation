<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
/**
 * CitiesController Controller
 *
 * @property \App\Model\Table\CitiesTable $Cities
 *
 * @method \App\Model\Entity\Cities[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CitiesController extends AppController
{
    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Cities', $actionStatus))
            {
                $actionItems = $actionStatus['Cities'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count( Cities.id) AS count  FROM `cities` Cities INNER JOIN `states` States ON States.id = Cities.state_id INNER JOIN `countries` Country ON Country.id = States.country_id WHERE 1=1 ";

        $query['detail'] = "SELECT Cities.id, Cities.`name` as city_name, States.`name` as state_name, Country.`name` as country_name FROM `cities` Cities INNER JOIN `states` States ON States.id = Cities.state_id INNER JOIN `countries` Country ON Country.id = States.country_id WHERE  1=1 ";
        return $query;
    }

    public function ajaxManageCitiesSearch(){
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Cities', $actionStatus))
            {
                $actionItems = $actionStatus['Cities'];
            }
        }
        $this->autoRender = false;
        $this->layout = 'ajax';
        $requestData= $this->request->data;
        $query = $this->search();
        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Cities.name LIKE '".$search."%' OR Country.name LIKE '".$search."%' OR States.name LIKE '".$search."%'
            )";
        }else{
            $search = 'United States';
            $cond.=" AND ( Cities.name LIKE '".$search."%' OR Country.name LIKE '".$search."%' OR States.name LIKE '".$search."%')";
        }


        $columns = array(
            0 => 'Cities.id',
            1 => 'Cities.name',
            2 => 'States.name',
            3 => 'Country.name'
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
        $sn = $requestData['start']+1;
        $length = PAGINATION_LIMIT;

        $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
        $results = $conn->execute( $SQL )->fetchAll('assoc');
        $i = 0;
        $j=1;
        
        $data = array();
        $view = $edit = $delete = '';
        foreach ( $results as $row){
            $nestedData= [];
            $nestedData[] = $sn++;
            $nestedData[] = $row["city_name"];
            $nestedData[] = $row["state_name"];            
            $nestedData[] = $row["country_name"];
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $this->Auth->user('id') == 1){
                $view = '<a href="cities/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $this->Auth->user('id') == 1){
                $edit = ' <a href="cities/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $this->Auth->user('id') == 1){
                $delete = ' <a data-city_id="'.$row['id'].'" data-url="cities/delete"  href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this City: '.$row["city_name"].'"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }
        $roles = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($roles);die;
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
        $cities = $this->Cities->get($id, [
            'contain' => ['States', 'States.Countries']
        ]);
        $this->set('cities', $cities);
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
            if(array_key_exists('Cities', $actionStatus))
            {
                $actionItems = $actionStatus['Cities'];
            }
        }
        $cities = $this->Cities->newEntity();
        $country_id = null;
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $country_id = $postData['country_id'];
            
            //Check if tier_level is already selected for membership_type
            if ($this->Cities->exists(['name' => $postData['name'], 'state_id' => $postData['state_id']])) {
                $this->Flash->error(__('City already exists for selected state.'));
            } else {
                $postData['updated_by'] = $this->Auth->User('id');
                $cities = $this->Cities->patchEntity($cities, $postData);
                //echo "<pre>";print_r($cities);die;
                if ($this->Cities->save($cities)) {
                    $this->Flash->success(__('The City has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The City could not be saved. Please, try again.'));
            }    
        }
        if (empty($country_id)) {
            $country_id = env('DEFAULT_COUNTRY_ID');
        }
        $states = $this->getStatesList($country_id);
        $countries = $this->getCountriesList();
        $this->set(compact('cities', 'states', 'countries', 'actionItems'));
        $this->set('country_id', $country_id);
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
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Cities', $actionStatus))
            {
                $actionItems = $actionStatus['Cities'];
            }
        }
        try {
            $cities = $this->Cities->get($id,[
                'contain' => 'States.Countries'
            ]);
        } catch (\Exception $e) {
            $this->Flash->error(__('No records found for this City id.'));
            return $this->redirect(['action' => 'index']);
        }    
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            //Check if tier_level is already selected for membership_type
            if ($this->Cities->exists(['name' => $postData['name'], 'state_id' => $postData['state_id'], 'id !=' => $id])) {
                $this->Flash->error(__('City already exists for selected state.'));
                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $cities = $this->Cities->patchEntity($cities, $postData);
                $cities->updated_by = $this->Auth->User('id');
                if ($this->Cities->save($cities)) {
                    $this->Flash->success(__('The City has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The City could not be saved. Please, try again.'));
            }    
        }
        $states = $this->getStatesList($cities->state->country->id);
        $countries = $this->getCountriesList();
        $this->set(compact('cities', 'states', 'countries', 'actionItems'));
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
        $city = $this->Cities->get($id);
        try {
            if ($this->Cities->delete($city)) {
                $this->Flash->success(__('The city has been deleted.'));
            } else {
                $this->Flash->error(__('The city could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

    /**
     * getStatesList method
     * This function is used to get list of all states.
     *
     * @return array.
     */
    public function getStatesList($country_id = null) {
        $states = $this->Cities->States->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'name'
        ));
        if (!empty($country_id)) {
            $states = $states->where(['country_id'=>$country_id]);
        }
        $states = $states->toArray();
        return $states;
    }

    /**
     * getCountriesList method
     * This function is used to get list of all countries.
     *
     * @return array.
     */
    public function getCountriesList() {
        $countries = TableRegistry::get('Countries');
        $country = $countries->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'name'
        ))->toArray();
        return $country;
    }


    /**
     * getAjaxStatesList method
     * This function is used to get list of all states.
     *
     * @return array.
     */
    public function getAjaxStatesList() {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $country_id = $this->request->getData('country_id');
            $states = $this->Cities->States->find('list', array (
                'keyField' => 'id', 
                'valueField' => 'name'
            ))->where(['country_id'=>$country_id])->toArray();
            
            echo json_encode($states);
            die;
        }
    }
    
    /**
     * IsFeeTypeExist method
     * This function is used to check is fee type already exist.
     * 
     * @return boolean true/false
     */
    public function isTierExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $tierLevel = $this->request->getData('tierLevel');
            $membershipTypeId = $this->request->getData('membershipTypeId');
            // qury to check tier_level
            $exists = $this->Cities->exists(['tier_level' => $tierLevel, 'membership_type_id' => $membershipTypeId]);
            if ($exists) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }
}
