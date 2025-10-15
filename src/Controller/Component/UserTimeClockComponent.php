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
use Cake\ORM\Query\SelectQuery;

class UserTimeClockComponent extends Component {
    public array $components = ['Timezone', 'Authentication.Authentication'];

    protected \App\Model\Table\UserMenuItemsTable $UserTimeClocks;
    protected \App\Model\Table\UsersTable $Users;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function validateUserAccessCode($time_clock_code){
        $this->Users = $this->getController()->fetchTable('Users');

        $authUserData = $this->Authentication->getResult()->getData();
        $is_valid_time_clock_code = $this->Users->find('all')->where(['id'=>$authUserData['id'], 'time_clock_code'=>$time_clock_code])->count();

        return $is_valid_time_clock_code;
    }

    public function getUserTimeClockDetail(){
        $UserTimeClocks = $this->getController()->fetchTable('UserTimeClocks');

        $authUserData = $this->Authentication->getResult()->getData();
        $usertimeclockdata = $UserTimeClocks->find('all', ['order'=>['id'=>'desc']])->where(['user_id'=>$authUserData['id'], 'DATE(in_time)'=>date('Y-m-d')])->first();

        return $usertimeclockdata;
    }

    public function getAllTimeClockActiveUsers(){
        $todaydate = date('Y-m-d');
        
        $connection = ConnectionManager::get('default');
        
        $authUserData = $this->Authentication->getResult()->getData();

        $cond = '';
        if(!empty($authUserData['is_manager']) && !empty($authUserData['team_member_id'])){
            $teamIdsArray = array_map('intval', explode(',', $authUserData['team_member_id'])); // Ensure values are integers
            $teamIdsSql = '(' . implode(',', $teamIdsArray) . ')';

            $cond .= " AND (tc.user_id IN $teamIdsSql OR tc.user_id = " . (int)$authUserData['id'] . ")";
        }else if($authUserData['role_id'] != 1){
            $cond .= " AND tc.user_id = ".$authUserData['id'];
        }
        
        $usertimeclocks = $connection->execute(
            "SELECT tc.id, tc.in_time, ROUND(SUM(TIMESTAMPDIFF(SECOND, tc.in_time, '".date("Y-m-d H:i:s ")."')) / 3600, 2) AS totaltime, u.full_name FROM user_time_clocks tc JOIN users u ON tc.user_id = u.id WHERE DATE(tc.in_time) = '".$todaydate."' and tc.out_time is NULL $cond GROUP BY tc.user_id ORDER BY tc.in_time")->fetchAll('assoc');
        //print_r($usertimeclocks);exit;
        return $usertimeclocks;
    }

    public function getUserListDropDown($user_id=''){
        $this->Users = $this->getController()->fetchTable('Users');
        
        $wherecond = ['Users.suspended' => '0'];

        $authUserData = $this->Authentication->getResult()->getData();
        
        if(!empty($authUserData['is_manager']) && !empty($authUserData['team_member_id'])){
            $teamIdsArray = array_map('trim', explode(',', $authUserData['team_member_id']));
            
            $wherecond['OR'] = [
                                    'Users.id IN' => $teamIdsArray,
                                    'Users.id' => $authUserData['id']
                                ];
        }else if($authUserData['role_id'] != 1){
            $wherecond['Users.id'] = $authUserData['id'];
        }

        $userdet = $this->Users->find()
            ->select(['Users.id', 'Users.full_name'])
            ->where($wherecond);
                                
        $userlist = [];
        foreach($userdet as $users){
            $userlist[$users['id']] = $users['full_name'];
        }
        
        return $userlist;
    }

    public function filterUserTimeClock($filter){
        $UserTimeClocks = $this->getController()->fetchTable('UserTimeClocks');

        $usertimeclockdata = $UserTimeClocks->find('all')->where($filter)->select($UserTimeClocks);

        return $usertimeclockdata;
    }

