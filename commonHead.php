<?php
/**
 * Created by PhpStorm.
 * User: Kent
 * Date: 2017-04-09
 * Time: 2:27 AM
 */
include_once "dbconfig/dbconfig.php";

function connectDB(){
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die(mysqli_connect_error());
    mysqli_select_db($db, DB_DATABASE);
    return $db;
}