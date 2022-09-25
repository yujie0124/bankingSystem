<?php
  session_start();
  require_once('db_con.php');

if(!isset($_SESSION["acc_id_to"]) && !isset($_SESSION["acc_id_from"])){
    $_SESSION["acc_id_from"] = "";
    $_SESSION["acc_id_to"] = "";
    $_SESSION["acc_number_from"] = "";
    $_SESSION["acc_number_to"] = "";
    $_SESSION["acc_balance"] = "";
    $_SESSION["acc_bank_from"] = "";
    $_SESSION["acc_bank_to"] = "";
    $_SESSION["acc_type_from"] = "";
    $_SESSION["acc_type_to"] = "";
    $_SESSION["transfer_amount"] = "";

}

$userid = $_SESSION['UserId'];
$errors = array('acc_name' => '', 'acc_type' => '', 'acc_number_from' => '','transfer_amount' => '');

if(isset($_POST['search_from'])){
    $search = $_POST['acc_number_from'];
    
    $sql = "SELECT * FROM bankacc WHERE user_id ='$userid' AND acc_number = '".$search."' ";
   
    $result = mysqli_query($db_con, $sql);
    if($result){
        $row = mysqli_fetch_assoc($result);
    }else{
        echo ("Account Number entered does not exist please enter again.");}

    $_SESSION["acc_number_from"] = $search;
    $_SESSION["acc_id_from"] = $row['acc_id'];
    $_SESSION["acc_bank_from"] = $row['acc_bank'];
    $_SESSION["acc_type_from"] = $row['acc_type'];
    $_SESSION["acc_balance"] = $row['acc_balance'];

    header("location: addTrans.php");
}

if(isset($_POST['search_to'])){
    $search_to = $_POST['acc_number_to'];
    
    $sql = "SELECT * FROM bankacc WHERE user_id ='$userid' AND acc_number = '".$search_to."' ";
   
    $result = mysqli_query($db_con, $sql);
    if($result){
        $row = mysqli_fetch_assoc($result);
    }else{
        echo ("Account Number entered does not exist please enter again.");}
    
    $_SESSION["acc_id_to"] = $row['acc_id'];
    $_SESSION["acc_number_to"] = $search_to;
    $_SESSION["acc_bank_to"] = $row['acc_bank'];
    $_SESSION["acc_type_to"] = $row['acc_type'];
    
    echo '<script> alert(" . $_SESSION["acc_number_to"]. ")</script>';

    header("location: addTrans.php");
}

 
if(isset($_POST['add'])){

    if (!empty($_POST["acc_number_from"])&& !empty($_POST["acc_number_to"]) && !empty($_POST["transfer_amount"]) && !empty($_POST["reference"]) 
        && !empty($_POST["user_id"])){

            $acc_number_from = mysqli_real_escape_string($db_con, $_REQUEST['acc_number_from']);
			$acc_number_to = mysqli_real_escape_string($db_con, $_REQUEST['acc_number_to']);
            $transfer_amount = mysqli_real_escape_string($db_con, $_REQUEST['transfer_amount']);
            $reference = mysqli_real_escape_string($db_con, $_REQUEST['reference']);
			$user_id = mysqli_real_escape_string($db_con, $_REQUEST['user_id']);
			$acc_id_from = $_SESSION["acc_id_from"];
            $acc_id_to = $_SESSION["acc_id_to"];

        
        // check if both acc are same
        if($acc_number_from == $acc_number_to){
            echo '<script>alert("Invalid transaction due to the same account numbers for both accounts.")</script>';
            header("Refresh:0");
        }
        // check if enough balance to transfer
        elseif($_SESSION["acc_balance"] < $transfer_amount){
            echo '<script> alert("Invalid transaction due to not enough balance in the account you have entered.") </script>';
            header("Refresh:0");
        }
        else{
            $sql ="INSERT INTO `transaction`(`transaction_number`, `transaction_from`, `transaction_to`, `transaction_amount`, `transaction_ref`, `transaction_date`, `transaction_status`, `user_id`) VALUES
                ((SELECT LPAD(FLOOR(RAND() * 9999999999.99), 10, '0')),'$acc_number_from','$acc_number_to','$transfer_amount','$reference', (SELECT CURRENT_TIMESTAMP()), 'Successful', '$user_id')";
   
            if(mysqli_query($db_con, $sql)){
                // update account balance
                
                    $deduct = "UPDATE bankacc SET acc_balance = acc_balance -'$transfer_amount' WHERE acc_number='$acc_number_from' AND acc_id='$acc_id_from'";
                    $deductcheck=mysqli_query($db_con,$deduct);

                    if($deductcheck){
                            echo '<script> alert("Your balance in the account has been deducted successfully.") </script>';
                    }

                    $increase = "UPDATE bankacc SET acc_balance = acc_balance +'$transfer_amount' WHERE acc_number='$acc_number_to' AND acc_id='$acc_id_to'";
                    $increasecheck=mysqli_query($db_con,$increase);
                    if($increasecheck){
                            echo '<script> alert("Your account has received the fund transferred successfully.") </script>';
                    }
                     
            }
            echo '<script> alert("Transaction has been done successfully.") </script>';
            header("location:transaction.php");
        }

    } else{echo '<script>alert("Please ensure that all fields are filled.")</script>';}
    
    
    $_SESSION["acc_id_from"] = "";
    $_SESSION["acc_id_to"] = "";
    $_SESSION["acc_number_from"] = "";
    $_SESSION["acc_number_to"] = "";
    $_SESSION["acc_balance"] = "";
    $_SESSION["acc_bank_from"] = "";
    $_SESSION["acc_bank_to"] = "";
    $_SESSION["acc_type_from"] = "";
    $_SESSION["acc_type_to"] = "";
    $_SESSION["transfer_amount"] = "";
    
}

