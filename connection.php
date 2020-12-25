<?php
session_start();
error_reporting('E_Deprecated');
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "chatapp";

$con = mysqli_connect($hostname, $username, $password) or die('Connection to host is failed, perhaps the service is down!');
mysqli_select_db($con, $dbname) or die('Database name is not available!');

function getPOSTValue($key)
{
   return isset($_POST[$key]) ? $_POST[$key] : null;
}
function getGETValue($key)
{
   return isset($_GET[$key]) ? $_GET[$key] : null;
}
function IFF($cond, $if_value, $else_value)
{
   if ($cond) {
      return $if_value;
   } else {
      return $else_value;
   }
}

function CheckLogin()
{
   if ($_SESSION['UserID'] > 0) {
      return true;
   }
   return false;
}
