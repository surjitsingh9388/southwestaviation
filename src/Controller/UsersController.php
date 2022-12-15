<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Mailer\Exception;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Database\Expression\QueryExpression;

/**
 * Users Controller
 */
class UsersController extends AppController
{   
    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Address');
        $this->loadComponent('User');
        $this->loadComponent('Booking');
        $this->loadComponent('Timezone');
        $this->loadModel('ResetPasswords');
        $this->loadModel('EmergencyContacts');
        $this->loadModel('EmailQueues');
        $this->loadComponent('Company');
        $this->loadModel('Companies');
        $this->loadModel('UserMenuItems');

    }
    
    /**
     * This function used to allow access for controller actions without authentication.
     */
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    /**
     * Index method
     * This function used to get list of all users.
     *
     * @return array
     */
    public function index()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Users', $actionStatus))
            {
                $actionItems = $actionStatus['Users'];
            }
            $this->set(compact('actionItems'));
        }
    }    
    
}

