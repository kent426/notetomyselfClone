<?php
require_once "commonHead.php";

session_start();

//account confirmation
if(isset($_GET['r'])&&isset($_GET['e'])) {
    $confirmcode = $_GET['r'];
    $emailtoconfirm = $_GET['e'];

    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die(mysqli_connect_error());
    mysqli_select_db($db, DB_DATABASE);
    $q = "SELECT confirmcode FROM users WHERE email = '$emailtoconfirm'";
    $result = mysqli_query($db, $q) or die(mysqli_error($db));
    //confirm code matchs
    if (mysqli_num_rows($result) == 1&&strcmp(mysqli_fetch_row($result)[0], $confirmcode)==0) {
        $queryActivate = "UPDATE users SET isactive = 1 WHERE email = '$emailtoconfirm'";
        mysqli_query($db,$queryActivate) or die(mysqli_error($db));
        $_SESSION['login'] = 1;
        $_SESSION['username'] = $email;
        echo "Thank you for confirming your registration for $email. You may now <a href='index.php'>log in</a>.";
        die();
    } else if (mysqli_num_rows($result) == 1&&strcmp(mysqli_fetch_row($result)[0], $confirmcode)!=0) {
        //wrong confirmcode
        echo "Wrong confirmation. Check your email ($email).";
    } else {
        //other random situation
        echo " Error logging in. Try <a href='index.php'>logging in</a> again or <a href='register2.php'>register</a> for a new account";
    }
    mysqli_close($db);
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="shortcut icon" href="pencil.ico" />


    <title>Note to Myself - Log in</title>
    <link type="text/css" href="css/register2.css" rel="stylesheet" media="screen"></link>
    <script src="js/jquery-1.4.1.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/login2.js"></script>

</head>
<body>

<h1>Log in</h1>

<form action="processLogin.php" method="post" onsubmit="return isFormDataValid();">
    <ul>

        <li>
            <h3>Email address<span id="validEmail"></span></h3>
            <p>
                <input type="text" name="email" id="email" tabindex="1"  value='' />


            </p>
        </li>
        <li>

            <h3 title="6+ characters">Password<span id="validPass"></span></h3>
            <p>
                <input type="password" id="passwd" name="passwd" title="6+ characters" tabindex="2" /></p>
        </li>





        <li class="last">
            <p>
                <!--<a id="signup" href="#"> -->
                <input type="image" id="submit" src="login2.png" alt="login button" style="vertical-align:middle;" tabindex="5" />
                <!--</a>-->
            </p>
        </li>

        <li>
            <p><a href="register2.php">Register</a> | <a href="forgotpassword.php">Forgot password</a>
            </p>
        </li>

        <li><a href="http://twitter.com/#!/notes_myself">Twitter</a></li>
    </ul>

</form>
</body>
</html>

