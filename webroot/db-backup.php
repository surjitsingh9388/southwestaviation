<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Enter your database information here and the name of the backup file
$mysqlDatabaseName ='i4642462_report';
$mysqlUserName ='i4642462_report';
$mysqlPassword ='lLwbuU(=TD-A';
$mysqlHostName ='localhost';
$mysqlExportPath ='aircraft-backup-'.time().'.sql';

//Export of the database and output of the status
//$command='mysqldump --user='.$mysqlUserName.' --password='.$mysqlPassword.' --host='.$mysqlHostName.' '.$mysqlDatabaseName.' > '.$mysqlExportPath;

//exec('mysqldump --user=i4642462_report --password=lLwbuU(=TD-A --host=localhost i4642462_report > aircraft.sql');

$command='mysqldump --opt -h ' .$mysqlHostName .' -u ' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' > ' .$mysqlExportPath;

$output=null;
$worked=null;
exec($command,$output,$worked);
//system($command,$output,$worked);

echo "Returned with status $worked and output:\n";
echo "<pre>";
print_r($output);
echo "</pre>";die;

/*
switch($worked){
	case 0:
		echo 'The database <b>' .$mysqlDatabaseName .'</b> was successfully stored in the following path '.getcwd().'/' .$mysqlExportPath .'</b>';
		break;
	case 1:
		echo 'An error occurred when exporting <b>' .$mysqlDatabaseName .'</b> '.getcwd().'/' .$mysqlExportPath .'</b>';
		break;
	case 2:
		echo 'An export error has occurred, please check the following information: <br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
		break;
}
*/


?>