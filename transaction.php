<?php
  session_start();
  require_once('db_con.php');

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

if(isset($_POST["DELETEC"]))
{

                $id = mysqli_real_escape_string($db_con, $_REQUEST['id']);
                $name = mysqli_real_escape_string($db_con, $_REQUEST['name']);
                
                $sql = "DELETE FROM `order` WHERE order_id = '$id' AND product_id = '$name'";
                $result = mysqli_query($db_con, $sql);
                
                if(mysqli_affected_rows ($db_con) == 0) {
                    echo '<script>alert("No order is deleted.")</script>';
                }
                elseif(mysqli_query($db_con, $sql)){
                    echo '<script>alert("The order is deleted successfully.")</script>';
                       
                } 
                else{
                    echo '<script>alert("There is error to perform your request. Please check if your entered information (Order ID and Product ID) are valid and try again.")</script>';
                }

}


  ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		
		<title>Transaction History</title>

		<link
			rel="stylesheet"
			href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css"
		/>

        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="css/sidebar.css" />
        <link rel="stylesheet" href="css/topnav.css" />
        <link rel="stylesheet" href="css/transaction.css" />
        <script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript">

        </script>

        <style>
        * {
            box-sizing: border-box;
        }

        .addPopup2 {
            position: relative;
			margin:10px;
            text-align: center;
            width: 100%;
        }      
        
        .formPopup {
			margin:10px;
			padding:20px;
            color: black;
            overflow: auto;
            font-weight: 500;
            display: none;
            position: fixed;
            left: 55%;
            top: 18%;
			bottom:10%;
            transform: translate(-50%, 5%);
            border: 4px solid whitesmoke;
            z-index: 9;
            background-color: #f3edf7;
        }
        
        .formContainer {
            max-height: 530px;
            max-width: 450px;
            padding: 20px;
        }
        
        .formContainer input[type=text],
		.formContainer select {
            width: 100%;
            padding: 15px;
            margin: 5px 0 20px 0;
            border: none;
            background: #fef4f6;
			
        }
        
        .formContainer input[type=text],
		select :focus {
            background-color: #fef4f6;
            outline: none;
        }
        
        .formContainer .btn {
            padding: 12px 20px;
            border: none;
            background-color: #8ebf42;
            color: #fff;
            cursor: pointer;
            width: 100%;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        
        .formContainer .cancel {
            background-color: #cc0000;
        }
        
        .formContainer .btn:hover {
            opacity: 2;
        }
        </style>
        
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
            
            <h2 style="margin-top:50px;"> Transaction History</h2>
            <button class="btn btn-info" onclick="document.location='addTrans.php'">Transfer Money</button>

            <p>
				<?php
                        $selectSQL = "SELECT * FROM `transaction` ORDER BY transaction_date DESC";
                        $selectResult = mysqli_query($db_con, $selectSQL);

                        if( !$selectResult ){
                            echo 'Retrieval of data from Database Failed - #';
                          }else{

                ?>
                        <table class="table order-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Transaction Date</th>
                                    <th>Transaction Number</th>
                                    <th>Transferred From Account</th>
                                    <th>Transferred To Account</th>
									<th>Amount Transferred</th>
                                    <th>Reference</th>
									<th>Transaction Status</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                            <?php

                                if( mysqli_num_rows( $selectResult )==0 ){
                                        echo '<tr><td colspan="4">No Transaction Made</td></tr>';
                                }
                                else{
                                    $id;
                                    $no = 1;
                                    while( $row = mysqli_fetch_assoc( $selectResult ) ){
                                        $id = $row['transaction_id'];
                                        echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['transaction_date']}</td>
                                        <td>{$row['transaction_number']}</td>
                                        <td>{$row['transaction_from']}</td>
										<td>{$row['transaction_to']}</td>
										<td>RM {$row['transaction_amount']}</td>
                                        <td>{$row['transaction_ref']}</td>
										<td>{$row['transaction_status']}</td>
                                        
                                        </tr>\n";
                                        $no++;
                                    }
                                }
                            
                            ?>
                            </tbody>
                        </table>
                        <?php
                    }

                    ?>
   
		</main>
	</body>
</html>