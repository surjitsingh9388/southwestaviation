<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Mailer\Exception;
use Cake\Database\Expression\QueryExpression;
use Cake\Log\Log;

/**
 * Email Controller
 */
class EmailsController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->loadModel('EmailQueues');
        $this->loadModel('ClosedFlights');
        $this->loadComponent('Timezone');
        // $this->loadComponent('TwilioMessage');
    }
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }
    
    public function index($id = null) {
        $callType = null;
        $result = 0;
        if (!empty($id)) {
            $emailsModel = TableRegistry::get('EmailQueuesAudits');
            $emails = $emailsModel->find()
                ->select(['id', 'email_type', 'email_data'])
                ->where(['id' => $id])
                ->toArray();
            $callType = 'Dashboard';
        }else{
            $this->autoRender = false;
            $emails = $this->EmailQueues->find()
                    ->select(['id', 'email_type', 'email_data'])
                    ->where(['EmailQueues.status != 1'])->toArray();
        }
        
        if (!empty($emails)) {
            foreach($emails as $email) {
                $emailQueueId = $email['id'];
                $emailData = json_decode($email['email_data'], true);
                $emailData['requestType'] = $callType;
                if ($email['email_type'] == 'Payment') {
                    $result = $this->sendPaymentEmail($emailQueueId, $emailData);
                } else if ($email['email_type'] == 'Invoice') {
                    $result = $this->sendInvoiceEmail($emailQueueId, $emailData);
                } else if ($email['email_type'] == 'User_Welcome') {
                    $result = $this->sendUserWelcomeEmail($emailQueueId, $emailData);
                } else if ($email['email_type'] == 'Booking') {
                    $result = $this->sendBookingEmail($emailQueueId, $emailData);
                } else if ($email['email_type'] == 'Forgot_Password'){
                    $result = $this->sendForgotPasswordEmail($emailQueueId, $emailData);
                } else if ($email['email_type'] == 'Become_Member'){
                    $result = $this->sendBecomeMemberEmail($emailQueueId, $emailData);
                } else if ($email['email_type'] == 'Cancel_Booking'){
                    $result = $this->sendCancelBooking($emailQueueId, $emailData);
                } else if ($email['email_type'] == 'Update_Booking'){
                    $result = $this->sendUpdateBookingEmail($emailQueueId, $emailData);
                } else if ($email['email_type'] == 'Pilot_Closed_Flight'){
                    $result = $this->sendClosedFlightEmail($emailQueueId, $emailData);
                } else if($email['email_type'] == 'Current_Routes' || $email['email_type'] == 'Route_Suggestion' || $email['email_type'] == 'How_It_Works' || $email['email_type'] == 'Meet_Your_Tuxedo_Air_Crew' || $email['email_type'] == 'Contact_Us' || $email['email_type'] == 'Nominate_A_Destination' || $email['email_type'] == 'Register_Now' || $email['email_type'] == 'Feedback' || $email['email_type'] == 'Refer'){
                    //$this->sendEmail($emailQueueId, $emailData, $email['email_type']);
                    $result = $this->sendEmail($emailQueueId, $emailData, $email['email_type']);
                }
            }
        }
        if (!empty($id)) {
            if ($result == 1) {
                $this->Flash->success(__('Email has been sent successfully.'), ['clear' => 'true']);
            }elseif($result == 2){
                $this->Flash->error(__('Unable to send email.'), ['clear' => 'true']);
            }else{
                $this->Flash->error(__('No record found.'), ['clear' => 'true']);
            }
            return $this->redirect(['action' => 'index', 'controller' => 'Dashboard', 'prefix' => 'admin']);
        }
    }
    private function sendEmail($emailQueueId, $emailData, $emailType) {
        //pr($emailData);exit;
        $email = new Email();
        $email->transport('smtp');
        if($emailType == 'Refer'){
            $email->template('refer');
        }else if($emailType == 'Feedback'){
            $email->template('feedback');
        }else if($emailType == 'Register_Now'){
            $email->template('registerNow');
        }else if($emailType == 'Nominate_A_Destination'){
            $email->template('nominateADestination');
        }else if($emailType == 'Contact_Us'){
            $email->template('contactUs');
        }else if($emailType == 'Meet_Your_Tuxedo_Air_Crew'){
            $email->template('memberRequestEmail');
        }else if($emailType == 'How_It_Works'){
            $email->template('memberRequestEmail');
        }else if($emailType == 'Route_Suggestion'){
            $email->template('currentRoutesAndRouteSuggestion');
        }else if($emailType == 'Current_Routes'){
            $email->template('currentRoutesAndRouteSuggestion');
        }else{

        }
        $subject = APPLICATION_DEPLOYED_ON_SERVER.$emailData['subject'];
        $email->subject($subject);
        $email->emailFormat('html');
        if($emailType == 'Refer'){
            $email->from($emailData['email']);
            $toEmail = explode(',', $emailData['to_email']);
            $email->to($toEmail);
            //$email->cc(ADMIN_EMAIL);
        }else{
            $email->from([ADMIN_SENDER_EMAIL => ADMIN_SENDER_NAME]);
            $email->to(ADMIN_EMAIL);
        }
        //$email->cc(ADMIN_SENDER_EMAIL, ADMIN_SENDER_NAME);
        if (!empty(BCC_LIST_FOR_ALL_OUTGOING_MAILS)) {
            $bccList = explode(',', BCC_LIST_FOR_ALL_OUTGOING_MAILS);
            $email->addBcc($bccList);
        }
        $email->viewVars(['data' => $emailData]);
        try {
            if ($email->send()) {
                $status = 1;
            } else {
                $status = 2;
            }
        } catch (\Exception $e) {
            $status = 2;
        }
        if (empty($emailData['requestType'])) {
            //update task status
            $this->updateQueueStatus($emailQueueId, $status);
        }
        return $status;
    }

    private function sendForgotPasswordEmail($emailQueueId, $data) {
        // Setting email config
        $email = new Email();
        $email->transport('smtp');
        $email->template('forgotPassword');
        $email->emailFormat('html');
        // set senders information
        $email->from([ADMIN_SENDER_EMAIL => ADMIN_SENDER_NAME]);
        // set receiver information
        $email->to($data['email_to'], $data['name']);
        $email->bcc(ADMIN_SENDER_EMAIL, ADMIN_SENDER_NAME);
        if (!empty(BCC_LIST_FOR_ALL_OUTGOING_MAILS)) {
            $bccList = explode(',', BCC_LIST_FOR_ALL_OUTGOING_MAILS);
            $email->addBcc($bccList);
        }
        $subject = APPLICATION_DEPLOYED_ON_SERVER.$data['subject'];
        $email->subject($subject);
        $email->viewVars(['data' => $data]);
        try {
            if ($email->send()) {
                $status = 1;
            } else {
                $status = 2;
            }
        } catch (\Exception $e) {
            $status = 2;
        }
        if (empty($data['requestType'])) {
            //update task status
            $this->updateQueueStatus($emailQueueId, $status);
        }
        return $status;
    }
            
    /**
     * sendUserWelcomeEmail method
     * To send welcome email on creation of new user.
     *
     * @param integer $emailQueueId
     * @param array $emailData.
     * @return void
     */
    private function sendUserWelcomeEmail($emailQueueId, $data) {
        // Setting email config
        $email = new Email();
        $email->transport('smtp');
        $email->template('welcomeUserEmail');
        $email->emailFormat('html');
        // set senders information
        $email->from([ADMIN_SENDER_EMAIL => ADMIN_SENDER_NAME]);
        // set receiver information
        $email->to($data['email'], $data['fullName']);
        $email->cc(CC_LIST_FOR_USER_WELCOME_EMAIL);
        $email->bcc(BCC_LIST_FOR_USER_WELCOME_EMAIL);
        if (!empty(BCC_LIST_FOR_ALL_OUTGOING_MAILS)) {
            $bccList = explode(',', BCC_LIST_FOR_ALL_OUTGOING_MAILS);
            $email->addBcc($bccList);
        }
        $subject = APPLICATION_DEPLOYED_ON_SERVER.$data['subject'];
        $email->subject($subject);
        $email->viewVars(['data' => $data]);
        try {
            if ($email->send()) {
                $status = 1;
            } else {
                $status = 2;
            }
        } catch (\Exception $e) {
            $status = 2;
        }
        if (empty($data['requestType'])) {
            //update task status
            $this->updateQueueStatus($emailQueueId, $status);
        }
        return $status;
    }

    /* updateEmailCount method
     * This function is used to update payment_mail_sent_count field value by on sending of payment email.
     *
     * @param integer $paymentId
     * @return void.
     */
    public function updateEmailCount($id, $emailType) {
        if ($emailType == 'Payment') {
            $paymentModel = TableRegistry::get('Payments');
            $payment = $paymentModel->get($id);
            $count = !empty($payment->payment_mail_sent_count) ? $payment->payment_mail_sent_count : 0;
            $payment->payment_mail_sent_count = $count + 1;
            $paymentModel->save($payment);
        } else if ($emailType == 'Invoice') {
            $invoiceModel = TableRegistry::get('Invoices');
            $invoice = $invoiceModel->get($id);
            $count = !empty($invoice->invoice_mail_sent_count) ? $invoice->invoice_mail_sent_count : 0;
            $invoice->invoice_mail_sent_count = $count + 1;
            $invoiceModel->save($invoice);
        }
    }
    
    /**
     * updateQueueStatus method
     * This function is used to update status field value of email_queues table.
     *
     * @param integer $id
     * @return void.
     */
    public function updateQueueStatus($id, $status) {
        $queue = $this->EmailQueues->get($id);
        // if email send successfully status equals to 1 delete the entry from table
        if ($status == 1) {
            $this->EmailQueues->delete($queue);
        } else {
            $queue->status = $status;
            $this->EmailQueues->save($queue);
        }
    }

    /**
     * sendPassengerManifest method
     * This function is used to send Passenger Manifest email.
     *
     * @return void.
     */
    public function sendPassengerManifest() {
        $this->autoRender = false;
        $currentDate = date('Y-m-d H:i:00');
        //$currentDate = '2019-01-28 07:20:00';
        $durations = explode(',', PASSENGER_MANIFEST_SCHEDULER);
        foreach ($durations as $duration) {
            $flightDetails = $this->getBookedFlights($duration, $currentDate);
            if (!empty($flightDetails)) {
                // Get flight journey date time
                $flightDate = $this->getDateWithDuration($duration, $currentDate);
                $printDate = new Time($flightDate);
                $subject = 'Passenger Manifest ' . trim($duration) . ' before for ' . $printDate->format('M j, Y h:i A') .' ('.env('TIMEZONE').') Flights';
                $data['url'] = Router::url('/', true);
                // Setting email config
                $email = new Email();
                $email->transport('smtp');
                $email->template('passengerManifest');
                $email->emailFormat('html');
                // set senders information
                $email->from([ADMIN_SENDER_EMAIL => ADMIN_SENDER_NAME]);
                // set receivers
                $receivers = explode(',', PASSENGER_MANIFEST_EMAIL_LIST);
                foreach ($receivers as $receiver) {
                    $email->addTo(trim($receiver));
                }
                if (!empty(BCC_LIST_FOR_ALL_OUTGOING_MAILS)) {
                    $bccList = explode(',', BCC_LIST_FOR_ALL_OUTGOING_MAILS);
                    $email->addBcc($bccList);
                }
                //$email->to($data['email'], $data['full_name']);
                //$email->bcc(ADMIN_SENDER_EMAIL, ADMIN_SENDER_NAME);
                $subject = APPLICATION_DEPLOYED_ON_SERVER.$subject;
                $email->subject($subject);
                $email->viewVars(['flightDetails' => $flightDetails, 'data' => $data]);
                try {
                    if ($email->send()) {
                        echo 'Email sent successfully';
                    } else {
                        echo 'Unable to send email';
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }
                        
    /* getDateWithDuration method
     * This function is used to get date after adding the specified time duration on current date.
     *
     * @param string $duration
     * @param string $currentDate
     * @return string $flightDate
     */
    private function getDateWithDuration($duration, $currentDate) {
        $diff = explode(' ', trim($duration));
        $durationTime = (int)str_replace("'","",$diff[0]);
        $checkDate = new Time($currentDate);
        if ($diff[1] == 'MINUTE') {
            $timeDiff = '+'.$durationTime.' minutes';
            $checkDate->modify($timeDiff);
        } else if ($diff[1] == 'HOUR') {
            $timeDiff = '+'.$durationTime.' hours';
            $checkDate->modify($timeDiff);
        }
        $flightDate = $checkDate->format('Y-m-d H:i:s');
        return $flightDate;
    }

}