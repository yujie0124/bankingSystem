<?php

require_once('db_con.php');

if (!$_SESSION['UserName']){
    header("Location: index.html");
}
$userid = $_SESSION['UserId'];
$username = $_SESSION['UserName'];

$update = false;
$acc_id = '';

$errors = array('acc_id' => '');


if(isset($_GET['delete'])){
    $acc_id = $_GET['delete'];

    $checkBal = "SELECT * FROM bankacc WHERE acc_id ='$acc_id' AND user_id ='$userid' AND acc_type = 'Investment Account'";
    $checkBalResult = mysqli_query($db_con, $checkBal);
	$row = mysqli_fetch_assoc($checkBalResult);
    echo '<script> alert("deleteAcc_process.php file here") </script>';
	$currentBal = $row['acc_balance'];

    if($currentBal > 0){
        $increase = "UPDATE bankacc SET acc_balance = acc_balance +'$currentBal' WHERE acc_type = 'Saving Account' AND user_id = '$userid' AND acc_name = '$username'";
        $increasecheck=mysqli_query($db_con,$increase);
        if($increasecheck){
                echo '<script> alert("Your saving account has received the balance transferred from this investment account successfully.") </script>';
        }
    }

    $db_con->query("DELETE FROM bankacc WHERE acc_id = '$acc_id' AND user_id = '$userid' ")or die($db_con->error);   
    
    
    $_SESSION['delete']="This investment account has been deleted!";
    $_SESSION['msg_type']="danger";
    
    header("location:bankacc.php");
}

?>

                                                                                                                                                                  
