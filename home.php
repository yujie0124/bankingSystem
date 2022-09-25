<?php
    session_start();
    require_once('db_con.php');
    if (!$_SESSION['UserName']){
        header("Location: index.html");
    }

    $userid =  $_SESSION['UserId'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />

    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

    <link rel=" stylesheet" href="css/main.css?ts=<?=time()?>">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/topnav.css">
    <script type="text/javascript" src="js/functions.js"></script>

</head>

<body>
    <div class="topnav" id="myTopnav">
        
        <h1><i class="mdi mdi-bank"></i> BBS</h1>
        <a href="index.html">Logout</a>
        <a href="profile.php">Profile</a>
        <a href="home.php" class="active">Home</a>

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

    <main class="main">
        <br><br>
        <section>
            <div class="split2 left2">
                <div class="container3">
                    <h2>Welcome, <?php echo $_SESSION['UserName']; ?></h2>
                    <h4>Current Time : &nbsp; <span id="clock"></span></h4>

                    <script type="text/javascript">
                        var clockElement = document.getElementById('clock');

                        function clock() {
                            clockElement.textContent = new Date().toString();
                        }

                        setInterval(clock, 1000);

                    </script>
                </div>
            </div>

            <div class="split2 right2">
                <div class="container1">
                    <h3 style="margin-top:6%; margin-bottom: 6%; text-align: center;">Your Bank Accounts</h3>
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
                                if ($selectResult){
                                    $num = mysqli_num_rows( $selectResult );						
                                    echo "$num";
                                } elseif(mysqli_affected_rows ($db_con) == 0){
                                    echo '0';
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
                                if ($selectResult){
                                    $num = mysqli_num_rows( $selectResult );						
                                    echo "$num";
                                } elseif(mysqli_affected_rows ($db_con) == 0){
                                    echo '0';
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
                                if ($selectResult){
                                    $num = mysqli_num_rows( $selectResult );						
                                    echo "$num";
                                } elseif(mysqli_affected_rows ($db_con) == 0){
                                    echo '0';
                                } else{
                                    echo 'Error of retrieval of data';
                                }
                                ?>
                                </b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>

        <section>
            <div class="split1 left1">
                <div class="container1" style="width:950px;">
                    <div class="centered">
                        <h3 style="margin-top:6%; margin-bottom: 2%;">Accounts Details</h3>
                    </div>
                    <div class="divider1"></div>
                    <table class="accountdetails">

                        <?php
                            $selectSQL = "SELECT * FROM `bankacc` WHERE user_id ='$userid'";
                            $selectResult = mysqli_query($db_con, $selectSQL);

                            if( !$selectResult ){
                                echo 'Retrieval of data from Database Failed - #';
                            }

                            if( mysqli_num_rows( $selectResult )==0 ){
                                echo '<tr><td colspan="4">No Account</td></tr>';
                            }else{
                        ?>
                        <tr>
                            <th>No.</th>
                            <th>Account Type</th>
                            <th>Account Number</th>
                            <th>Account Balance</th>
                        </tr>
                        <?php
                            
                                $id;
                                $no = 1;
                                while( $row = mysqli_fetch_assoc( $selectResult ) ){
                                    $id = $row['acc_id'];
                                    echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['acc_type']}</td>
                                    <td>{$row['acc_number']}</td>
                                    <td>RM {$row['acc_balance']}</td>
                                    </tr>\n";
                                    $no++;
                                }
                            }
                        ?>

                    </table>
                    <p></p>
                </div>
            </div>


            <section>
                <div class="split3 center">
                    <div class="container1">
                        <h3 style="margin-top:35px; margin-bottom:35px; text-align: center;">Latest Transactions in This Month</h3>
                        <div class="divider1"></div>
                        <table class="latestTrans1">
                            <tr>
                                <th class="proth">Transaction Reference</th>
                                <th class="proth">Transferred From </th>
                                <th class="proth">Transferred To </th>
                                <th class="proth">Amount Transferred (RM)</th>
                                <th class="proth">Transferred Date (RM)</th>
                            </tr>
                    </div>
                    </table>
                    <div class="divider1"></div>
                    <table class="latestTrans2">
                        <?php 
                            $query = "SELECT * FROM `transaction` WHERE user_id = '$userid' AND MONTH(transaction_date) = MONTH(CURRENT_DATE()) ORDER by transaction_date DESC";
                            $result = mysqli_query($db_con, $query);
                             
                            while($row = mysqli_fetch_array($result))
                            {
                                echo "<tr>";
                                echo "<td class='protd' style='width:300px; padding-left:35px'>" . $row['transaction_ref'] . "</td>";
                                echo "<td class='protd' style='padding-left:20px; padding-right:15px'>" . $row['transaction_from'] . "</td>";
                                echo "<td class='protd' style='padding-right:25px'>" . $row['transaction_to'] . "</td>";
                                echo "<td class='protd' style='padding-left:15px; padding-right:15px'>RM " . $row['transaction_amount'] . "</td>";
                                echo "<td class='protd' style='padding-left:35px; text-align:center;'>" . $row['transaction_date'] . "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                    <br>
                </div>
                </div>
            </section>
        </section>
</body>

</html>