    public function filterTimeClockUserLogs($postData, $report_date){
        $connection = ConnectionManager::get('default');
        
        $authUserData = $this->Authentication->getResult()->getData();

        $wherecond = '';
        if(isset($postData['user_id']) && !empty($postData['user_id'])){
            $wherecond = " and tc.user_id = '".$postData['user_id']."'";
        }else if(!empty($authUserData['is_manager']) && !empty($authUserData['team_member_id'])){
            $teamIdsArray = array_map('intval', explode(',', $authUserData['team_member_id'])); // Ensure values are integers
            $teamIdsSql = '(' . implode(',', $teamIdsArray) . ')';

            $wherecond .= " AND (tc.user_id IN $teamIdsSql OR tc.user_id = " . (int)$authUserData['id'] . ")";
        }

        $usertimeclocks = $connection->execute(
            "SELECT tc.id, tc.in_time, tc.out_time, tc.user_id, (CASE WHEN tc.out_time is not NULL THEN (ROUND(TIMESTAMPDIFF(SECOND, tc.in_time, tc.out_time ) / 3600, 2)) ELSE 0 END) as totaltime, u.full_name FROM `user_time_clocks` tc join users u on tc.user_id = u.id WHERE DATE(tc.in_time) = '".$report_date."'".$wherecond." order by DATE(tc.in_time), u.full_name")->fetchAll('assoc');
        //print_r($usertimeclocks);exit;
        return $usertimeclocks;
    }

    public function getAllUsersTimeClockDateRange($postData){
        $fromdate = date('Y-m-d', strtotime($postData['date_from']));
        $todate = date('Y-m-d', strtotime($postData['date_to']));
        
        $connection = ConnectionManager::get('default');

        $authUserData = $this->Authentication->getResult()->getData();
        $wherecond = '';
        if(!empty($authUserData['is_manager']) && !empty($authUserData['team_member_id'])){
            $teamIdsArray = array_map('intval', explode(',', $authUserData['team_member_id'])); // Ensure values are integers
            $teamIdsSql = '(' . implode(',', $teamIdsArray) . ')';

            $wherecond = " AND (u.id IN $teamIdsSql OR u.id = " . (int)$authUserData['id'] . ")";
        }else if($authUserData['role_id'] != '1'){
            $wherecond = " AND u.id = " . (int)$authUserData['id'];
        }
        
        $usertimeclocks = $connection->execute(
            "SELECT u.full_name, (select ROUND(SUM(TIMESTAMPDIFF(SECOND, in_time, out_time )) / 3600, 2) from user_time_clocks where user_id = u.id and DATE(in_time) BETWEEN '".$fromdate."' and '".$todate."' and out_time != '') as totaltime FROM users u where 1=1 $wherecond order by u.full_name")->fetchAll('assoc');
        //print_r($usertimeclocks);exit;
        return $usertimeclocks;
    }

    public function getAllTechnicianProductivityData($postData){
        $fromdate = date('Y-m-d', strtotime($postData['date_from']));
        $todate = date('Y-m-d', strtotime($postData['date_to']));
        
        $connection = ConnectionManager::get('default');
        
        $authUserData = $this->Authentication->getResult()->getData();
        $wherecond = '';
        if(!empty($authUserData['is_manager']) && !empty($authUserData['team_member_id'])){
            $teamIdsArray = array_map('intval', explode(',', $authUserData['team_member_id'])); // Ensure values are integers
            $teamIdsSql = '(' . implode(',', $teamIdsArray) . ')';

            $wherecond = " AND (u.id IN $teamIdsSql OR u.id = " . (int)$authUserData['id'] . ")";
        }else if($authUserData['role_id'] != '1'){
            $wherecond = " AND u.id = " . (int)$authUserData['id'];
        }
        
        $usertimeclocks = $connection->execute(
            "SELECT tc.id, tc.in_time, tc.out_time, tc.user_id, u.full_name, (CASE WHEN tc.out_time is not NULL THEN (ROUND(SUM(TIMESTAMPDIFF(SECOND, tc.in_time, tc.out_time )) / 3600, 2)) ELSE 0 END) as totaltime, COUNT(DISTINCT DATE(tc.in_time)) as days FROM `user_time_clocks` tc join users u on tc.user_id = u.id WHERE DATE(tc.in_time) between '".$fromdate."' and '".$todate."' $wherecond group by tc.user_id order by DATE(tc.in_time), u.full_name")->fetchAll('assoc');
        //print_r($usertimeclocks);exit;
        return $usertimeclocks;
    }

