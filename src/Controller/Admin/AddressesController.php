<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * Addresses Controller
 *
 * @property \App\Model\Table\AddressesTable $Addresses
 *
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AddressesController extends AppController
{
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Address');
    }
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['getStatesList','getCitiesList']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Companies']
        ];
        $addresses = $this->paginate($this->Addresses);

        $this->set(compact('addresses'));
    }

    /**
     * View method
     *
     * @param string|null $id Address id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $address = $this->Addresses->get($id, [
            'contain' => ['Users', 'Companies']
        ]);

        $this->set('address', $address);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $address = $this->Addresses->newEntity();
        if ($this->request->is('post')) {
            $address = $this->Addresses->patchEntity($address, $this->request->getData());
            if ($this->Addresses->save($address)) {
                $this->Flash->success(__('The address has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The address could not be saved. Please, try again.'));
        }
        $users = $this->Addresses->Users->find('list', ['limit' => 200]);
        $companies = $this->Addresses->Companies->find('list', ['limit' => 200]);
        $this->set(compact('address', 'users', 'companies'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Address id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $address = $this->Addresses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $address = $this->Addresses->patchEntity($address, $this->request->getData());
            if ($this->Addresses->save($address)) {
                $this->Flash->success(__('The address has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The address could not be saved. Please, try again.'));
        }
        $users = $this->Addresses->Users->find('list', ['limit' => 200]);
        $companies = $this->Addresses->Companies->find('list', ['limit' => 200]);
        $this->set(compact('address', 'users', 'companies'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Address id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $address = $this->Addresses->get($id);
        try {
            if ($this->Addresses->delete($address)) {
                $this->Flash->success(__('The address has been deleted.'));
            } else {
                $this->Flash->error(__('The address could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    

        return $this->redirect(['action' => 'index']);
    }
    
    public function getStatesList() {
        $this->layout = 'ajax';
        $countryId = $this->request->data['countryId'];
        $states = $this->Address->getStateListByCountryId($countryId);
        $this->set('states', $states);
    } 
    
    public function getCitiesList() {
        $this->layout = 'ajax';
        $stateId = $this->request->data['stateId'];
        $cities = $this->Address->getCityListByStateId($stateId);
        $this->set('cities', $cities);
    }
}
