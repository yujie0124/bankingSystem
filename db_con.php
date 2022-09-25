<?php 

define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', 'bank_system');



$db_con = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);



// Check connection
/*if($db_con){
    echo("Connection Successfull");
}*/
if($db_con === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>