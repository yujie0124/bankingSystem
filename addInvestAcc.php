<?php

  session_start();
  require_once('db_con.php');
  if (!$_SESSION['UserName']){
	header("Location: index.html");
}
    $userid =  $_SESSION['UserId'];
	$username = $_SESSION['UserName'];

	$acc_name = $acc_type = $acc_number_from = $transfer_amount = '';
	$errors = array('acc_name' => '', 'acc_type' => '', 'acc_number_from' => '','transfer_amount' => '');



if(isset($_POST['submit'])){
		
		$transfer_amount = $_POST['transfer_amount'];
		// check acc_number_from has enough balance to transfer or not
		if(!empty($_POST['transfer_amount'])){
				// check amount input
				if(!preg_match('/^[0-9]+$/', $transfer_amount)){
						$errors['transfer_amount'] = 'Must be valid amount only';
				}

			$acc_number_from = mysqli_real_escape_string($db_con, $_POST['acc_number_from']);
			$checkBal = "SELECT acc_balance FROM bankacc WHERE acc_number ='$acc_number_from' AND user_id ='$userid'";
            $checkBalResult = mysqli_query($db_con, $checkBal);
			$row = mysqli_fetch_assoc($checkBalResult);

			$currentBal = $row['acc_balance'];
			if($currentBal < $transfer_amount){
				$errors['transfer_amount'] = 'The selected account has not enough balance to transfer this amount of money.';

			}
		}


  

		if(array_filter($errors)){
			//echo 'errors in form';
		} else {
			// escape sql chars
			
			$acc_name = mysqli_real_escape_string($db_con, $_POST['acc_name']);
			$acc_type = mysqli_real_escape_string($db_con, $_POST['acc_type']);
            $acc_balance = mysqli_real_escape_string($db_con, $_POST['transfer_amount']);
			$acc_number_from = mysqli_real_escape_string($db_con, $_POST['acc_number_from']);

			$getAccID = "SELECT acc_id from bankacc WHERE acc_number='$acc_number_from' AND user_id='$userid'";
			$selectResult = mysqli_query($db_con, $getAccID);
			$row = mysqli_fetch_assoc( $selectResult );
			$acc_id_from = $row['acc_id'];
            
			// create sql
			$sql = "INSERT INTO bankacc (acc_number, acc_name, acc_type, acc_balance, acc_created_date,	 user_id)
					VALUES((SELECT LPAD(FLOOR(RAND() * 9999999999.99), 10, '1')),'$acc_name','$acc_type','$acc_balance',(SELECT CURRENT_TIMESTAMP()), '$userid')";

			// save to db and check
			if(mysqli_query($db_con, $sql) && !empty($acc_balance)){
				// success
				$deduct = "UPDATE bankacc SET acc_balance = acc_balance -'$transfer_amount' WHERE acc_number='$acc_number_from' AND acc_id='$acc_id_from'";
                $deductcheck=mysqli_query($db_con,$deduct);

                if($deductcheck){
                            echo '<script> alert("Your balance in the account has been deducted successfully.") </script>';
                }

				$getNewAccNum = "SELECT acc_number from bankacc WHERE acc_type ='Investment Account' AND user_id='$userid' ORDER BY acc_created_date DESC LIMIT 1";
				$newAccNumcheck=mysqli_query($db_con,$getNewAccNum);
				$invNum = mysqli_fetch_assoc( $newAccNumcheck );
				$acc_number_to = $invNum['acc_number'];

				$makeTrans = "INSERT INTO `transaction`(`transaction_number`, `transaction_from`, `transaction_to`, `transaction_amount`, `transaction_ref`, `transaction_date`, `transaction_status`, `user_id`) VALUES
                ((SELECT LPAD(FLOOR(RAND() * 9999999999.99), 10, '0')),'$acc_number_from','$acc_number_to','$transfer_amount','Transfer to Investment Account', (SELECT CURRENT_TIMESTAMP()), 'Successful', '$userid')";
				if(mysqli_query($db_con, $makeTrans)){
					echo '<script> alert("New transaction history created.") </script>';
				}

				header('Location: bankacc.php');
			} else {
				echo 'query error: '. mysqli_error($db_con);
			}
            header('Location: bankacc.php'); 
			
		}

} // end POST check

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		
		<title>Create New Investment Account</title>
		<link
			rel="stylesheet"
			href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css"
		/>

        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="css/sidebar.css" />
        <link rel="stylesheet" href="css/topnav.css" />
        <link rel="stylesheet" href="css/bankacc.css?ts=<?=time()?>" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="js/functions.js"></script>
        
		<style>
			.table-design, 
			.table-design th, 
			.table-design td {
  				border: 1px solid black;
  				border-collapse:collapse;
				padding: 5px;
			}
		</style>
        
	</head>
	<body>
    <div class="topnav" id="myTopnav">
	<h1><i class="mdi mdi-bank"></i> BBS</h1>
        
        <a href="index.html">Logout</a>
        <a href="profile.php">Profile</a>
        <a href="home.php">Home</a>

        
    </div>


	<aside class="sidebar">
			<nav>
				<ul class="sidebar__nav">
					<li>
						<a href="home.php" class="sidebar__nav__link">
							<i class="mdi mdi-home"></i>
							<span class="sidebar__nav__text">Dashboard</span>
						</a>
					</li>
					<li>
						<a href="bankacc.php" class="sidebar__nav__link">
							<i class="mdi mdi-coin"></i>
							<span class="sidebar__nav__text">Bank Accounts</span>
						</a>
                        <ul style="list-style: none;">
                        <li>
                            <a href="addInvestAcc.php" class="sidebar__nav__link">
                            <i class="mdi mdi-bookmark-plus" style="font-size:27px;"></i>
							<span class="sidebar__nav__text" >Add new bank account</span>
						</a>
                        </li>
                        
                        </ul>
					</li>
					<li>
						<a href="transaction.php" class="sidebar__nav__link">
							<i class="mdi mdi-transfer"></i>
							<span class="sidebar__nav__text">Transaction</span>
						</a>
                        
					</li>
					<li>
						<a href="profile.php" class="sidebar__nav__link">
							<i class="mdi mdi-account-circle"></i>
							<span class="sidebar__nav__text">Your Profile</span>
						</a>
					</li>
					
					<li>
						<a href="contact.php" class="sidebar__nav__link">
							<i class="mdi mdi-contacts"></i>
							<span class="sidebar__nav__text">Contact Us</span>
						</a>
					</li>
				</ul>
			</nav>
	</aside>

	<main class="main" style="margin-left:250px;">

	<section class="container grey-text" style="width:100%;">
	<div style="float:center; margin-right:50px;">
		<h3 class="center">Create an Investment Account</h3>
		<form class="addproduct" action="addInvestAcc.php" method="POST" style="margin-left:120px;">
            <table>
            <tr><td><label>Account Holder Name :</label></td><td>
			<input type="text" style="font-style:italic; font-size:medium;" name="acc_name" id="acc_name" value="<?php echo $_SESSION['UserName'] ?>" readonly></td>
            </tr>
			<tr><td><label>Type of Account :</label></td><td>
			<input type="text" style="font-style:italic; font-size:medium;" name="acc_type" id="acc_type" value="<?php echo 'Investment Account' ?>"></td>
			</tr>
			<tr><td><label>Amount to transfer/to deposit (OPTIONAL) : </label></td><td>
			<input type="number" placeholder="Amount to transfer in" name="transfer_amount" value="RM <?php echo $transfer_amount ?>">
			<div class="red-text" style="color:red;"><?php echo $errors['transfer_amount']; ?></div></td></tr>
            
			<tr><td></td></tr>

			<tr><td><label for="acc_number_from">Transfer Money From Existing Accounts: </label></td><td>
                        <select name="acc_number_from" value="<?php echo '$acc_number_from' ?>" style=" margin: 10px; background-color: lavender ; height: 30px; width: 300px; font-style:italic;" >
                            <option>Select Account</option>
							
							<?php 
								$selectSQL = "SELECT acc_number, acc_type FROM `bankacc` WHERE user_id ='$userid'";
								$selectResult = mysqli_query($db_con, $selectSQL);
	
								if( !$selectResult ){
									echo 'Retrieval of data from Database Failed - #';
								}
								while($row = $selectResult->fetch_assoc()){
									$option =$row['acc_number'];
									$type = $row['acc_type'];
							?>
							<?php
    							//selected option
    							if(!empty($acc_number_from) && $acc_number_from== $option){
    							// selected option
    						?>
    							<option value="<?php echo $option; ?>" selected><?php echo $option; echo " - "; echo $type; ?> </option>
    						<?php 
								continue;
   							}?>
    						<option value="<?php echo $option; ?>" ><?php echo $option; echo " - "; echo $type; ?> </option>
   							<?php
								}
    						?>
							
						</select>

						</td></tr>
						
			
			
			
            </table>
			<div class="center">
				<input type="submit" name="submit" value="Submit">
			</div>
		</form>
	</div>

	
	</section>
  </main>

<script>
function addMoney() {
  var checkBox = document.getElementById("add_money").checked;
  var text = document.getElementById("addFrom");
  if (checkBox == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script>

</body>
</html>