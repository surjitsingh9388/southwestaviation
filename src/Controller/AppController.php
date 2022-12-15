<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginAction' => [
                'controller' => 'Pages',
                'action' => 'login'
            ],
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email'],
                    'finder' => 'auth'
                ]
            ],
            'authError' => 'Your session has expired, please login again.',
            'loginRedirect' => [
                'controller' => 'customer/Bookings',
                'action' => 'dashboard'
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'home'
            ],
            'authorize' => ['Controller']
        ]);
        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event) {
        //$customerRoles = array('Individual', 'Corporate Admin', 'Corporate User');
    }

    public function isAuthorized($user) {
        // Only customers can access every action
        $email = '';
        if ($this->request->getParam('prefix') === 'customer') {
            //$customerRoles = array('Individual', 'Corporate Admin', 'Corporate User');
            if (isset($user['id'])) {
                $email = $this->Auth->user('email');
                $this->set('userLoginEmail', $email);
                return true;
            } else {
                $this->request->session()->delete('Flash');
                $this->Flash->error(__('Please login to access customer portal.'));
                return $this->redirect(['controller' => 'Customers', 'action' => 'index', 'prefix' => 'customer']);
            }
        }
        // Default deny
        return false;
    }
}
