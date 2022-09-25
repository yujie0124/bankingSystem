<?php
  session_start();
  require_once('db_con.php');
  if (!$_SESSION['UserName']){
    header("Location: index.html");
}

 $name = $_SESSION['UserName'];
 $userid =  $_SESSION['UserId'];

 $query = "SELECT * FROM user WHERE user_name = '$name'";
 $result = mysqli_query($db_con, $query);
 $row = mysqli_fetch_array($result); 

 if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];

    $query = "UPDATE user SET user_name = '$name', user_email = '$email', user_phone = '$phone', user_address = '$address', user_birthdate = '$birthday' WHERE user_id='$userid' ";
    $result = mysqli_query($db_con,$query);
   if($result){
    echo '<script>alert("Your Profile is updated successfully.")</script>';
    header("Refresh:0");
   }
   else{
    echo '<script>alert("There is error to perform your request. Please check if your entered information are valid and try again.")</script>';
   }
}

 
 if(isset($_POST["CHANGEP"]))
 {
 
                 $currentPassword = mysqli_real_escape_string($db_con, $_REQUEST['currentPassword']);
                 $newPassword = mysqli_real_escape_string($db_con, $_REQUEST['newPassword']);
                 $confirmPassword = mysqli_real_escape_string($db_con, $_REQUEST['confirmPassword']);
 
                 if($currentPassword != $row['user_password']){
                      echo '<script>alert("Current Password is not correct. Please try again.")</script>';
                 }
                 elseif(!($newPassword==$confirmPassword)){
                     echo '<script>alert("New Password is not matched with your re-enter password. Please try again.")</script>';
                 }
                 else{
 
                      $sql = "UPDATE user SET user_password = '$confirmPassword' WHERE user_id='$userid' ";
                     $result = mysqli_query($db_con, $sql);
                 
                      if(mysqli_affected_rows ($db_con) == 0) {
                          echo '<script>alert("We are sorry, internal error occur. Your password is not updated.")</script>';
                      }
                     elseif($result){
                             print('<script>alert("Your password is updated successfully.")</script>');
                        
                      } 
                     else{
                         echo "ERROR: Unable to execute $sql " . mysqli_error($db_con);
                     }
 
                 }
                
 
 }

  ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />

    <title>User Profile</title>

    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css" />

    <link rel="stylesheet" href="css/main.css?ts=<?=time()?>" />
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/topnav.css" />
    <script type="text/javascript" src="js/functions.js"></script>

    <script type="text/javascript">
        function changepass() {
            document.getElementById("change").style.display = "block";
        }

        function closeDForm() {
            document.getElementById("change").style.display = "none";
        }
    </script>

    <style>
    input[type=text],
    input[type=email] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
    }

    
    .btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 20px;
    font-weight: normal;
    line-height: 1.428571429;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    border-radius: 4px;
    white-space: nowrap;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
    font-weight: 300;
    -webkit-transition: all 0.15s;
    -moz-transition: all 0.15s;
    transition: all 0.15s;
    }

    .btn:focus {
    outline: none;
    }

    .btn:hover,
    .btn:focus {
    color: #2b2b2b;
    text-decoration: none;
    }


    .btn-danger {
    color: #ffffff;
    background-color: #ff2d55;
    border-color: #ff2d55;
    font-size: 18px;
    }

    .btn-danger:hover,
    .btn-danger:focus,
    .btn-danger:active,
    .btn-danger.active,
    .open .dropdown-toggle.btn-danger {
    color: #ff2d55;
    background: transparent;
    border-color: #ff2d55;
    }

    .btn-info {
    color: #ffffff;
    background-color: #34aadc;
    border-color: #34aadc;
    }

    .btn-info:hover,
    .btn-info:focus,
    .btn-info:active,
    .btn-info.active,
    .open .dropdown-toggle.btn-info {
    color: #34aadc;
    background: transparent;
    border-color: #34aadc;
    }

    .btn-info:active,
    .btn-info.active,
    .open .dropdown-toggle.btn-info {
    background-image: none;
    }

        .addPopup2 {
            position: relative;
            text-align: center;
            width: 100%;
        }
        
        .formPopup {
            color: black;
            overflow: auto;
            font-weight: 500;
            display: none;
            position: fixed;
            left: 55%;
            top: 18%;
            transform: translate(-50%, 5%);
            border: 4px solid whitesmoke;
            z-index: 9;
            background-color: snow;
        }
        
        .formContainer {
            max-height: 530px;
            max-width: 500px;
            padding: 20px;
        }
        
        .formContainer input[type=text],
        .formContainer input[type=password],
        .formContainer input[type=email] {
            width: 90%;
            padding: 15px;
            margin: 5px 10px 20px 10px;
            border: none;
            background: #eee;
        }
        
        .formContainer input[type=text]:focus,
        .formContainer input[type=password]:focus,
        .formContainer input[type=email]:focus {
            background-color: #ddd;
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

        <a href="index.html">Logout</a>
        <a href="profile.php" class="active">Profile</a>
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

    <main class="main" style="margin-left: 230px;">
        <br><br>
        <section>
            <div class="split4 left4">
                    <h2>Welcome, <?php echo $_SESSION['UserName']; ?></h2>

                    <div class="addPopup2">
                    <div class="formPopup" id="change">
                        <form action="profile.php" method="POST" class="formContainer">
                            <h2 style="font-weight: 600;">Change Your Password</h2>
                            <label for="currentPassword">
                                <strong>Current Password</strong>
                              </label>
                            <input type="password" name="currentPassword" id="currentPassword"  required>

                            <label for="newPassword">
                              <strong>New Password</strong>
                            </label>
                            <input type="password" name="newPassword" id="newPassword" required>

                            <label for="confirmPassword">
                              <strong>Confirm Password</strong>
                            </label>
                            <input type="password" name="confirmPassword" id="confirmPassword" required>

                            <input type="submit" class="btn" value="Change" name="CHANGEP"></input>
                            <button type="button" class="btn cancel" onclick="closeDForm()">Cancel</button>
                        </form>
                    </div>
                </div>



                    <div class="container2">
                    <form action="profile.php" method="POST">
                        <table class="profiledetails">
                        <tr>
                        <td class='protd'><b>Name</b></td>
                        <td class='protd'><input type="text" id="f-name" value="<?php echo $row['user_name'];?>" name="name"></td>
                        </tr>

                        <tr>
                        <td class='protd'><b>Email</b></td>
                        <td class='protd'><input type="email" id="email" value="<?php echo $row['user_email'];?>" name="email"></td>
                        </tr>

                        <tr>
                        <td class='protd'><b>Phone</b></td>
                        <td class='protd'><input type="text" id="phone" value="<?php echo $row['user_phone'];?>" name="phone"></td>
                        </tr>

                        <tr>
                        <td class='protd'><b>Address</b></td>
                        <td class='protd'><input type="text" id="address" value="<?php echo $row['user_address'];?>" name="address"></td>
                        </tr>

                        <tr>
                        <td class='protd'><b>Date of Birth</b></td>
                        <td class='protd'><input type="date" id="birthday" value="<?php echo $row['user_birthdate'];?>" name="birthday" style="font-size:17px;"></td>
                        </tr>

                        <tr>
                        <td class='protd'><b>Password</b></td>
                        <td class='protd'> <a class="btn btn-danger" onclick="changepass()" title="Change Password" style="font-family:'Montserrat'; font-size:17px; font-weight:600; border-radius:13px; padding:10px;">
                                                             Change Password </a></td>
                        </tr>

                        <tr>
                        <td class='protd'></td>
                        <td class='protd'> <button name="save" value="Update Profile" type="submit" class="btn btn-info" style="font-family:'Montserrat'; font-size:17px; font-weight:600; border-radius:13px; padding:10px;">Update Profile</button>
                        </tr>


                        </table>
                        </form>
                    </div>
                </div>
        </section>

    </main>
</body>

</html>
