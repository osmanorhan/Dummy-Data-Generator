<?php
require_once 'Dummy.class.php';

if($_GET['step'] == 1){
$db_table = htmlentities($_POST['db_tablename']);
$db_server = htmlentities($_POST['db_server']);
$db_user = htmlentities($_POST['db_user']);
$db_pass = htmlentities($_POST['db_pass']);
$db_name = htmlentities($_POST['db_name']);
$field_count = htmlentities($_POST['field_count']);

if($db_name && $db_server  && $db_table && $field_count && $field_count > 1 ){

try {
    $dummy = new Dummy($db_table);
 	$dummy->connect($db_server,$db_user,$db_pass,$db_name);
} catch (Exception $e) {
    echo 'We have a problem!: ',  $e->getMessage();
}



echo "Your table like this:<br><table><tr><th>Field Name</th><th>Type</th><th>Lenght</th></tr>";
foreach($dummy->get_table_info() as $info){
	echo "<tr><td>".$info[0]."</td><td>".$info[1]."</td><td>".$info[2]."</td></tr>";
	
}
	echo "</table>Generating...";

	$dummy->insert_data($field_count);
	sleep(2);
	echo "<br> Finished!";
}else{
	
	echo "Plase fill all box!";
}	
	
}
