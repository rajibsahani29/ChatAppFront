<?php
include("connection.php");

$fromID = $_SESSION["UserID"];
$fromName = $_SESSION["Name"];

$toId = getGETValue('toid');
$toName = getGETValue('toname');

$chatType = getGETValue('type');

if ($toId > 0) {
?>
   <input id="hdnChatToID_OR_GroupID" type="hidden" value="<?php echo $chatType . '_' . $toId; ?>" />
   <div class="chat-messages">
      <?php
      $query = '';
      if ($chatType == 'P') {
         $query = "SELECT m.*, uTo.Name as ToName, uFrom.Name as FromName  
            FROM MESSAGE m
            INNER JOIN USER uTo ON uTo.UserID = m.ToUserID
            INNER JOIN USER uFrom ON uFrom.UserID = m.FromUserID
            WHERE ChatGroupID IS NULL AND (m.ToUserID = $toId OR m.FromUserID = $toId) AND (m.ToUserID = $fromID OR m.FromUserID = $fromID )
            ORDER BY m.CreatedOn ASC
         ";
      } else {
         //Group Query
         $query = "SELECT m.*, uFrom.Name as FromName  
               FROM MESSAGE m
               INNER JOIN USER uFrom ON uFrom.UserID = m.FromUserID
               WHERE m.ChatGroupID = $toId
               ORDER BY m.CreatedOn ASC
         ";
      }
      //echo $query;
      $result = mysqli_query($con, $query);
      if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_array($result)) {
      ?>
            <div class="message">
               <p class="meta">
                  <?php
                  if ($row["FromUserID"] == $fromID) {
                     echo 'Me';
                  } else {
                     echo $row["FromName"];
                  }
                  ?>
                  <span><?php echo date("g:i A", strtotime($row["CreatedOn"])); ?></span>
               </p>
               <p class="text">
                  <?php echo $row["MessageText"]; ?>
               </p>
            </div>
      <?php
         }
      } else {
         echo '';
      }

      ?>
   </div>
   <div class="chat-form-container">
      <div id="feedback"></div>
      <form id="chat-form">
         <input id="msg" type="text" placeholder="Enter Message" required autocomplete="off" />
         <button class="btn"><i class="fas fa-paper-plane"></i> Send</button>
      </form>
   </div>
   <script type='text/javascript'>
      var fromID = '<?php echo $fromID; ?>';
      var fromName = '<?php echo $fromName; ?>';
      var toID = '<?php echo $toId; ?>';
      var ChatType = '<?php echo $chatType; ?>';

      $("#chat-form").on('submit', e => {
         try {
            e.preventDefault();

            // Get message text
            let messageText = e.target.elements.msg.value;
            messageText = messageText.trim();

            if (!messageText) {
               return false;
            }
            var ToID = ChatType == 'P' ? toID : '';
            var GroupID = ChatType == 'G' ? toID : '';

            InsertMessage(fromID, toID, GroupID, messageText, '', 'TXT').then(() => {
               var html = generateMessageHtml('Me', messageText, new moment().format("h:mm A"));
               $(".chat-messages").append(html);

               SendMessage(fromID, fromName, toID, ChatType, messageText, '', 'TXT');
               $('.chat-messages')[0].scrollTop = $('.chat-messages')[0].scrollHeight;
               // Clear input
               e.target.elements.msg.value = '';
               e.target.elements.msg.focus();
            });
         } catch (error) {
            alert("Error");
         }
      });

      $("#msg").on('input', function() {
         onTyping(fromID, fromName, toID, ChatType);
      })
   </script>
<?php
} else {
?>
   <h5>something went wrong, please try again.</h5>
<?php
}
?>