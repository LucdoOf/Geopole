$(document).ready(function () {
    displayNotifications(0);
});

function initEvents(){
    $("#content_pages li a").on({
        mousedown: function(e){
            e.preventDefault();
            e.stopPropagation();
            $("#content_pages_input").val($(this).html());
            displayNotifications($(this).html()-1)
        }
    });
    $("#body10 #notifications .notification .notification_viewed").on({
       mousedown: function (e) {
           e.preventDefault();
           e.stopPropagation();
           seeNotification($(this).data("id"));
           displayNotifications(0);
       }
    });
    $("#body10 #notifications .notification .notification_delete").on({
        mousedown: function (e) {
            e.preventDefault();
            e.stopPropagation();
            deleteNotification($(this).data("id"));
            displayNotifications(0);
        }
    });
}

function displayNotifications(page){
    let $notifications = $("#body10 #notifications");
    let data = {"page":page};
    $.ajax({
        url: "/display_notification_list",
        method: "POST",
        type: "POST",
        data: data,
        cache: false
    }).done(function (data) {
        $notifications.fadeOut(200, function () {
            $(document).find("#body10 #notifications").html(data).fadeIn(200);
            initEvents();
        });
    });
}