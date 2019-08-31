let currentConversation;
let messageFullScreen;
let page;

$(document).ready(function(){
    if($("#conversation_middle").data("foreigner") && parseInt($("#conversation_middle").data("foreigner")) >= 0){
        displayConversation(parseInt($("#conversation_middle").data("foreigner"))); //Dans cet ordre sinon return
        currentConversation = parseInt($("#conversation_middle").data("foreigner"));
    }
    initMessageBaseActions();
    displayConversations();
    updateMessages();
});

function updateMessages(){
    if(currentConversation){
        $.ajax({
            url:"/send_data",
            type:"POST",
            method:"POST",
            data:{"data":"message","type":"check_message","foreigner":currentConversation}
        }).done(function(data){
            if(parseInt(data) != parseInt($("#conversation_content .message:last-child").data("id"))){
                displayConversation(currentConversation);
                displayConversations();
            }
        });
    }
    $.ajax({
        url:"/send_data",
        type:"POST",
        method:"POST",
        data:{"data":"message","type":"check_conversations"}
    }).done(function (data) {
        let messageMap = String(data).split(";");
        $conversations = $("#conversation_list .conversation");
        let newMessage = false;
        if($conversations.length !== messageMap.length){
            newMessage = true;
        } else {
            for (let i = 0; i < messageMap.length; i++){
                let lastMessageId = String(messageMap[i]).split(":")[1];
                let conversationId = String(messageMap[i]).split(":")[0];
                for(let j = 0; j < messageMap.length; j++){
                    if($($conversations.get(j)).data("id") === conversationId){
                        if(lastMessageId !== $($conversations.get(j)).find(".last_message").data("id")){
                            newMessage = true;
                            break;
                        }
                    }
                }
            }
        }
        if(newMessage){
            displayConversations();
        }
    });
    setTimeout(updateMessages, 5000);
}

function seeConversation(foreignUser){
    let foreigner = foreignUser;
    $.ajax({
        url: "/manage_message",
        type: "POST",
        method: "POST",
        data: {"type":"see","foreigner":foreigner}
    }).done(function (data) {
        if(currentConversation === foreigner) { //Pour éviter les see qui se pack
            displayConversations();
            updateNotifications(getNotifications()); //On met a jour les notifs
        }
    });
}

function displayConversations(){
    if(currentConversation) $("#conversation_list .conversation").each(function(){$(this).removeClass("expanded");}); //Esthetique
    if(currentConversation) $("#conversation_list .conversation[data-id="+currentConversation+"]").addClass("expanded"); //Deux fois pour pas de délai pur esthetique
    let actualConversation = currentConversation;
    $.ajax({
        url:"/display_messages",
        type:"POST",
        method:"POST",
        data:{"current_conversation":actualConversation}
    }).done(function (data) {
        if(actualConversation == currentConversation) { //Eviter le pack
            $("#conversation_list").html(data);
            initMessageActions();
        }
    });
}

function displayConversation(foreignUser){
    page = 0;
    currentConversation = foreignUser;
    seeConversation(foreignUser);
    $.ajax({
        url: "/display_messages",
        type: "POST",
        method: "POST",
        data: {"foreigner":foreignUser},
    }).done(function (data) {
        if(!String(data).includes("class='first_message'")){
            $(".scroll_top").fadeIn({duration:200, queue:false});
        }
        $("#conversation_content").html(
            data
        );
        $("#scrollable_area").get(0).scrollTop = $("#conversation_content").get(0).scrollHeight;
    });
}

function sendMessage(foreignUser, message){
    $.ajax({
        url: "/manage_message",
        type: "POST",
        method: "POST",
        data: {"type":"send","foreigner":foreignUser,"content":message}
    }).done(function (data) {
        displayConversation(foreignUser);
        displayConversations();
    });
}

function initMessageActions(){
    $("#conversation_list .conversation").unbind("click");
    $("#conversation_list .conversation").on({
        click: function () {
            if($(this).hasClass("formGray")) return;
            let foreignUser = $(this).data("id");
            if(currentConversation && currentConversation == foreignUser) return;
            displayConversation(foreignUser);
        }
    });
}

function initMessageBaseActions(){
    $("#screen_control").on({
        click: function (e) {
            e.preventDefault();
            e.stopPropagation();
            if(messageFullScreen){
                $("#content_list").css("display", "table-cell");
                $(this).text("Mode plein écran");
                messageFullScreen = false;
            } else {
                $("#content_list").css("display", "none");
                $(this).text("Mode fenetré");
                messageFullScreen = true;
            }
        }
    });
    $("#send").on({
       click: function(){
           if($(this).hasClass("formGray") || !currentConversation){
                event.preventDefault();
                event.stopPropagation();
            } else {
                $("#conversation_input").trigger("keypress", ["custom"]); 
            }
       }
    });
    $("#conversation_input").on({
        keypress:function (event, custom) {
            if($(this).hasClass("formGray") || !currentConversation){
                event.preventDefault();
                event.stopPropagation();
            } else {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (custom || (keycode == '13' && !shifted)) {
                    event.preventDefault();
                    event.stopPropagation();
                    if($(this).text().length !== 0) {
                        sendMessage(currentConversation, $(this).html());
                        $(this).empty();
                    }
                }
            }
        },
        focus:function(){
            if($(this).hasClass("formGray") || !currentConversation){
                event.preventDefault();
                event.stopPropagation();
                $(this).blur();
            } else {
                if ($(this).hasClass("default")) {
                    $(this).empty();
                    $(this).removeClass("default");
                }
            }
        },
        blur: function () {
            if($(this).text().length === 0){
                $(this).empty();
                $(this).addClass("default");
            }
        },
        paste: function (e) {
            e.preventDefault();
            const text = (e.originalEvent || e).clipboardData.getData('text/plain');
            window.document.execCommand('insertText', false, text);
        }
    });
    $(".scroll_top").on({
        click: function () {
            if($(this).hasClass("formGray") || !currentConversation) return;
            page = page+1;
            let oldForeigner = currentConversation;
            disableActions();
            $.ajax({
                url:"/display_messages",
                type: "POST",
                method: "POST",
                data: {"foreigner":currentConversation, page:page},
            }).done(function (data) {
                enableActions();
                if(currentConversation == oldForeigner){
                    if(data != false) {
                        $("#conversation_content").html(
                            data + $("#conversation_content").html()
                        );
                    } else {
                        $(".scroll_top").fadeOut({duration:200, queue:false});
                    }
                }
            });
        }
    });
}

function disableActions(){
    $("#conversation_input").addClass("formGray");
    $("#send").addClass("formGray");
    $(".conversation").each(function () {
        $(this).addClass("formGray");
    });
}

function enableActions(){
    $("#conversation_input").removeClass("formGray");
    $("#send").removeClass("formGray");
    $(".conversation").each(function () {
        $(this).removeClass("formGray");
    });
}