<?php
  session_start();
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
    
        $checkBal = "SELECT * FROM bankacc WHERE acc_id ='$acc_id' AND user_id ='$userid' AND acc_type = 'Investment Account' ";
        $checkBalResult = mysqli_query($db_con, $checkBal);
        $row = mysqli_fetch_assoc($checkBalResult);
        
        $currentBal = $row['acc_balance'];
        $acc_number_from = $row['acc_number'];
    
        if($currentBal > 0){
            $increase = "UPDATE bankacc SET acc_balance = acc_balance +'$currentBal' WHERE acc_type = 'Saving Account' AND user_id = '$userid' AND acc_name = '$username'";
            $increasecheck=mysqli_query($db_con,$increase);
            if($increasecheck){
                    echo '<script> alert("Your saving account has received the balance transferred from this investment account successfully.") </script>';
            }

            $getReceivedNum = "SELECT * FROM bankacc WHERE user_id ='$userid' AND acc_type = 'Saving Account' ";
   
            $receivedAccNum = mysqli_query($db_con, $getReceivedNum);
            $toAccNum = mysqli_fetch_assoc($receivedAccNum);
            $acc_number_to = $toAccNum['acc_number'];

            $makeTrans ="INSERT INTO `transaction`(`transaction_number`, `transaction_from`, `transaction_to`, `transaction_amount`, `transaction_ref`, `transaction_date`, `transaction_status`, `user_id`) VALUES
                ((SELECT LPAD(FLOOR(RAND() * 9999999999.99), 10, '0')),'$acc_number_from','$acc_number_to','$currentBal','Transfer Money for closing Investment Account', (SELECT CURRENT_TIMESTAMP()), 'Successful', '$userid')";

            $makeTranschk= mysqli_query($db_con, $makeTrans);
            
    }
    
        $db_con->query("DELETE FROM bankacc WHERE acc_id = '$acc_id' AND user_id = '$userid' ")or die($db_con->error);   
        
        
        $_SESSION['delete']="This investment account has been deleted!";
        $_SESSION['msg_type']="danger";
        
        header("location:bankacc.php");
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		
		<title>Your Bank Acounts</title>

		<link
			rel="stylesheet"
			href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css"
		/>

        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="css/sidebar.css" />
        <link rel="stylesheet" href="css/topnav.css" />
        <link rel="stylesheet" href="css/bankacc.css?ts=<?=time()?>" />
        <script type="text/javascript" src="js/functions.js"></script>
        
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
							<span class="sidebar__nav__text">Add new bank account</span>
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
        
		<main class="main" style="margin-left: 250px; width:90%">
            <br><br>
			<div class="container">
        <?php
            if (isset($_SESSION['delete'])):?>
            <div style="float: center; color:black;">
             <?php
             echo '<div style="
             background-color: red;
             color: white;
             border: none;
             border-style: outset;
             border-radius: 8px;
             text-align: center;
             padding: 10px 16px;
             height: 40px;
             margin: auto;
             width: 160%;
             font-size: 22px;">';
             echo $_SESSION['delete'];
             unset($_SESSION['delete']);
             echo '</div>';
             ?>
            </div>
        <?php endif; ?>
                
                
        <h2 style="margin-top:20px;">Existing Bank Accounts</h2>
        
        <?php
          
          $result = mysqli_query($db_con, "SELECT * FROM bankacc WHERE user_id = '$userid'");
                
          ?>
            <table class="product-table">
                <thead>
                     <tr>
                         <th colspan="2">No</th>
                         <th colspan="2">Account Name</th>
                         <th colspan="2">Account Number</th>
                         <th colspan="2">Type of Account</th>
                         <th colspan="2">Bank of Account</th>
                         <th colspan="2">Account's Balance</th>
                         
                         <th colspan="2" style="text-align:center;">Action</th>
                     </tr>
                </thead>
                <tbody>
            <?php 
                $id = 1;
                while($row = $result->fetch_assoc()):?>
                <tr>
                    <td colspan="2"><?php echo $id ?></td>
                    <td colspan="2"><?php echo $row['acc_name']; ?></td>
                    <td colspan="2"><?php echo $row['acc_number']; ?></td>
                    <td colspan="2"><?php echo $row['acc_type']; ?></td>
                    <td colspan="2"><?php echo $row['acc_bank']; ?></td>
                    <td colspan="2">RM <?php echo $row['acc_balance']; ?></td>
                    
                    <?php if($row['acc_type'] == 'Investment Account'){?>

                    <td style="text-align:center;">
                        <button class = "delete_button" ><a class="delete" onclick="return confirm('Are you sure you want to delete this Account? If yes, the available balance in this Investment Account will be transferred to your Saving Account.')" 
                        href="bankacc.php?delete=<?php echo $row['acc_id']; ?>" class="btn btn-danger ">Delete</a></button>               
                     </td>
                     <?php }else{echo "<td></td>";}?>
            <?php $id++; endwhile;  ?>
                    </tr>
                </tbody>
            </table>
         <?php     
          function pre_r($array){
              echo'<pre>';
              print_r($array);
              echo'</pre>';}
         ?>
          </div>            
            <div>
              </div>   
		</main>
	</body>
</html>