    public function getAllUsersTimeClockReportSummary($report_date){
        $connection = ConnectionManager::get('default');
        
        $authUserData = $this->Authentication->getResult()->getData();
        $wherecond = '';
        if(!empty($authUserData['is_manager']) && !empty($authUserData['team_member_id'])){
            $teamIdsArray = array_map('intval', explode(',', $authUserData['team_member_id'])); // Ensure values are integers
            $teamIdsSql = '(' . implode(',', $teamIdsArray) . ')';

            $wherecond = " AND (u.id IN $teamIdsSql OR u.id = " . (int)$authUserData['id'] . ")";
        }else if($authUserData['role_id'] != '1'){
            $wherecond = " AND u.id = " . (int)$authUserData['id'];
        }

        $usertimeclocks = $connection->execute(
            "SELECT u.full_name, (select ROUND(SUM(TIMESTAMPDIFF(SECOND, in_time, out_time )) / 3600, 2) from user_time_clocks where user_id = u.id and DATE(in_time) = '".$report_date."' and out_time != '') as totaltime FROM users u where 1=1 $wherecond order by u.full_name")->fetchAll('assoc');
        //print_r($usertimeclocks);exit;
        return $usertimeclocks;
    }

    public function getAllEmpTimeClockDetail($report_date){
        $connection = ConnectionManager::get('default');
        
        $authUserData = $this->Authentication->getResult()->getData();
        $wherecond = '';
        if(!empty($authUserData['is_manager']) && !empty($authUserData['team_member_id'])){
            $teamIdsArray = array_map('intval', explode(',', $authUserData['team_member_id'])); // Ensure values are integers
            $teamIdsSql = '(' . implode(',', $teamIdsArray) . ')';

            $wherecond = " AND (tc.user_id IN $teamIdsSql OR tc.user_id = " . (int)$authUserData['id'] . ")";
        }else if($authUserData['role_id'] != '1'){
            $wherecond = " AND tc.user_id = " . (int)$authUserData['id'];
        }

        $usertimeclocks = $connection->execute(
            "SELECT tc.id, tc.in_time, tc.out_time, tc.user_id, (CASE WHEN tc.out_time is not NULL THEN (ROUND(TIMESTAMPDIFF(SECOND, tc.in_time, tc.out_time ) / 3600, 2)) ELSE 0 END) as totaltime, u.full_name FROM `user_time_clocks` tc join users u on tc.user_id = u.id WHERE DATE(tc.in_time) = '".$report_date."' and tc.out_time != '' $wherecond order by DATE(tc.in_time), u.full_name")->fetchAll('assoc');
        //print_r($usertimeclocks);exit;
        return $usertimeclocks;
    }

    public function timeClockLogReports($postData){
        $mainHtml = '';
        if($postData['time_clock_log_report'] == '1'){
            $mainHtml = $this->individualEmpTimeClockDetReport($postData);
        }else if($postData['time_clock_log_report'] == '2'){
            $mainHtml = $this->indEmpTechnicianComparison($postData);
        }else if($postData['time_clock_log_report'] == '3'){
            $mainHtml = $this->allEmpDateRangeSummaryReport($postData);
        }else if($postData['time_clock_log_report'] == '4'){
            $mainHtml = $this->allEmpTimeClockDetailReport($postData);
        }else if($postData['time_clock_log_report'] == '5'){
            $mainHtml = $this->allEmpTimeClockSummaryReport($postData);
        }else if($postData['time_clock_log_report'] == '6'){
            $mainHtml = $this->allTechniciansProductivityReport($postData);
        }

        return $mainHtml;
    }

