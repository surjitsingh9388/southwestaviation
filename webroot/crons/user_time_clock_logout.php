<?php

namespace App\Controller;

require dirname(__DIR__) . '/../vendor/autoload.php';

use App\Controller\AppController;
//use App\Controller\Admin\UserTimeClocksController; 
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

if (!defined('DS')) {
  define('DS', DIRECTORY_SEPARATOR);
} 

require_once(dirname(dirname(__FILE__)).DS.'../config'.DS.'bootstrap.php');
//require_once(dirname(dirname(__FILE__)).DS.'../src'.DS.'Controller'.DS.'AppController.php');
 
//app = new AppController();

//$UserTimeClocksController = new UserTimeClocksController();

$connection = ConnectionManager::get('default');

$yesterday_date = date('Y-m-d', strtotime('-1 days'));

$usertimeclocks = $connection->execute(
  "SELECT * FROM `user_time_clocks` WHERE DATE(in_time) = '".$yesterday_date."' and out_time is NULL",
  ['created' => 'datetime'])->fetchAll('assoc');

if(!empty($usertimeclocks)){
	$currentdate = date('Y-m-d H:i:s');
  	foreach($usertimeclocks as $clocklog){
    		$out_time = $yesterday_date.' 23:59:59';
    		$connection->execute("update user_time_clocks set out_time = '".$out_time."', updated_at = '".$currentdate."' where id='".$clocklog['id']."'");
  	}
}
?>
