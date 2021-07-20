// function init(messages) {

//     if (messages && messages.length) {

//         clearChatWindow();

//         messages.forEach(value => {

//             console.log(value.message);
//             console.log(value.message);
//             var client_message_id = createClientId();
//             var messageList = $("#chat-message-list");
//             var status = null

//             if (value.sender_uid == current_UUID) {
//                 switch (value.sync_status) {
//                     case "confirmed":
//                         status = MSG_STATUS.SENT;
//                         break;
//                     case "stored":
//                         status = MSG_STATUS.SENT;
//                         var data = value
//                         data.status_code = 200
//                         confirmMessageSent(data)
//                         break;
//                     case "read":
//                     case "done":
//                         status = MSG_STATUS.READ;
//                         break;
//                 }
//                 return createMessage(messageList, value.message, status, client_message_id, value.message_uid);
//             } else {
//                 return receiveMessage(value)
//             }
//         });
//     }
// }

function sync() {
    //console.log("sync pool");

    forcePing()

    if (sync_requests.length) {
        var now = new Date()
        last_sync_timestamp = now ;

        var data = {
            "last_sync_timestamp": last_server_timestamp,
            "current_uuid": current_UUID,
            "current_suid": current_SUID,
            "data": sync_requests
        }
        addCSRF(data) 
        //console.log("start sync");
        //console.log(data);
        sync_requests = [];

        $.ajax({
                url: '/chat/api/sync',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: true,
                success: function (response, status) {
                    console.log("recebido:",response);
                    if (response) {
                        last_sync_timestamp = new Date()
                        last_server_timestamp = response.timestamp
                        if (response.data.length > 0) {
                            response.data.forEach((item, index, array) => {
                                if ("stored" in item) {
                                    confirmMessageSent(item.stored);
                                } else if ("read" in item) {
                                    setMessageRead(item.read)
                                } else if ("new" in item) {
                                    receiveMessage(item.new)
                                } else if ("init" in item) {
                                    init(item.init)
                                }
                            })
                        }
                    }
                }
            })
            .done(function (response) {
                //console.log("done");
                //console.log(response);
            })
            .fail(function (response) {
                //console.log("fail");
                //console.log(response);
            })
            .always(function (response) {
                sync_semaphore = false;
            });
    }
}


$(document).ready(function () {

    forceInit();
    sync();

    setInterval(sync, 1000);

    $("#submit-message").click(e => {

        var text = $("#input-message").val();
        var messageList = $("#chat-message-list");
        sendMessage(messageList, text, window.session_data.attendee_avatar);

    });
});