    public function individualEmpTimeClockDetReport($postData){
        $this->Users = $this->getController()->fetchTable('Users');
        
        $userdet = $this->Users->get($postData['user_id']);
        $mainHtml = '';
        if(!empty($userdet)){
            $datemsg = $postData['date_from'].' to '.$postData['date_to'];
            if($postData['date_from'] == $postData['date_to']){
                $datemsg = $postData['date_from'];
            }
            
            $mainHtml .= '<table class="main-table" cellspacing="0">
                                <tr class="main-tr">
                                    <td class="main-td" colspan="2">
                                        <table>
                                            <tr>
                                                <td valign="top">
                                                    <table>
                                                        <tr>
                                                            <td class="mid-header">
                                                                Time Clock Report for: '.$userdet['full_name'].' ('.$datemsg.')
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>';
            
            $from = new \DateTime($postData['date_from']);
            $to = new \DateTime($postData['date_to']);
            $to->modify('+1 day'); // Include the end date

            $interval = new \DateInterval('P1D'); // 1 Day
            $period = new \DatePeriod($from, $interval, $to);

            $noofdays = 0;
            $total_hour_worked = 0;

            foreach ($period as $date) {
                $totaltime = 0;

                $report_date = $date->format('Y-m-d');

                $mainHtml .= '<tr>
                                    <td valign="top" class="time-table-td">
                                        <span class="date-class">'.$date->format('m/d/Y').'</span>
                                    </td>
                                    <td align="right" class="time-table-td">
                                        <table cellspacing="0" class="time-table" >';
            
                $totaltime = 0;
                $dates = '';
                
                $usertimeclocklist = $this->filterTimeClockUserLogs($postData, $report_date);
                foreach($usertimeclocklist as $clock){
                    $total_hour_worked += $clock['totaltime'];
                    $totaltime += $clock['totaltime'];
                    
                    $intimestr = strtotime(date('Y-m-d', strtotime($clock['in_time'])));

                    $out_time = !empty($clock['out_time']) ? date('h:i:s A', strtotime($clock['out_time'])) : '';
                    
                    $mainHtml .= '<tr class="first-row">
                                        <td width="150">In: '.date('h:i:s A', strtotime($clock['in_time'])).'</td>
                                        <td width="150" align="right">Out: '.$out_time.'</td>
                                        <td width="100" align="right">Time:</td>
                                        <td width="100" align="right">'.$clock['totaltime'].'</td>
                                    </tr>';
                }

                $mainHtml .= '<tr class="second-row">
                                <td colspan="3" align="right" >Hours Worked for Day:</td>
                                <td align="right">'.$totaltime.'</td>
                            </tr>
                        </table>
                    </td>
                </tr>';
            }

            $mainHtml .= '<tr>
                                <td colspan="2" align="right">
                                    <table cellspacing="0" border="0">
                                        <tr class="second-row">
                                            <td colspan="3" align="right" >Total Hours Worked:</td>
                                            <td align="right" width="98">'.$total_hour_worked.'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>';
        }
        return $mainHtml;
    }

