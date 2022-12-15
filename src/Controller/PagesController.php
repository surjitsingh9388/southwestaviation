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

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Mailer\Exception;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /** [initialize description] */
    public function initialize() {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Memberships');
        $this->loadModel('TierLevels');
        $this->loadModel('MembershipTypes');
        $this->loadModel('MembershipFees');
        $this->loadModel('FeeTypes');
        $this->loadModel('Routes');
        $this->loadModel('Airports');
        $this->loadModel('Source');
        $this->loadModel('Destination');
        $this->loadModel('ResetPasswords');
        $this->loadModel('Airports');
        $this->loadModel('EmailQueues');        
    }

    /**
     * This function used to allow access for controller actions without authentication.
     */
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        $this->Auth->allow(['home','login']);
    }

    /**
     * Login method
     * This function checks user identity.
     *
     * @access public
     * @return void
     */
    public function login() {
        $this->autoRender = false;
        if ($this->Auth->user('id')) {
            return $this->redirect(['controller' => 'Bookings', 'action' => 'dashboard', 'prefix' => 'customer']);    
        }  
        
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                if($user['suspended'] == 1){
                    $this->Flash->error(__('Sorry, your account is Suspended, please call us at +1-918-298-3718 or write to us at admin@tuxedoair.com for account Activation.'));
                    return $this->redirect($this->Auth->logout());
                }
                $user['role'] = isset($user['role']['role_name']) ? $user['role']['role_name'] : '';
                $this->Auth->setUser($user);
                $this->updateLastLoginTime();
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
            return $this->redirect(['action' => 'home']);
        } else {
            return $this->redirect(['action' => 'home']);
        }
    }

    /**
     * forgotPassword method
     * This function is used to sent mail request.
     *
     * @return Redirects on successful add, renders view otherwise.
     */
    public function forgotPassword() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $userData = $this->request->getData();  

            if (!empty($userData)) {
                $result = $this->sendPasswordDetails($userData);
                /*if ($result) {
                    $this->Flash->success(__(FORGOT_PASSWORD_EMAIL_SEND_SUCCESSFULLY));
                } else {
                    $this->Flash->error(__('The request could not be sent. Please try again.'));
                }*/
            } else {
                $this->Flash->error(__('Please enter required details.'));
            }
            return $this->redirect(['action' => 'home']);
        } else {
            $this->Flash->error(__('Invalid request. Please try again.'));
        }
    }

    /**
     * generateToken method
     * This function is used to generate reset password token.
     *
     * @return token.
     */
    public function generateToken($user_id=null) {
        if(empty($user_id)){
            $this->Flash->error(__('Invalid user data. Please try again.'));
            return $this->redirect(['action' => '/']);
        }

        $resetModel = TableRegistry::get('ResetPasswords');
        do {
            unset($exists);
            $token = bin2hex(openssl_random_pseudo_bytes(16));            
            $exists = $resetModel->find('all', ['conditions' => ['token' => $token]])->first();
        }while(count($exists) != 0);
        
        return $token;
    }

    /**
     * resetPassword method
     * This function is used to reset password.
     *
     * @return
     */
    public function resetPassword($token=null) {

        if(empty($token)){
            $this->Flash->error(__('Invalid token. Please try again.'));
            return $this->redirect(['action' => '/']);
        }
        
        $resetModel = TableRegistry::get('ResetPasswords');
        $tokenExist = $resetModel->find('all', ['conditions' => ['token' => $token]])->first();
        
        $this->set(compact('tokenExist'));
        if(empty($tokenExist)){
            $this->Flash->error(__('Invalid token. Please try again.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }
        $currentDateTime = strtotime(date('Y-m-d H:i:s'));
        $expired = strtotime(date('Y-m-d H:i:s',strtotime($tokenExist->expired)));
        if($currentDateTime > $expired){
            //delete existing tokens            
            $id = $tokenExist->id;
            $resetModel = TableRegistry::get('ResetPasswords');
            $resetPasswords = $resetModel->get($id);
            $resetModel->delete($resetPasswords);

            $this->Flash->error(__('Token expired. Please try again forgot password.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }
        $this->set('title', 'Reset Password');
        $this->viewBuilder()->setLayout('site');        
    }

    public function setNewPassword(){

        if ($this->request->is('post')) {
            $user = $this->Users->newEntity();
            $password = $this->request->getData();
            
            $user['id'] = $password['user_id'];
            
            $id = $password['ResetPasswords']['id'];
            $resetModel = TableRegistry::get('ResetPasswords');
            $resetPasswords = $resetModel->get($id);
            $user = $this->Users->get($resetPasswords->user_id);
            $user['password'] = $password['new_password'];
            if ($this->Users->save($user)) {
                $resetModel->delete($resetPasswords);
                $this->Flash->success(__('Password reset successfully.'));
                if($user->role_id == '1'){
                    return $this->redirect(['controller' => 'admin/users', 'action' => 'login']);
                }else{
                    return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
                }
            }
            $this->Flash->error(__('Somthing went worng. Please, try again.'));
        } else {
            $this->Flash->error(__('Invalid request. Please try again.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }
    }

    /**
     * sendEmailRequest method
     * This function is used to sent mail request.
     *
     * @return Redirects on successful add, renders view otherwise.
     */
    public function sendEmailRequest() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $redirectActions = array('refer', 'feedback', 'contactUs');
            $fromPage = $this->referer('/', true);
            $parse_url_params = Router::parse($fromPage);
            $userData = $this->request->getData();
            if (!empty($userData)) {
                if ($parse_url_params['action'] == 'contactUs' || $parse_url_params['action'] == 'howItWorks' || $parse_url_params['action'] == 'routeSuggestion' || $parse_url_params['action'] == 'meetYourTuxedoAirCrew' || $parse_url_params['action'] == 'nominateADestination' || $parse_url_params['action'] == 'currentRoutes') {
                    if (empty($userData['g-recaptcha-response'])) {
                        $this->Flash->error(__('Please verify you are not robot.'), ['clear' => 'true']);
                        return $this->redirect($this->referer());
                    } else if (!$this->validateCaptcha($userData['g-recaptcha-response'])) {
                        $this->Flash->error(__('Invalid Captcha!'), ['clear' => 'true']);
                        return $this->redirect($this->referer());
                    } else {
                        $result = $this->sendEmail($userData);
                    }
                } else {
                    $result = $this->sendEmail($userData);
                }
                if ($result) {
                    if ($fromPage == '/refer') {
                        $this->Flash->success(__('Your referral has been sent successfully.'), ['clear' => 'true']);
                    } else if ($fromPage == '/feedback') {
                        $this->Flash->success(__('Your feeback has been sent successfully.'), ['clear' => 'true']);
                    } else {
                        $this->Flash->success(__('Your request has been sent successfully. We will contact you shortly.'), ['clear' => 'true']);
                    }                    
                } else {
                    $this->Flash->error(__('The request could not be sent. Please try again.'), ['clear' => 'true']);
                }
            } else {
                $this->Flash->error(__('Please enter required details.'), ['clear' => 'true']);
            }
            // if request came from customers controller redirect to dashboard page
            if (in_array($parse_url_params['action'], $redirectActions) && ($parse_url_params['controller'] == 'Customers')) {
                return $this->redirect(['controller' => 'Bookings', 'action' => 'dashboard', 'prefix' => 'customer']);
            } else {
                return $this->redirect(['action' => 'home']);
            }
        }
    }
    
    /**
     * sendEmail method
     * To send request for become a member.
     *
     * @param array $emailData.
     * @return boolean true|false
     */    
    public function sendEmail($emailData = array()) {
        if (!empty($emailData)) {
            // set senders details
            $emailData['senderTitle'] = ADMIN_SENDER_NAME;
            $emailData['senderEmail'] = ADMIN_SENDER_EMAIL;
            $emailData['senderPhone'] = ADMIN_SENDER_PHONE;
            $emailData['senderAddress'] = ADMIN_SENDER_ADDRESS;
            //$email = new Email();
            $emailArr[] = $emailData['email'];
            $emailData['emailUsersList'] = $emailArr;
            //geting referer page
            $fromPage = $this->referer('/', true);
            $userId = $this->Auth->user('id');
            if(!empty($userId)){
                $userId = $userId;
            }else{
                $userId = '1';
            }
            $emailType='';
            //seting subject on the basis of referer page
            if ($fromPage == '/current-routes' || $fromPage == '/route-suggestion'){                
                if ($fromPage == '/current-routes') {
                    $subject = 'New request for routes';
                    //$emailData['subject'] = $subject;
                    $emailType['email_type'] = 'Current_Routes';
                } else {
                    $subject = 'New request for routes';
                    //$emailData['subject'] = $subject;
                    $emailType['email_type'] = 'Route_Suggestion';
                }
                $emailData['subject'] = $subject;
                //$email->template('currentRoutesAndRouteSuggestion');
            } else if ($fromPage == '/how-it-works' || $fromPage == '/meet-your-tuxedo-air-crew'){
                if($fromPage == '/how-it-works'){
                    $subject = 'New request for becoming a member on Tuxedo Air';
                    $emailData['phone_number'] = $emailData['phone'];
                    $emailData['fullName'] = $emailData['name'];
                    $emailType['email_type'] = 'How_It_Works';
                } else {
                    $subject = 'New request for becoming a member on Tuxedo Air';
                    $emailData['phone_number'] = $emailData['phone'];
                    $emailData['fullName'] = $emailData['name'];
                    $emailType['email_type'] = 'Meet_Your_Tuxedo_Air_Crew';
                }       
                $emailData['subject'] = $subject;         
                //$email->template('memberRequestEmail');
            } else if ($fromPage == '/contact-us' || strpos($fromPage, 'contactUs') !== false){
                $subject = $emailData['subject'];
                if (empty($subject)) {
                    $subject = 'Contact us';
                    $emailType['email_type'] = 'Contact_Us';
                } else {
                    $subject = 'Contact us: '.$subject;
                    $emailType['email_type'] = 'Contact_Us';
                }
                $emailData['subject'] = $subject;
                //$email->template('contactUs');
            } else if ($fromPage == '/nominate-a-destination') {
                $subject = 'Request for nominate a destination';
                $emailType['email_type'] = 'Nominate_A_Destination';
                $emailData['subject'] = $subject;
                //$email->template('nominateADestination');
            } else if ($fromPage == '/register-now'){
                $subject = 'New request for becoming a member on Tuxedo Air';
                $emailType['email_type'] = 'Register_Now';
                $emailData['subject'] = $subject;
                //$email->template('registerNow');
            } else if ($fromPage == '/feedback' || strpos($fromPage, 'feedback') !== false) {
                $subject = 'Feedback from: '.$emailData['name'];
                $emailType['email_type'] = 'Feedback';
                $emailData['subject'] = $subject;
                //$email->template('feedback');
            } else if ($fromPage == '/refer' || strpos($fromPage, 'refer') !== false)  {
                $subject = $emailData['subject'];
                $emailType['email_type'] = 'Refer';
                $emailData['subject'] = $subject;
                $emailData['email_to'] = $emailData['to_email'];
                $emailArr[] = $emailData['email_to'];
                $emailData['emailUsersList'] = $emailArr;
                //$email->template('refer');
            } else {
                $subject = 'New request for becoming a member on Tuxedo Air';
                $emailData['subject'] = $subject;
            }
            $emailData['url'] = Router::url('/', true);            
            // Setting email config 
            
            $emailQueueArr['email_type'] = $emailType['email_type'];
            $emailQueueArr['email_data'] = json_encode($emailData);
            $emailQueueArr['status'] = 0;
            $emailQueueArr['updated_by'] = $userId;
            $emailQueue = $this->EmailQueues->newEntity();
            $emailQueue = $this->EmailQueues->patchEntity($emailQueue, $emailQueueArr);
            //pr($emailQueue);exit;
            if ($this->EmailQueues->save($emailQueue)) {
                return true;
                //$this->Flash->success(__('Your request has been sent successfully. We will contact you shortly.'));
            } else {
                return false;
                //$this->Flash->error(__('The request could not be sent. Please try again.'));
            }
        }
    }

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function home($page = null) {
        $this->set('title', 'Home - Aircraft');
    }


    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function timeComparison() {
        $this->set('title', 'Time Comparison - Tuxedo Air');
        $this->viewBuilder()->setLayout('site');
    }

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function contactUs() {
        $this->set('title', 'Contact Us - Maintenance');
        $this->viewBuilder()->setLayout('site');
    }

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function feedback() {
        $this->set('title', 'Feedback - Maintenance');
        $this->viewBuilder()->setLayout('site');
    }

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function privacy() {
        $this->set('title', 'Privacy - Maintenance');
        $this->viewBuilder()->setLayout('site');
    }

    /**
     * IsEmailExist method
     * This function is used to check is plane name already exist.
     * 
     * @return boolean true/false
     */
    public function isEmailExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $email = $this->request->getData('email');
            $exists = $this->Users->exists(['email' => $email]);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    /**
     * IsEmailExist method
     * This function is used to check is email already exist.
     * 
     * @return boolean true/false
     */
    public function isEmailNotExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $email = $this->request->getData('email');
            $exists = $this->Users->exists(['email' => $email]);
            if ($exists) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }

    /**
     * updateLastLoginTime method
     * To update the login tme of user on login.
     *
     * @return void
     */
    public function updateLastLoginTime() {
        if ($this->Auth->user('id')) {
            $user = $this->Users->get($this->Auth->user('id'));
            $user->last_login = Time::now();
            $this->Users->save($user);
        }
    }
    
    /**
    * @access protected
    * Add validateCaptcha Method
    * @Method : validateCaptcha.
    * @Developer : 
    * @param : string|null $gRecaptchaResponse Recaptcha string.
    * @description: get Recaptcha string for validate user or robot. 
    * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */

    public function validateCaptcha($gRecaptchaResponse) {
        $post_data = http_build_query(
            array(
                'secret' => CAPTCHA_SECRET_KEY,
                'response' => $gRecaptchaResponse,
                'remoteip' => $_SERVER['REMOTE_ADDR']
                )
            );
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
                )
            );
        $context = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);
        if ($result->success) {
            return true;
        }
        return false;
    }
}
