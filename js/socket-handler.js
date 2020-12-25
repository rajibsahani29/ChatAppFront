//const socket = io('ws://localhost:3000');
//const socket = io('ws://localhost:9091/ChatServer');
//const socket = io('ws://localhost:9092');
const socket = io('ws://3.18.122.133:8082');

//Connect User with chat bot
function ConnectToChatServer(UserID, Name, arrGroups) {
   socket.emit('joinChatServer', { UserID, Name, arrGroups }, function (response) {
      console.log(response);
   });
}

// On Send message
function SendMessage(SenderID, SenderName, ReceiverID, ChatType, MessageText, Attachment, ContentType) {
   const message = { SenderID, SenderName, ReceiverID, ChatType, MessageText, Attachment, ContentType };
   socket.emit('sendMessage', message);
}

// Listen On Receive message
socket.on('receiveMessage', message => {
   console.log(message);
   var ChatBoxID = 0;
   if (message.ChatType == 'G') {
      ChatBoxID = message.ReceiverID;
   }
   else if (message.SenderID > 0) {
      ChatBoxID = message.SenderID;
   }
   if (ChatBoxID > 0) {

      if ($("#hdnChatToID_OR_GroupID").val() == message.ChatType + '_' + ChatBoxID) {
         var html = generateMessageHtml(message.SenderName, message.MessageText, new moment().format("h:mm A"));
         $(".chat-messages").append(html);
         $('.chat-messages')[0].scrollTop = $('.chat-messages')[0].scrollHeight;
      }
      else {
         var elementid = '';
         if (message.ChatType == 'G') {
            elementid = '#chat-group-' + ChatBoxID;
         }
         else {
            elementid = '#chat-personal-' + ChatBoxID;
         }
         var count = Number($(elementid).find('.badge span').html()) || 0;
         $(elementid).find('.badge').html(`<span>${count + 1}</span>`);
      }
   }
});

//On Typing
function onTyping(SenderID, SenderName, ReceiverID, ChatType) {
   socket.emit('typing', { SenderID, SenderName, ReceiverID, ChatType });
}


//Listen on typing
socket.on('typing', ({ SenderID, SenderName, ReceiverID, ChatType }) => {
   var ChatBoxID = 0;
   if (ChatType == 'G') {
      ChatBoxID = ReceiverID;
   }
   else if (ChatType == 'P' && SenderID > 0) {
      ChatBoxID = SenderID;
   }

   if (ChatBoxID > 0) {
      if ($("#hdnChatToID_OR_GroupID").val() == ChatType + '_' + ChatBoxID) {
         $("#feedback").html("<p><i>" + SenderName + " is typing a message..." + "</i></p>");
         setTimeout(function () {
            $("#feedback").html('');
         }, 3000);
      }
   }
})