    public function indEmpTechnicianComparison($postData){
        $this->Users = $this->getController()->fetchTable('Users');

        $userdet = $this->Users->get($postData['user_id']);
        $mainHtml = '';
        if(!empty($userdet)){
            $datemsg = $postData['date_from'].' to '.$postData['date_to'];
            if($postData['date_from'] == $postData['date_to']){
                $datemsg = $postData['date_from'];
            }

            $mainHtml .= '<table class="main-table" cellspacing="0">
                            <tr class="main-tr">
                                <td class="main-td">
                                    <table>
                                        <tr>
                                            <td valign="top">
                                                <table>
                                                    <tr>
                                                        <td class="mid-header">
                                                            Time Clock vs. Time Worked for: '.$userdet['full_name'].' ('.$datemsg.')
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>';
            
            $from = new \DateTime($postData['date_from']);
            $to = new \DateTime($postData['date_to']);
            $to->modify('+1 day'); // Include the end date

            $interval = new \DateInterval('P1D'); // 1 Day
            $period = new \DatePeriod($from, $interval, $to);

            $noofdays = 0;
            $total_hour_worked = 0;

            foreach ($period as $date) {
                $totaltime = 0;

                $report_date = $date->format('Y-m-d');
                $noofdays++;

                $mainHtml .= '<tr>
                                    <td class="time-table-td">
                                        <span class="date-class-bg">'.$date->format('m/d/Y').'</span>';

                $usertimeclocklist = $this->filterTimeClockUserLogs($postData, $report_date);
                foreach($usertimeclocklist as $clock){
                    $totaltime += $clock['totaltime'];

                    $in_time = !empty($clock['in_time']) ? date('h:i:s A', strtotime($clock['in_time'])) : '';
                    $out_time = !empty($clock['out_time']) ? date('h:i:s A', strtotime($clock['out_time'])) : '';

                    $total_hour_worked += $clock['totaltime'];

                    $mainHtml .= '<table cellpadding="10" cellspacing="0" class="table-time-work">
                                    <tr>
                                        <td width="200">'.$in_time.'</td>
                                        <td>'.$clock['totaltime'].'</td>
                                        <td align="right">'.$out_time.'</td>
                                    </tr>
                                </table>';

                }
                
                $mainHtml .= '<table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td valign="top">
                                            <table>
                                                <tr>
                                                    <td class="summary-style">Summary for:</td>
                                                </tr>
                                                <tr>
                                                    <td class="summary-date">'.$date->format('m/d/Y').'</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table border="0" cellpadding="5" class="table-hrs">
                                                <tr>
                                                    <td align="right">Total Time Clock Hours:</td>
                                                    <td width="100">'.$totaltime.'</td>
                                                </tr>
                                                <tr>
                                                    <td align="right">Hours accounted for:</td>
                                                    <td width="100">0.00</td>
                                                </tr>
                                                <tr>
                                                    <td align="right">Hours unaccounted for:</td>
                                                    <td width="100">0.00</td>
                                                </tr>
                                                <tr>
                                                    <td align="right">Productivity:</td>
                                                    <td width="100">0.00%</td>
                                                </tr>
                                                <tr>
                                                    <td align="right">Override hours not accounted for:</td>
                                                    <td width="100">0.00</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            </tr>';
            }
            $mainHtml .= '<tr>
                            <td>
                            <table cellspacing="0">
                                <tr>
                                <td width="300" valign="top">
                                    <table class="table-hrs">
                                    <tr>
                                        <td><b>Summary for All Days Above</b></td>
                                    </tr>
                                    <tr>
                                        <td><i>`Hours accounted for` does not include time overrides</i></td>
                                    </tr>
                                    <tr>
                                        <td><i>This report compare actual time worked, and not time overrides, which can be viewed from the Technician Logs.</i></td>
                                    </tr>
                                    </table>
                                </td>
                                <td width="300" valign="top">
                                    <table class="table-hrs" cellpadding="5" border="0">
                                    <tr>
                                        <td align="right"><b>Total Time Clock Hours:</b></td>
                                        <td width="100">'.$total_hour_worked.'</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><b>Hours accounted for:</b></td>
                                        <td width="100">0.00</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><b>Hours unaccounted for:</b></td>
                                        <td width="100">0.00</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><b>Number of Days:</b></td>
                                        <td width="100">'.$noofdays.'</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><b>Average Hours Per Day:</b></td>
                                        <td width="100">'.round($total_hour_worked/$noofdays, 2).'</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><b>Productivity:</b></td>
                                        <td width="100">0.00 %</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><b>Override hours not accounted for:</b></td>
                                        <td width="100">0.00</td>
                                    </tr>
                                    </table>
                                </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                        </table>';
        }
        
        return $mainHtml;
    }

