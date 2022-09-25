<?php
  session_start();
  require_once('db_con.php');

  if (!$_SESSION['UserName']){
    header("Location: index.html");
}

$UserId = $_SESSION['UserId'];
$UserName = $_SESSION['UserName'];
$_SESSION['EmpEmail'];

$query = "SELECT * FROM user WHERE user_id = '".$_SESSION['UserId']."'";  
if($result = mysqli_query($db_con, $query)){  //if query successful
    $rowcount = mysqli_num_rows($result);
    if($rowcount > 0)  //if whiteboard exists
        $row = mysqli_fetch_assoc($result);
        $_SESSION['EmpEmail']= $row['user_email'];
} 
else{
    echo '<script>alert("ERROR: Unable to execute SQL query. ")</script>';
}

 

if(isset($_POST['submit'])){
    $userid = $_SESSION["UserId"];
    $name = $_POST['name'];
    $email = $_SESSION['EmpEmail'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $query = "INSERT INTO contact (name, email, title, message, user_id) VALUES('$name','$email','$subject','$message','$userid')";
    if(mysqli_query($db_con,$query)){
        echo '<script>alert("Your feedback has been sent to us successfully. Thank you very much for your feedback")</script>';
    };
}

  ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		
		<title>Contact Us</title>

		<link
			rel="stylesheet"
			href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css"
		/>

        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="css/sidebar.css" />
        <link rel="stylesheet" href="css/topnav.css" />
        <link rel="stylesheet" href="css/contact.css?ts=<?=time()?>" />
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
		<main class="main" style="margin-left:100px;">
        <br><br>
            <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="mdi mdi-contact-mail" style="color:#045BBC;"></i>  Contact Us</h3>
                    </div>
                </div>
                <!-- page start-->
                <table style="margin-left:150px; width:100%;">
                    <tr>
                    <td style="width:400px;">
                        
                            <h3>Send us a message&nbsp;&nbsp;<i class="mdi mdi-message"></i></h3>
                        
                        <div id="sendmessage">Your message has been sent. Thank you!</div>
                        <div id="errormessage"></div>
                        <form action="contact.php" method="post" role="form" class="contactForm">
                            <h4 style="color: darkgray; margin-top: 20px;"><?php echo "Logged in as: "?>
                                <text style="color: black;"><?php echo $_SESSION["UserName"]." (USER ID: ".$_SESSION["UserId"].")"  ?></text>
                            </h4>
                            <div class="form-group">
                                <input type="text" name="name" id="name" value="<?php echo $_SESSION["UserName"] ?>" readonly>
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" id="email" value="<?php echo $_SESSION["EmpEmail"] ?>" readonly/>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" required/>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" style="resize:none" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message" required></textarea>
                                <div class="validation"></div>
                            </div>

                            <div class="text-center">
                                <button type="submit" value=Send name="submit" class="btn-create">Send Message</button>
                            </div>
                        </form>

                        
                    </td>
                    <td style="width:150px;"></td>

                    <td style="width:400px;">
                        
                        <h3>We'd love to hear from you!</h3>
                        
                        <div>
                            <p style="font-size: small; font-family: Verdana, Geneva, Tahoma, sans-serif;">
                                Feel free to send us message. <br>
                                We will review on your feedback for further improvement of our system. <br>
                                Thank you very much.
                            </p>

                            <h4>Address:</h4>
                                Bank System from Company Z, <br>
                                Jalan Ayer Keroh Lama,<br>
                                75450 Melaka,<br>
                                Malacca.<br>
                            <h4>Telephone:</h4>+6012-3456789<br>
                            <h4>Fax:</h4>+06-3456789
                        </div>
                    </td>
                    </tr>
                </table>
                <!-- page end-->
           

		</main>
	</body>
</html>