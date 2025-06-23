<?php

namespace App\Controller;

require dirname(__DIR__) . '/../vendor/autoload.php';

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

if (!defined('DS')) {
  define('DS', DIRECTORY_SEPARATOR);
} 

require_once(dirname(dirname(__FILE__)).DS.'../config'.DS.'bootstrap.php');

$connection = ConnectionManager::get('default');

$todaydate = date("Y-m-d");
$currentdays = date('l');
$currentdate = date('Y-m-d H:i:s');

$userlist = $connection->execute(
    "SELECT id, first_paycheck_date, pto_accrual_rate FROM `users` where first_paycheck_date <= '".$todaydate."'",
    ['created' => 'datetime'])->fetchAll('assoc');

if(!empty($userlist)){
    foreach($userlist as $users){
        $first_paycheck_date = $users['first_paycheck_date'];
        $pto_accrual_rate = $users['pto_accrual_rate'];

        $pto_accrual_rate_value = 0;
        if($pto_accrual_rate == '1'){
            $pto_accrual_rate_value = '3.08';
        }else if($pto_accrual_rate == '2'){
            $pto_accrual_rate_value = '4.62';
        }else if($pto_accrual_rate == '3'){
            $pto_accrual_rate_value = '6.15';
        }else if($pto_accrual_rate == '4'){
            $pto_accrual_rate_value = '7.69';
        }

        $userptodata = $connection->execute("select * from user_pto_requests where user_id='".$users['id']."' order by id desc limit 1")->fetch('assoc');
        
        $ptocheckdata = $connection->execute("select * from user_pto_requests where user_id='".$users['id']."' and is_first_paycheck = '1' order by id desc limit 1")->fetch('assoc');

        if(empty($ptocheckdata)){
            $nextdate = date('Y-m-d', strtotime("+2 week", strtotime($first_paycheck_date)));
        }else{
            $nextdate = date('Y-m-d', strtotime("+2 week", strtotime($ptocheckdata['created_at'])));
        }
        
        if($todaydate == $nextdate){
            $previous_balance = $userptodata['new_balance'];
            $hours_used_gained = $pto_accrual_rate_value;
            $new_balance = $previous_balance+$hours_used_gained;
            $is_pto_add = '1';
            
            $connection->execute("insert into user_pto_requests set user_id = '".$users['id']."', previous_balance = '".$previous_balance."', hours_used_gained = '".$hours_used_gained."', new_balance = '".$new_balance."', pto_requests_status = '2', is_first_paycheck = '1', is_pto_add = '".$is_pto_add."', added_by = '".$users['id']."', created_at = '".$currentdate."'");
            break;
        }
    }
}
?>