    public function allEmpDateRangeSummaryReport($postData){
        $usertimeclocklist = $this->getAllUsersTimeClockDateRange($postData);
        
        $mainHtml = '';
        if(isset($usertimeclocklist[0]['full_name'])){
            $datemsg = $postData['date_from'].' to '.$postData['date_to'];
            if($postData['date_from'] == $postData['date_to']){
                $datemsg = $postData['date_from'];
            }
            
            $mainHtml .= '<table class="main-table" >
                            <tr class="main-tr">
                                <td class="main-td rm-border">
                                    <table>
                                        <tr>
                                            <td valign="top">
                                                <table>
                                                    <tr>
                                                        <td class="mid-header">
                                                            Time Clock Date Range Summary ('.$datemsg.')
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>';

            $mainHtml .= '<tr>
                            <td>
                                <table border="0" class="emp-hours-table" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <td><b>Employee</b></td>
                                            <td width="200" align="right"><b>Regular Hours</b></td>
                                            <td width="150" align="right"><b>Overtime Hours</b></td>
                                        </tr>
                                    </thead>
                                ';
            $total_hour_worked = 0;
            foreach($usertimeclocklist as $clock){
                $mainHtml .= '<tr>
                                <td style="border-bottom: 1px solid #7f7f7f; padding: 2px 0px 2px 0px;">'.$clock['full_name'].'</td>
                                <td style="border-bottom: 1px solid #7f7f7f; padding: 2px 0px 2px 0px;" align="right">'.(!empty($clock['totaltime']) ? $clock['totaltime'] : '0.00').'</td>
                                <td style="border-bottom: 1px solid #7f7f7f; padding: 2px 0px 2px 0px;" align="right">0.00</td>
                            </tr>';
            }

            $mainHtml .= '
                        </table>
                    </td>
                </tr>
            </table>';
        }
        
        return $mainHtml;
    }

