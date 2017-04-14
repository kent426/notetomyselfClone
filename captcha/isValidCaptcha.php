<?php

session_start();

$entered = $_POST['enteredCode'];
if($_SESSION['captcha']== $entered) {
    echo true;
} else {
    echo false;
}