<?php
include("connection.php");
if (!CheckLogin()) {
   header('Location: index.php');
}
$loginUser = $_SESSION['UserID'];
$arrGroups = [];

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Chat App</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <div class="chat-container">
      <header class="chat-header">
         <h1><i class="fas fa-smile"></i> ChatCord</h1>
         <a href="logout.php" class="btn">Logout</a>
      </header>
      <main class="chat-main">
         <div class="chat-sidebar">
            <h3><i class="fas fa-comments"></i> User Groups:</h3>
            <ul id="users">
               <?php
               $query = "  SELECT DISTINCT g.* 
                           FROM chatgroup g
                           INNER JOIN chatgroupuser gu ON gu.ChatGroupID = g.ChatGroupID
                           WHERE gu.UserID = $loginUser ";
               $result = mysqli_query($con, $query);
               if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_array($result)) {
                     $arrGroups[] = $row["ChatGroupID"];
               ?>
                     <li id="chat-group-<?php echo $row["ChatGroupID"] ?>" class="c-item p-relative">
                        <a href="javascript:void(0)" onclick="LoadChatDetails(this,'<?php echo $row["ChatGroupID"] ?>', '<?php echo $row["Name"] ?>', 'G')"><?php echo $row["Name"] ?></a>
                        <div class="badge">
                        </div>
                     </li>
               <?php
                  }
               } else {
                  echo 'No groups found';
               }

               ?>
            </ul>
            <br />
            <br />
            <h3><i class="fas fa-users"></i> Users</h3>
            <ul id="users">
               <?php
               $query = "SELECT * FROM USER WHERE UserID Not IN (" . $loginUser . ")";
               $result = mysqli_query($con, $query);
               if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_array($result)) {
               ?>
                     <li id="chat-personal-<?php echo $row["UserID"] ?>" class="c-item p-relative">
                        <a href="javascript:void(0)" onclick="LoadChatDetails(this,'<?php echo $row["UserID"] ?>', '<?php echo $row["Name"] ?>', 'P')"><?php echo $row["Name"] ?></a>
                        <div class="badge">
                        </div>
                     </li>
               <?php
                  }
               } else {
                  echo 'No users found';
               }

               ?>
            </ul>
         </div>
         <div class="chat-box">

         </div>
      </main>
   </div>
   <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
   <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>


   <script src="js/socket-handler.js"></script>
   <script src="js/main.js"></script>
   <script type='text/javascript'>
      $("document").ready(function() {
         var UserID = '<?php echo $loginUser; ?>';
         var Name = '<?php echo $_SESSION['Name']; ?>';
         var arrGroups = '<?php echo json_encode($arrGroups) ?>';
         ConnectToChatServer(UserID, Name, arrGroups);
      })

      function LoadChatDetails(ele, userid, username, type) {

         var url = 'chat-box.php?' + $.param({
            type: type,
            toid: userid,
            toname: username,
         });
         loadFileWithCallback('.chat-box', url, function() {
            $(ele).closest('.c-item').find('.badge').html('');
            $('.chat-messages')[0].scrollTop = $('.chat-messages')[0].scrollHeight;
         });
      }
   </script>
</body>

</html>