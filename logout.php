<?php
session_start();
$_SESSION['UserID'] = null;
$_SESSION['Email'] = null;
$_SESSION['Name'] = null;
header('Location: index.php');
