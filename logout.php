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
<?php
ob_start();
session_start();

if(!isset($_SESSION['login'])){
die('<a href="index.php">log in</a> or <a href="register2.php">register</a>  before logging out.');
}
echo "<h2>".$_SESSION['username']."is now logged out. Thank you.</h2><p><a href='index.php'>Log in</a> again.</p>";



session_destroy();
ob_end_flush();
?>