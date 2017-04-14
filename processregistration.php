<?php
require_once 'PHPMailer-master/PHPMailerAutoload.php';

require_once "commonHead.php";

session_start();

$reEmail = $_POST['email'];
$pass0 = $_POST['passwd'];
$passConf = $_POST['passwd_conf'];

$pass0 = hash("sha256", $pass0);
$passConf = hash("sha256", $passConf);

if ($passConf == $pass0) {
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die(mysqli_connect_error());

    mysqli_select_db($db, DB_DATABASE);
    $q = "SELECT user_id FROM users WHERE email = '$reEmail'";
    $result = mysqli_query($db, $q) or die(mysqli_error($db));

        //insert confirm code to database
    if (mysqli_num_rows($result) == 0) {
        $token = bin2hex(random_bytes(30));
        $qi = "INSERT INTO users VALUES(DEFAULT ,'$reEmail', '$pass0',0,'$token')";
        $userInsert = mysqli_query($db, $qi) or die(mysqli_error($db));

        //TODO send email

        $mysite =  ROOTDOMAIN;
        $conlink = $mysite."/index.php?r=$token&e=$reEmail";
        sendemail($reEmail,$mysite,$conlink);

        //print confirmation info
        registerSuccess($reEmail,$token);

        if ($userInsert) {
            mysqli_close($db);
        }

    } else {
        echo "Account already exists for $reEmail. Did you <a href='forgotpassword.php'>forget your password</a>? Please try again to <a href=\"register2.php\">register</a> or <a href=\"index.php\">log in</a>.";
    }
}

function registerSuccess($reEmail,$token) {
    echo "Thank you. <a href='index.php?r=$token&e=$reEmail'>Finish signing up</a>.


<br>Thank you for registering. 


<br>An email has been sent to <span style='color:red;'>$reEmail</span>. 


<br>Please confirm your registration by clicking the link in your email. 


<br>Then you can <a href='index.php'>log in</a>. <span style='color:red;text-decoration:blink'>Alternatively, you can finish signing up <a href='index.php?r=$token&e=$reEmail'></span>now</a>.";
}

function sendemail($toemail,$mysiteurl,$confrimlink) {
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
 //   $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "kentsnotetome@gmail.com";
    $mail->Password = "1234567asd";
    $mail->SetFrom("kentsnotetome@gmail.com");
    $mail->Subject = "Registration notice for note-to-myself.com for $toemail";
    $mail->Body = "Welcome to $mysiteurl.
    <br>
    <br>
    <br>
    To finish signing up, please click this link: $confrimlink";
    $mail->AddAddress("$toemail");
    $mail->Send();

/*    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message has been sent";
    }*/
}