    public function allEmpTimeClockDetailReport($postData){
        $mainHtml = '';
        
        $datemsg = $postData['date_from'].' to '.$postData['date_to'];
        if($postData['date_from'] == $postData['date_to']){
            $datemsg = $postData['date_from'];
        }

        $mainHtml .= '<table class="main-table" >
                        <tr class="main-tr">
                            <td class="main-td rm-border">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td valign="top">
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td class="mid-header">
                                                        All Employees - Time Clock Detail ('.$datemsg.')
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td> 
                        </tr>';
        
        $from = new \DateTime($postData['date_from']);
        $to = new \DateTime($postData['date_to']);
        $to->modify('+1 day'); // Include the end date

        $interval = new \DateInterval('P1D'); // 1 Day
        $period = new \DatePeriod($from, $interval, $to);
        $total_hour_worked = 0;
        
        foreach ($period as $date) {
            $report_date = $date->format('Y-m-d');
            $mainHtml .= '<tr>
                    <td>';
            $tothrsworkedforallemps = 0;
            $mainHtml .= '<table class="date-group-table">
                                <tr>
                                <td><span>'.$date->format('m/d/Y').'</span></td>
                                </tr>
                            </table>';
            
            
            $usertimeclocklist = $this->getAllEmpTimeClockDetail($report_date);

            $user_id = '';
            $totaltime = 0;
            if(!empty($usertimeclocklist)){
                foreach($usertimeclocklist as $clock){
                    $out_time = !empty($clock['out_time']) ? date('h:i:s A', strtotime($clock['out_time'])) : '';
                    
                    if(empty($user_id)){
                        $mainHtml .= '<table border="0" class="emp-clock-table">
                                        <tr>
                                            <td class="emp-title"><span>'.$clock['full_name'].'</span></td>
                                        </tr>
                                        <tr>
                                            <td align="right">
                                            <table class="emp-block-table" border="0" cellpadding="0" cellspacing="0">';
                    }

                    if(!empty($user_id) && $user_id != $clock['user_id']){
                        $mainHtml .= '<tr class="border-hide">
                                            <td align="right" colspan="3">Hours Worked for Day:</td>
                                            <td align="right">'.$totaltime.'</td>
                                        </tr>
                                        </table>  
                                    </td>
                                    </tr>
                                    </table>
                                    </td>
                                  </tr>';
                        $totaltime = $clock['totaltime'];
                        $user_id = $clock['user_id'];
                        $mainHtml .= '<table border="0" class="emp-clock-table">
                                        <tr>
                                            <td class="emp-title"><span>'.$clock['full_name'].'</span></td>
                                        </tr>
                                        <tr>
                                            <td align="right">
                                            <table class="emp-block-table" border="0" cellpadding="0" cellspacing="0">';
                    }else{
                        $totaltime += $clock['totaltime'];
                        $user_id = $clock['user_id'];
                    }

                    $total_hour_worked += $clock['totaltime'];
                    $tothrsworkedforallemps += $clock['totaltime'];

                    $mainHtml .= '<tr>
                                    <td align="left" >In: '.date('h:i:s A', strtotime($clock['in_time'])).'</td>
                                    <td align="left" >Out: '.$out_time.'</td>
                                    <td align="right" >Time:</td>
                                    <td align="right" >'.$clock['totaltime'].'</td>
                                </tr>';
                }
                $mainHtml .= '<tr class="border-hide">
                                            <td align="right" colspan="3">Hours Worked for Day:</td>
                                            <td align="right">'.$totaltime.'</td>
                                        </tr>
                                        </table>  
                                    </td>
                                    </tr>
                                    </table>
                                    </td>
                              </tr>';
                $mainHtml .= '<tr>
                                <td class="emp-td-border">
                                <table border="0" class="emp-bottom-table" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td align="right">Total Hours Worked for Day for All Employee:</td>
                                    <td width="130" align="right">'.$tothrsworkedforallemps.'</td>
                                    </tr>
                                </table>
                                </td>
                            </tr>';
            }
            $mainHtml .= '</td>
                    </tr>';
        }
        $mainHtml .= '<tr>
                            <td class="emp-td-border">
                            <table border="0" class="emp-bottom-table" cellspacing="0" cellpadding="0">
                                <tr class="emp-bt-border">
                                <td align="right">Total Hours Worked:</td>
                                <td width="130" align="right">'.$total_hour_worked.'</td>
                                </tr>
                            </table>
                            </td>
                        </tr>';

        $mainHtml .= '</table>';
        return $mainHtml;
    }

    public function allTechniciansProductivityReport($postData){
        $usertimeclocklist = $this->getAllTechnicianProductivityData($postData);

        $mainHtml = '';
        if(isset($usertimeclocklist[0]['full_name'])){
            $datemsg = $postData['date_from'].' to '.$postData['date_to'];
            if($postData['date_from'] == $postData['date_to']){
                $datemsg = $postData['date_from'];
            }

            $mainHtml .= '<table class="main-table" >
                            <tr class="main-tr">
                            <td class="main-td">
                                <table>
                                <tr>
                                    <td valign="top">
                                    <table>
                                        <tr>
                                        <td class="mid-header">
                                            Technician Productivity ('.$datemsg.')
                                        </td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                                </table>
                            </td>
                            </tr>';

            $mainHtml .= '<tr>
            <td>
              <table>
                <tr>
                  <td><b>(No User Grouping)</b></td>
                </tr>
              </table>
              <table class="tech-table" cellpadding="0" cellspacing="0">
                <thead>
                  <tr>
                    <td>&nbsp;</td>
                    <td class="td-space" valign="bottom"><b>Technician</b></td>
                    <td width="150" align="right" valign="bottom"><b>Days</b></td>
                    <td width="120" align="right"><b>Avg Hrs<br/>Per Day</b></td>
                    <td width="80" align="right"><b>TClock<br/>Hours</b></td>
                    <td width="120" align="right"><b>Accounted<br/>Hours</b></td>
                    <td width="120" align="right"><b>Unaccounted<br/>Hours</b></td>
                    <td width="140" align="right" valign="bottom"><b>Productivity%</b></td>
                  </tr>
                </thead>
      
                <tbody>';
            $total_hour_worked = 0;
            foreach($usertimeclocklist as $keys=>$clock){
                $mainHtml .= '<tr>
                                <td>'.($keys+1).'</td>
                                <td class="td-space">'.$clock['full_name'].'</td>
                                <td align="right">'.$clock['days'].'</td>
                                <td align="right">'.round($clock['totaltime']/$clock['days'], 2).'</td>
                                <td align="right">'.$clock['totaltime'].'</td>
                                <td align="right">0.00</td>
                                <td align="right">0.00</td>
                                <td align="right">0.00%</td>
                            </tr>';
            }

            $mainHtml .= '</tbody>
                        </table>
                    </td>
                    </tr>
                </table>';
        }

        return $mainHtml;
    }

