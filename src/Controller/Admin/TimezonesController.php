<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Routing\Router;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Timezones Controller
 *
 * @property \App\Model\Table\TimezonesTable $Timezones
 *
 * @method \App\Model\Entity\Timezones[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TimezonesController extends AppController
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
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Timezones', $actionStatus))
            {
                $actionItems = $actionStatus['Timezones'];
            }
        }
        $query = $this->Timezones->find()->order('serial_no ASC');
        $conn = ConnectionManager::get('default');
        $reports = $conn->execute( $query )->fetchAll('assoc');
        $this->set(compact('reports', 'actionItems'));
    }

    /**
     * View method
     *
     * @param string|null $id Timezone id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $timezone = $this->Timezones->get($id);
        $this->set('timezone', $timezone);
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
            if(array_key_exists('Timezones', $actionStatus))
            {
                $actionItems = $actionStatus['Timezones'];
            }
        }
        $timezone = $this->Timezones->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            //Converting enter key value in 1 character (Method defined in Admin AppController)
            $postData['description'] = $this->checkMaxlength($postData['description']);
            $timezone = $this->Timezones->patchEntity($timezone, $postData);
            $timezone->updated_by = $authUserData['id'];
            if ($this->Timezones->save($timezone)) {
                $this->Flash->success(__('The timezone has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The timezone could not be saved. Please, try again.'));
        }
        $serialNo = $this->Timezones->find('all',['fields' => array('serial_no' => 'MAX(Timezones.serial_no)')])->toArray();
        $timezoneListArray = timezone_identifiers_list();
        foreach ($timezoneListArray as $key => $value) {
            $timezoneList[$value] = $value;
        }
        //pr(count($timezoneList));exit; 
        $this->set(compact('timezone', 'serialNo', 'timezoneList', 'actionItems'));
    }
    function tz_list() {
      $zones_array = array();
      $timestamp = time();
      foreach(timezone_identifiers_list() as $key => $zone) {
        date_default_timezone_set($zone);
        $zones_array[$key]['zone'] = $zone;
        $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
      }
      return $zones_array;
    }

    /**
     * Edit method
     *
     * @param string|null $id Timezone id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Timezones', $actionStatus))
            {
                $actionItems = $actionStatus['Timezones'];
            }
        }
        $timezone = $this->Timezones->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            //Converting enter key value in 1 character (Method defined in Admin AppController)
            $postData['description'] = $this->checkMaxlength($postData['description']);
            $timezone = $this->Timezones->patchEntity($timezone, $postData);
            $timezone->updated_by = $authUserData['id'];
            
            if ($this->Timezones->save($timezone)) {
                $this->Flash->success(__('The timezone has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The timezone could not be saved. Please, try again.'));
        }
        $timezoneListArray = timezone_identifiers_list();
        foreach ($timezoneListArray as $key => $value) {
            $timezoneList[$value] = $value;
        }
        //pr($timezone['timezone']);exit;
        $this->set(compact('timezone', 'timezoneList', 'actionItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Timezone id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $timezone = $this->Timezones->get($id);
        try {
            if ($this->Timezones->delete($timezone)) {
                $this->Flash->success(__('The timezone has been deleted.'));
            } else {
                $this->Flash->error(__('The timezone could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * isTimezoneExist method
     * This function is used to check is timezone already exist.
     * 
     * @return boolean true/false
     */
    public function isTimezoneExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $timezone = $this->request->getData('timezone');
            $exists = $this->Timezones->exists(['timezone' => $timezone]);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    public function isSerialNoExist(){
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $serialNo = $this->request->getData('serial_no');
            $exists = $this->Timezones->exists(['serial_no' => $serialNo]);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }
}
