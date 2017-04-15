<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

</head>
<body>

<?php

require_once "commonHead.php";

session_start();


$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or
die(mysqli_connect_error());

mysqli_select_db($db, DB_DATABASE);

//get form data
$email = $_POST['email'];
$pass = hash("sha256",strip_tags(trim($_POST['passwd'])));

$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($db,$query) or die(mysqli_error($db));

$numOfuser = mysqli_num_rows($result);
$row = mysqli_fetch_all($result, MYSQLI_ASSOC);


//activated
if($numOfuser==1&&strcmp($row[0]['password'], $pass)==0&&$row[0]['isactive'] == 1) {
    $_SESSION['login'] = 1;
    $_SESSION['username'] = $email;

    $_SESSION['CREATED'] = time();
    $_SESSION['BROWSER']=$_SERVER['HTTP_USER_AGENT'];
    $_SESSION['IP']=$_SERVER['REMOTE_ADDR'];
    $_SESSION['LANGUAGE']=$_SERVER['HTTP_ACCEPT_LANGUAGE'];

    //set cookies for check
    $value = session_id();
    setcookie("id", $value, time()+1200);

    header("Location:notes.php");
    die();
    //not activated
} else if($numOfuser==1&&strcmp($row[0]['password'], $pass)==0&&$row[0]['isactive'] == 0) {
    $confirmcode = $row[0]['confirmCode'];
    $email = $row[0]['email'];
    echo "You need to confirm your registration before you can log in. Check your email ($email).


<br>Thank you for registering. 


<br>An email has been sent to <span style='color:red;'>$email</span>. 


<br>Please confirm your registration by clicking the link in your email. 


<br>Then you can <a href='index.php'>log in</a>. <span style='color:red;text-decoration:blink'>Alternatively, you can finish signing up <a href='index.php?r=$confirmcode&e=$email'></span>now</a>.";
} else{
    echo " Error logging in. Try <a href='index.php'>logging in</a> again or <a href='register2.php'>register</a> for a new account";
}


?>

</body>

