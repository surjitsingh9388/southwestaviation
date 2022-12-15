<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbHost = 'localhost';
$dbUsername = 'i4642462_report';
$dbPassword = 'lLwbuU(=TD-A';
$dbName = 'i4642462_report';

//connect & select the database
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 

//get all of the tables
$tables = array();
$result = $db->query("SHOW TABLES");
while($row = $result->fetch_row()){
    $tables[] = $row[0];
}

//echo "<pre>";
//print_r($tables);
//echo "</pre>";die;

//loop through the tables
$return = '';
foreach($tables as $table) {
    $result = $db->query("SELECT * FROM $table");
    $numColumns = $result->field_count;

    $return .= "DROP TABLE $table;";

    $result2 = $db->query("SHOW CREATE TABLE $table");
    $row2 = $result2->fetch_row();

    $return .= "\n\n".$row2[1].";\n\n";

    for($i = 0; $i < $numColumns; $i++){
        while($row = $result->fetch_row()){
            $return .= "INSERT INTO $table VALUES(";
            for($j=0; $j < $numColumns; $j++){
                $row[$j] = addslashes($row[$j]);
                //$row[$j] = ereg_replace("\n","\\n",$row[$j]);
				$row[$j] = preg_replace("[^A-Za-z0-9]","",$row[$j]);
                if (isset($row[$j])) { $return .= '"'.$row[$j].'"' ; } else { $return .= '""'; }
                if ($j < ($numColumns-1)) { $return.= ','; }
            }
            $return .= ");\n";
        }
    }

    $return .= "\n\n\n";
}

//save file
$handle = fopen('db-backup-'.time().'.sql','w+');
fwrite($handle,$return);
fclose($handle);
