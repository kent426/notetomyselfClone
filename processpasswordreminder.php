<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="shortcut icon" href="pencil.ico" />

    <title>Note to Myself - Reset Password</title>
    <link type="text/css" href="css/register2.css" rel="stylesheet" media="screen" />
    <script src="js/jquery-1.4.1.min.js" type="text/javascript"></script>
<?php
/**
 * Created by PhpStorm.
 * User: Kent
 * Date: 2017-04-14
 * Time: 3:19 PM
 */
require_once 'PHPMailer-master/PHPMailerAutoload.php';
include_once "commonHead.php";

$emailToBeSent = $_POST['email'];

//echo $emailToBeSent;

$db = connectDB();

$q = "SELECT * FROM users WHERE email = '$emailToBeSent'";
$result = mysqli_query($db,$q) or die(mysqli_error($db));

//has this user in the db table
if(mysqli_num_rows($result)==1) {
    $newPass = bin2hex(random_bytes(2));

    sendemail($emailToBeSent,ROOTDOMAIN,$newPass);
    $hashPass = hash("sha256", $newPass);

    $qResetPass = "UPDATE users SET password = '$hashPass' WHERE email = '$emailToBeSent'";

    $resultReset = mysqli_query($db,$qResetPass) or die(mysqli_error($db));



    echo "
	<h1>Your new password is $newPass</h1>


<br>Your password has been reset. 


<br>An email may have been sent to <span style='color:red;'>$emailToBeSent</span>. 


<br>Please use your new password from now on. 


<br>Then you can <a href='index.php'>log in</a>.";

} else {
  // no record
    echo "No record for $emailToBeSent. Please <a href='register2.php'>register</a>.";
}


function sendemail($toemail,$mysiteurl,$newPass) {
    date_default_timezone_set('America/Vancouver');
    $today = date("D M j G:i:s T Y");
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "kentsnotetome@gmail.com";
    $mail->Password = "1234567asd";
    $mail->SetFrom("kentsnotetome@gmail.com");
    $mail->Subject = "Password reset notice for $mysiteurl";
    $mail->Body = "Welcome to $mysiteurl.
    <br>
    <br>
    <br>
    From kentsnotetome@gmail.com on $today: 
    <br><br>

    Welcome to $mysiteurl. YOUR NEW PASSWORD IS $newPass. Please keep this email or write this down.
    To log in again, please click this link: $mysiteurl";
    $mail->AddAddress("$toemail");
    $mail->Send();

    /*    if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }*/
}


