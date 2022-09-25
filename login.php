<?php
  session_start();
  require_once('db_con.php');
  $message = "";

  if(isset($_POST["btnlogin"]))
{
    $username=$_POST["username"];
    $password=$_POST["password"];
  

	$query = "SELECT * FROM user WHERE user_name='$username' AND user_password='$password'"; 
    $result = mysqli_query($db_con, $query);

              if(mysqli_num_rows($result) > 0){
                     while($row = mysqli_fetch_assoc($result)){
                            
                        
                        if($row["user_level"] == "Admin"){
                                   $_SESSION['UserName'] = $row["user_name"];
                                   $_SESSION['UserId'] = $row["user_id"];
                                   $_SESSION['UserLevel'] = $row["user_level"];
                                   header("Location: home.php");
                            }
                            else{
                                $_SESSION['UserName'] = $row["user_name"];
                                $_SESSION['UserId'] = $row["user_id"];
                                   header("Location: home.php");
                            }
                     }
              }else{
                echo '<script>alert("Your username or password is not correct. Please try again")</script>';
                header("Refresh:0");
          }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">


    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/login.css?ts=<?=time()?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?ts=<?=time()?>">
    



</head>

<body class="bgimg-1">
    <div>
        
        <h1 class="title">
        <button class="fa fa-home" onclick="location.href = 'index.html';" style="background-color: white; border:none;"></button>
          BBS Login </h1>
            <div>


                <form class="login" action="login.php" method="POST">


                    <label><b><i class="fa fa-user"></i> User Name
                        </b>
                    </label>

                    <input type="text" id="Uname" placeholder="Username" name="username" autofocus required>
                    <br><br>
                    <label><b><i class="fa fa-key"></i> Password
                        </b>
                    </label>
                    <input type="password" id="Uname" placeholder="Password" name="password" required>
                    <br><br><br>

                    <input type="submit" name="btnlogin" id="log" value="Login">
                    <br>
                    <p style="text-align:left;">Don't have an account?<a href="register.php" style="color:black;background:none;font-weight:bolder;">Register Now!</a></p>


                </form>

            </div>
        
    </div>




</body>

</html>