    public function allEmpTimeClockSummaryReport($postData){
        $mainHtml = '';
        
        $datemsg = $postData['date_from'].' to '.$postData['date_to'];
        if($postData['date_from'] == $postData['date_to']){
            $datemsg = $postData['date_from'];
        }

        $mainHtml .= '<table class="main-table" >
                        <tr class="main-tr">
                        <td class="main-td rm-border">
                            <table>
                            <tr>
                                <td valign="top">
                                <table>
                                    <tr>
                                        <td class="mid-header">Time Clock Report Summary ('.$datemsg.')</td>
                                    </tr>
                                </table>
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>';

        $from = new \DateTime($postData['date_from']);
        $to = new \DateTime($postData['date_to']);
        $to->modify('+1 day'); // Include the end date

        $interval = new \DateInterval('P1D'); // 1 Day
        $period = new \DatePeriod($from, $interval, $to);

        foreach ($period as $date) {
            $report_date = $date->format('Y-m-d');
            $mainHtml .= '<tr><td>';
            $mainHtml .= '<table class="date-group-table">
                            <tr>
                            <td><span class="tcs_date_heading">'.$date->format('m/d/Y').'</span></td>
                            </tr>
                        </table>';
            $usertimeclocklist = $this->getAllUsersTimeClockReportSummary($report_date);
            $mainHtml .= '<table class="tcs-table" cellpadding="0" cellspacing="0">';
            foreach($usertimeclocklist as $clock){
                $mainHtml .= '<tr>
                                <td class="tsc_td">'.$clock['full_name'].'</td>
                                <td align="right" class="tsc_td">'.(!empty($clock['totaltime']) ? $clock['totaltime'] : '0.00').'</td>
                            </tr>';
            }
            $mainHtml .= '</table>';
            $mainHtml .= '</tr></td>';
        }

        $mainHtml .= '</table>';
        
        return $mainHtml;
    }

    public function getTimeClockDetailByUser($fromdate, $todate, $user_id){
        $connection = ConnectionManager::get('default');
        $startTime = strtotime($fromdate);
        $endTime = strtotime($todate);
        $usertimeclocks = [];
        for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
            $thisDate = date( 'Y-m-d', $i );
            $timeclocks = $connection->execute(
                "SELECT tc.id, tc.in_time, tc.out_time, tc.user_id, (CASE WHEN tc.out_time is not NULL THEN (ROUND(TIMESTAMPDIFF(SECOND, tc.in_time, tc.out_time ) / 3600, 2)) ELSE 0 END) as totaltime FROM `user_time_clocks` tc WHERE DATE(tc.in_time) = '".$thisDate."' and tc.user_id = '".$user_id."' group by tc.in_time order by DATE(tc.in_time) desc")->fetchAll('assoc');

            foreach($timeclocks as $row){
                $usertimeclocks[] = $row;
            }
        }
        //print_r($usertimeclocks);exit;
        return $usertimeclocks;
    }

}