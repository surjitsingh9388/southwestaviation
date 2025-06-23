<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class PTORequestsComponent extends Component {
    public array $components = ['Timezone', 'Authentication.Authentication'];

    protected \App\Model\Table\UserPTORequestsTable $UserPTORequests;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function getPTOReqestsDet($pto_request_id){
        $this->UserPTORequests = $this->getController()->fetchTable('UserPTORequests');

        $whereCond = ['UserPTORequests.id'=>$pto_request_id];
        $userptorequestlist = $this->UserPTORequests->find('all')
                                                ->where($whereCond)
                                                ->select($this->UserPTORequests)
                                                ->select(['users.full_name', 'request_logs.day_of_week', 'request_logs.date_of_day', 'request_logs.time_from', 'request_logs.time_to', 'request_logs.pto_to_use'])
                                                ->join([
                                                    'users'=>[
                                                        'table'=>'users',
                                                        'type'=>'INNER',
                                                        'conditions'=>'UserPTORequests.user_id = users.id'
                                                    ]
                                                ])
                                                ->join([
                                                    'request_logs'=>[
                                                        'table'=>'user_pto_request_logs',
                                                        'type'=>'INNER',
                                                        'conditions'=>'UserPTORequests.id = request_logs.pto_request_id'
                                                    ]
                                                ]);

        return $userptorequestlist;
    }

    public function getPTOReqestList($user_id = ''){
        $this->UserPTORequests = $this->getController()->fetchTable('UserPTORequests');
        $whereCond = [];
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['role_id'] != '1' && empty($user_id)){
            $whereCond['OR'] = ['user_id'=>$authUserData['id'], 'manager_id'=>$authUserData['id']];
        }
        if(!empty($user_id)){
            $whereCond['UserPTORequests.user_id'] = $user_id;
        }
        
        $userptorequestlist = $this->UserPTORequests->find('all', ['order'=>'UserPTORequests.id desc'])
                                                ->where($whereCond)
                                                ->select($this->UserPTORequests)
                                                ->select(['users.full_name'])
                                                ->join([
                                                    'users'=>[
                                                        'table'=>'users',
                                                        'type'=>'INNER',
                                                        'conditions'=>'UserPTORequests.user_id = users.id'
                                                    ]
                                                ]);

        return $userptorequestlist;
    }
}