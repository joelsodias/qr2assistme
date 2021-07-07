const MSG_STATUS = {
    "WAITING": {
        "direction": "sent",
        "icon": "far fa-clock text-muted",
        "title": "Esta mensagem ainda não foi enviada",
    },
    "SENT": {
        "direction": "sent",
        "icon": "fas fa-check text-muted",
        "title": "Esta mensagem ainda não foi lida",
    },
    "RECEIVED": {
        "direction": "received",
        "icon": "",
        "title": "",
    },
    "READ": {
        "direction": "sent",
        "icon": "fas fa-check text-primary",
        "title": "Esta mensagem já foi lida",
    },
    "ERROR": {
        "direction": "error",
        "icon": "fas fa-exclamation-circle text-danger",
        "title": "Esta mensagem não pôde ser enviada",
    },
    "NONE": {
        "direction": "none",
        "icon": "",
        "title": "",
    },
}

function refreshSessionData(session) {
    if (session) {
        $(".attendee-name").text(session.attendee_name);
        //$(".attendee-avatar").attr("src", session.attendee_avatar);
        window.session_data = session
    }
}

function init(data) {

    if (data) {

        if (data.messages && data.messages.length) {

            clearChatWindow();

            data.messages.forEach(value => {

                // console.log(value.message);
                // console.log(value.message);
                var client_message_id = createClientId();
                var messageList = $("#chat-message-list");
                var status = null

                if (value.sender_uid == current_UUID) {
                    switch (value.sync_status) {
                        case "confirmed":
                            status = MSG_STATUS.SENT;
                            break;
                        case "stored":
                            status = MSG_STATUS.SENT;
                            var data = value
                            data.status_code = 200
                            confirmMessageSent(data)
                            break;
                        case "read":
                        case "done":
                            status = MSG_STATUS.READ;
                            break;
                    }
                    return createMessage(messageList, value.message, status, client_message_id, value.message_uid, value.user_avatar);
                } else {
                    return receiveMessage(value)
                }
            });
        }

        if (data.session) {
            refreshSessionData(data.session);
        }

    }
}

function createClientId() {
    // Math.random should be unique because of its seeding algorithm.
    // Convert it to base 36 (numbers + letters), and grab the first 9 characters
    // after the decimal.
    return '' + Math.random().toString(36).substr(2, 9);
};

function animateScroll(container, element, speed = 0) {
    container.animate({
        scrollTop: element.offset().top - container.offset().top + container.scrollTop()
    }, speed);
}

function createMessage(container, text = "", status = MSG_STATUS.NONE, clientId = "", serverId = "", avatar = "") {

    var html =
        "<div class='chat-message " + status.direction + "' id='chat-message-" + clientId + "' clientid='" + clientId + "' serverid='" + serverId + "'>" +
        ((avatar != "" && avatar != null && avatar != "null") ?
            //"<img class='chat-message-avatar' src='" + avatar + "'>" :
            "<img class='chat-message-avatar' src='" + avatar + "'>" :
            "<img class='chat-message-avatar' src='/images/avatar/default.png'>"
        ) +
        "<div class='chat-message-text'>" + text +
        "<i class='chat-message-status " + status.icon + "' title='" + status.title + "'></i>" +
        "</div>" +
        "</div>";

    container.append(html);
    var message = $("#chat-message-" + clientId);

    animateScroll(container, message)

    return message;
}

function receiveMessage(value) {
    var client_message_id = createClientId();

    var text = value.message.replace(/\r?\n/g, '<br />');

    var messageList = $("#chat-message-list");

    var status = null;

    status = MSG_STATUS.RECEIVED;

    switch (value.sync_status) {


        case "stored":
        case "confirmed":
            addSyncRequest({
                "action": "read-message",
                "data": {
                    "message_uid": value.message_uid,
                    "reader_uid": current_UUID,
                }
            });

            break;
        case "read":
            addSyncRequest({
                "action": "confirm-read-message",
                "data": {
                    "message_uid": value.message_uid,
                    "reader_uid": current_UUID,
                }
            });

            break;
        case "done":

            break;
    }

    return createMessage(messageList, text, status, client_message_id, value.message_uid, value.user_avatar);
}

function setMessageStatus(message, status) {

    var icon = message.find("i.chat-message-status");

    icon.attr("class", "chat-message-status " + status.icon)
    icon.attr("title", status.title)

}

function setMessageRead(data) {
    if (data.message_uid) {
        var message = $("[serverid=" + data.message_uid + "]")
        if (message.length > 0) setMessageStatus(message, MSG_STATUS.READ);

        var confirmation = {
            "action": "confirm-read-message",
            "data": {
                "session_uid": current_SUID,
                "sender_uid": current_UUID,
                "message_uid": data.message_uid
            }
        }
        addSyncRequest(confirmation);
    }

}

function confirmMessageSent(data) {

    var message = $("#chat-message-" + data.client_message_id)

    if (data.status_code == 200) {
        message.attr("serverid", data.message_uid)
        setMessageStatus(message, MSG_STATUS.SENT)

        var confirmation = {
            "action": "confirm-message",
            "data": {
                "session_uid": current_SUID,
                "sender_uid": current_UUID,
                "message_uid": data.message_uid
            }
        }
        addSyncRequest(confirmation);

        //console.log("success");
    } else {
        //console.log("error");
        setMessageStatus(message, "error")
    }
}

function sendMessage(container, text, avatar) {

    var client_message_id = createClientId();
    var text = text.replace(/\r?\n/g, '<br />');
    var message = createMessage(container, text, MSG_STATUS.WAITING, client_message_id, null, avatar);

    var data = {
        "action": "store-message",
        "data": {
            "session_uid": current_SUID,
            "sender_uid": current_UUID,
            "message": text,
            "client_message_id": client_message_id
        }
    }
    addSyncRequest(data);
}

function clearChatWindow() {
    $("#chat-message-list").html("");
}

function forceInit() {
    addSyncRequest({
        "action": "init",
        "current_suid": current_SUID,
        "current_uuid": current_UUID,
    });
}

function forcePing() {
    if (last_sync_timestamp) {
        var now = new Date()
        var date = new Date(last_sync_timestamp * 1000);
        //last_sync_timestamp = now 
        // console.log("check ping")
        // console.log(last_sync_timestamp)
        // console.log(now);
        // console.log(date);
        // console.log(now - date);


        if (now - date > 10000) {
            //console.log("ping")
            addSyncRequest({
                "action": "ping",
                "data": {
                    "timestamp": date,
                    "current_suid": current_SUID,
                    "current_uuid": current_UUID,
                }
            });

            //console.log(last_sync_timestamp)
        }
        $("title").text("(" + (now - date) + ")")

    }
}

function addSyncRequest(request) {
    console.log("addSyncRequest:", request)
    sync_requests.push(request);
}


var sync_requests = [];
var last_sync_timestamp = null;
var sync_semaphore = false;