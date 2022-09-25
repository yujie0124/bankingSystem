<?php

session_start();
require_once('db_con.php');

$user_name = "";
$user_email = "";
$user_phone = "";
$user_address = "";
$errors = array(); 

if (isset($_POST['register'])) {
  $user_name = mysqli_real_escape_string($db_con, $_POST['user_name']);
  $user_email = mysqli_real_escape_string($db_con, $_POST['user_email']);
  $user_phone = mysqli_real_escape_string($db_con, $_POST['user_phone']);
  $user_address = mysqli_real_escape_string($db_con, $_POST['user_address']);
  //$user_level = mysqli_real_escape_string($db_con, $_POST['user_level']);
  $password1 = mysqli_real_escape_string($db_con, $_POST['password1']);
  $password2 = mysqli_real_escape_string($db_con, $_POST['password2']);

  // form validation
  if (empty($user_name)) { array_push($errors, "Username is required"); }
  if (empty($user_email)) { array_push($errors, "Email is required"); }
  if (empty($user_phone)) { $user_phone = "-"; }
  if (empty($user_address)) { $user_address = "-"; }
  if (empty($password1)) { array_push($errors, "Password is required"); }
  if ($password1 != $password2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM user WHERE user_name='$user_name' LIMIT 1";
  $result = mysqli_query($db_con, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['user_name'] === $user_name) {
      array_push($errors, "Username already exists");
    }
  }

  // Register user if there are no errors in the form
  if (count($errors) == 0) {
  	$user_password = $password1;

  	$query = "INSERT INTO user (user_name, user_email, user_phone, user_address, user_level, user_password) 
  			  VALUES('$user_name', '$user_email', '$user_phone', '$user_address', 'Customer', '$user_password')";
  	$ok = mysqli_query($db_con, $query);

    // if success create user, then create 2 types acc
    if($ok){
        $check = "SELECT * FROM user WHERE user_name = '$user_name'";
        $r=mysqli_query($db_con,$check);
        $row = mysqli_fetch_assoc($r);
        $owner = $row["user_id"];

        $owner = mysqli_real_escape_string($db_con, $owner);


        $query2 = "INSERT INTO bankacc (acc_number, acc_name, acc_type, acc_balance, acc_created_date, user_id) 
                    VALUES((SELECT LPAD(FLOOR(RAND() * 9999999999.99), 10, '1')), '$user_name', 'Saving Account', '0', (SELECT CURRENT_TIMESTAMP()), '$owner');";
        $query3 = "INSERT INTO bankacc (acc_number, acc_name, acc_type, acc_balance, acc_created_date, user_id) 
                    VALUES((SELECT LPAD(FLOOR(RAND() * 9999999999.99), 10, '1')), '$user_name', 'Goal Account', '0', (SELECT CURRENT_TIMESTAMP()), '$owner')";
        $cck = mysqli_query($db_con, $query2);
        $cck2 = mysqli_query($db_con, $query3);
        if($cck && $cck2){
                echo "New records created successfully";
        } else {
                echo "Error: " . $query2 . "<br>" . $db_con->error;
                echo "Error: " . $query3 . "<br>" . $db_con->error;
        }
        
        
        
    }

  	header('location: login.php');
  }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="css/login.css?ts=<?=time()?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?ts=<?=time()?>">

</head>

<body class="bgimg-2">
    <div>
        <div class="caption">
            <h1 class="title">
                <button class="fa fa-home" onclick="location.href = 'index.html';" style="background-color: white; border:none;"></button>BBS Registration
            </h1>
            <div>

                <form class="login" action="register.php" method="POST" style="margin-top:15px;">

                    <?php  if (count($errors) > 0) : ?>
                    <div class="error">
                        <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error ?></p>
                        <?php endforeach ?>
                    </div>
                    <?php  endif ?>

                    <label><b><i class="fa fa-user"></i> User Name (Full Name)
                        </b>
                    </label>
                    <input type="text" id="Uname" placeholder="Full Name" name="user_name" value="<?php echo $user_name; ?>" autofocus required>
                    <br><br>

                    <label><b><i class="fa fa-envelope-open"></i> Email
                        </b>
                    </label>

                    <input type="text" id="Uname" placeholder="Email" name="user_email" value="<?php echo $user_email; ?>" required>
                    <br><br>

                    <label><b><i class="fa fa-phone-square"></i> Phone Number
                        </b>
                    </label>
                    <input type="text" id="Uname" placeholder="Phone Number" name="user_phone" value="<?php echo $user_phone; ?>" required>
                    <br><br>
                    <label><b><i class="fa fa-address-book"></i> Address
                        </b>
                    </label>
                    <input type="text" id="Uname" placeholder="Address" name="user_address" value="<?php echo $user_address; ?>" required>
                    <br><br>
                    <label><b><i class="fa fa-key"></i> Password
                        </b>
                    </label>
                    <input type="password" id="Uname" placeholder="Password" name="password1" required>
                    <br><br>

                    <label><b><i class="fa fa-key"></i> Confirm Password
                        </b>
                    </label>
                    <input type="password" id="Uname" placeholder="Confirm Password" name="password2" required>
                    <br><br><br>

                    <input type="submit" name="register" id="log" value="Sign Up">
                </form>
            </div>
        </div>
    </div>
</body>

</html>