?>
  


<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		
		<title>Add New Transaction</title>

		<link
			rel="stylesheet"
			href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css"
		/>

        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="css/sidebar.css" />
        <link rel="stylesheet" href="css/topnav.css" />
        <link rel="stylesheet" href="css/transaction.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="js/functions.js"></script>
        
	</head>
	<body>
    <div class="topnav" id="myTopnav">
    <h1><i class="mdi mdi-bank"></i> BBS</h1>
        <a href="login.php">Logout</a>
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
             
			<h2 style="margin-top:50px;">New Transaction</h2>
            <button class="btn btn-danger" onclick="document.location='transaction.php'" style="margin-bottom: 30px;">Cancel</button> 

                <div class="container1">
                    <h3 style="margin-top:2%; margin-bottom: 2%; text-align: center;">Your Bank Accounts</h3>
                    <div class="divider1"></div>
                    <table class="boxdetails">
                        <tr>
                            <td>
                                <p>Saving Accouns</p>
                            </td>
                            <td><b>
                                    <?php 
                                $query = "SELECT * FROM bankacc WHERE user_id ='$userid' AND acc_type = 'Saving Account'";
                                $selectResult = mysqli_query($db_con, $query);
                                if ($row = mysqli_fetch_assoc( $selectResult )){	
                                    $num = $row['acc_number'];
                                    echo $num;
                                } elseif(mysqli_affected_rows ($db_con) == 0){
                                    echo '-';
                                } else{
                                    echo 'Error of retrieval of data';
                                }
                                ?>
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Goal Accounts</p>
                            </td>
                            <td><b>
                                    <?php 
                                $query = "SELECT * FROM bankacc WHERE user_id ='$userid' AND acc_type = 'Goal Account'";
                                $selectResult = mysqli_query($db_con, $query);
                                if ($row = mysqli_fetch_assoc( $selectResult )){	
                                    $num = $row['acc_number'];
                                    echo $num;
                                } elseif(mysqli_affected_rows ($db_con) == 0){
                                    echo '-';
                                } else{
                                    echo 'Error of retrieval of data';
                                }
                                ?>
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Investment Accounts</p>
                            </td>
                            <td><b>
                                    <?php 
                                $query = "SELECT * FROM bankacc WHERE user_id ='$userid' AND acc_type = 'Investment Account'";
                                $selectResult = mysqli_query($db_con, $query);
                                if ($row = mysqli_fetch_assoc( $selectResult )){	
                                    $num = $row['acc_number'];
                                    echo $num;
                                } elseif(mysqli_affected_rows ($db_con) == 0){
                                    echo '-';
                                } else{
                                    echo 'Error of retrieval of data';
                                }
                                ?>
                                </b>
                            </td>
                        </tr>
                    </table>
                </div>

            <form  class="orderForm"  method="POST" style="width:autopx" action="addTrans.php">
               
            <table>
                <tr>
                    <td>From Account Number:</td>
                    <td>
                        <input type="text" id="acc_number_from" style=" width: 230px;" name="acc_number_from" placeholder="Transfer FROM this account number" value="<?php echo $_SESSION["acc_number_from"];?>">
                        
                            <button type="submit" class="btn btn-success" name="search_from">
                                    <i class="fa fa-check icon"></i>
                            </button>
                  
                    </td>

                </tr>
                <tr>
                    <td>From Bank:</td>
                    <td><input type="text" id="acc_bank_from" maxlength="265"  placeholder="Display bank name" style=" width: auto;" value="<?php echo $_SESSION["acc_bank_from"]?>" readonly></td>

                    <td>Account type:</td>
                    <td><input type="text" id="acc_type_from" maxlength="265"  placeholder="Account type" style=" width: auto;"value="<?php echo $_SESSION["acc_type_from"]?>" readonly></td>
                    
                </tr>
                <tr>
                    <td>Available Balance:</td>
                    <td><input type="text" id="acc_balance" maxlength="265"  placeholder="Available Balance" style=" width: auto;" value="RM <?php echo $_SESSION["acc_balance"]?>" readonly></td>

                </tr>

                <tr>
                    <td>To Account Number:</td>
                    <td>
                        <input type="text" id="acc_number_to" style=" width: 230px;" name="acc_number_to" placeholder="Transfer TO this account number" value="<?php echo $_SESSION["acc_number_to"];?>">
                    
                                    <button type="submit" class="btn btn-success" name="search_to">
                                        <i class="fa fa-check icon"></i>
                                    </button>
                    </td>

                                
                </tr>
                <td>From Bank:</td>
                    <td><input type="text" id="acc_bank_to" maxlength="265"  placeholder="Display bank name" style=" width: auto;"value="<?php echo $_SESSION["acc_bank_to"]?>" readonly></td>

                    <td>Account type:</td>
                    <td><input type="text" id="acc_type_to" maxlength="265"  placeholder="Account type" style=" width: auto;"value="<?php echo $_SESSION["acc_type_to"]?>" readonly></td>
                    
                </tr>
                
                <tr>
                    <td>User ID:</td>
                    <td>
                         <input type="number" id="user_id" name="user_id" min="1" value="<?php echo $_SESSION["UserId"]; ?>" readonly>
                        
                    </td>
                        <td>User Name:</td>
                        <td><input type="text" id="user_name" maxlength="265"  style=" width: auto;" value="<?php echo $_SESSION["UserName"]?>" readonly></td>
                    
                </tr>
                <tr>
                    <td>Reference:</td>
                    <td><input type="text" name="reference" id="reference" placeholder="Transaction's reference" style=" width: auto;" ></td>
                </tr>
                
                <tr>
                    <td>Amount to transfer (RM):</td>
                    <td><input type="number" name="transfer_amount" id="transfer_amount" min="1" value="<?php echo $_SESSION["transfer_amount"]; ?>" style=" width: auto;" ></td>
                    
                </tr>
            </table> 
            <br>
            <p style="text-align:center;">
                 <button type="submit" name="add" id="add" class="btn btn-submit" value="">Confirm Transfer</button>
            </p>
            </form>
	</main>
	</body>
</html>