
function loadFile(element, file) {
   if ($(element).find(".spinner-block").length == 0) {
      $(element).html('<div class="spinner-block" style="height:250px;"></div>');
   }
   $(element).load(file);
}

function loadFileWithCallback(element, file, callback) {
   if ($(element).find(".spinner-block").length == 0) {
      $(element).html('<div class="spinner-block" style="height:250px;"></div>');
   }
   $(element).load(file, function () {
      if (typeof callback != "undefined") {
         callback();
      }
   });
}

function initLoadingBeforeAjaxCall(element, loadingClass) {
   if (typeof (loadingClass) == "undefined") loadingClass = "aiLoading";

   $(document).ajaxStart(function () {
      $(element).addClass(loadingClass);
   });
   $(document).ajaxComplete(function () {
      $(element).removeClass(loadingClass);
      $(document).unbind("ajaxStart ajaxStop");
   });
}


function InsertMessage(FromUserID, ToUserID, ChatGroupID, MessageText, Attachment, MessageType) {
   return new Promise((resolve, reject) => {
      var data = {
         action: 'InsertMessage',
         FromUserID: FromUserID,
         ToUserID: ToUserID,
         ChatGroupID: ChatGroupID,
         MessageText: MessageText,
         Attachment: Attachment,
         MessageType: MessageType,
      }
      initLoadingBeforeAjaxCall("#dvAddForm");
      $.get("getAjaxData.php", data)
         .done(function (responce) {
            try {
               console.log(responce);
               if (responce["status"] == "success") {
                  const res_data = responce["data"];
                  console.log(res_data);
                  resolve();
               }
               else {
                  throw responce["message"];
                  reject();
               }
            }
            catch (error) {
               console.error(error);
               reject();
            }
         });
   });
}


function generateMessageHtml(SenderName, MessageText, CreatedOn) {
   return `<div class="message">
            <p class="meta">
               ${SenderName}
               <span>${CreatedOn}</span>
            </p>
            <p class="text">
               ${MessageText}
            </p>
         </div>`;
}