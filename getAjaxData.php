<?php
include("connection.php");
header('Content-Type: application/json');

$action = getGETValue("action");
$response = array("status" => "success", "data" => "", "message" => "");

if (!CheckLogin()) {
   Error("Login Failed");
   //$response = array("status"=> "error", "data" =>"", "message"=> "Login Failed" );
   echo json_encode($response);
   exit;
}

if (function_exists($action)) {
   $action();
} else {
   Error("Not Found", 404);
   //$response = array("status"=> "error", "data" =>"", "message"=> "Not Found" );
   echo json_encode($response);
}

function InsertMessage()
{
   global $response, $con;
   $FromUserID = getGETValue("FromUserID");

   $ToUserID = getGETValue("ToUserID");
   $ToUserID = IFF($ToUserID == '', 'null', $ToUserID);

   $ChatGroupID = getGETValue("ChatGroupID");
   $ChatGroupID = IFF($ChatGroupID == '', 'null', $ChatGroupID);

   $MessageText = getGETValue("MessageText");
   $Attachment = getGETValue("Attachment");
   $MessageType = getGETValue("MessageType");


   $query = "INSERT INTO `message`(`FromUserID`, `ToUserID`, `ChatGroupID`, `MessageText`, `Attachment`, `MessageType`, `CreatedOn`) 
                        VALUES ($FromUserID, $ToUserID, $ChatGroupID, '$MessageText', '$Attachment', '$MessageType', NOW())";
   $returnValue = mysqli_query($con, $query);
   if ($returnValue === TRUE) {
      $response["data"] = mysqli_insert_id($con);
   } else {
      $response = array(
         "status" => "error",
         "data" => $query,
         "message" => "Internal server error."
      );
   }
   echo json_encode($response);
}




function Error($message, $code = 500)
{
   global $response;
   http_response_code($code);
   //header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);

   if (!$message) {
      $message = "Internal Server Error";
   }
   $response = array("status" => "error", "data" => "", "message" => $message);
}
