$(document).ready(function () {
    checkForNotifications();
    initBaseNotificationActions();
    initNotificationsActions();
});

function getNotifications(){
    let datas = {"data":"notification", "type":"list"};
    let toReturn;
    $.ajax({
        url: "/send_data",
        method: "POST",
        type: "POST",
        data: datas,
        cache: false,
        async: false
    }).done(function (data) {
        toReturn = data;
    });
    return toReturn;
}

function checkForNotifications(){
    let $notifications = $("header #notifications").find(".notification");
    let data = getNotifications();
    let newNotificationList = String(data).split(";");
    let oldNotificationList = [];
    $notifications.each(function () {
       oldNotificationList.push($(this).data("id"));
    });
    let sameArrays = true;
    if(newNotificationList.length == oldNotificationList.length) {
        for (let i = 0; i < newNotificationList.length; i++) {
            if(newNotificationList[i] != oldNotificationList[i]) sameArrays = false;
        }
    } else sameArrays = false;
    if(!sameArrays){
        updateNotifications(data);
    }
    setTimeout(checkForNotifications, 5000);
}

function updateNotifications(newNotificationList){
    let $notifications = $("header #notifications");
    $.ajax({
        url: "/display_notifications",
        method: "POST",
        type: "POST",
        cache: false
    }).done(function (data) {
        $notifications.html(data);
        data = {"data":"notification","type":"count"};
        $.ajax({
            url: "/send_data",
            method: "POST",
            type: "POST",
            data: data,
            cache: false
        }).done(function (data) {
            if(data != 0) {
                $("header #notifications_global_container #notifications_counter").fadeIn({
                    duration: 200,
                    queue: false
                }).text(data);
            } else {
                $("header #notifications_global_container #notifications_counter").fadeOut({
                    duration: 200,
                    queue: false
                });
            }
        });
        initNotificationsActions();
    });
}

function seeNotification(notificationId){
    let data = {"notification_id":notificationId,"type":"seen"};
    $.ajax({
        url: "/manage_notification",
        method: "POST",
        type: "POST",
        data: data,
        async: false,
        cache: false
    }).done(function () {
        updateNotifications(getNotifications());
    });
}

function deleteNotification(notificationId){
    let data = {"notification_id":notificationId,"type":"delete"};
    $.ajax({
        url: "/manage_notification",
        method: "POST",
        type: "POST",
        data: data,
        async: false,
        cache: false
    }).done(function () {
        updateNotifications(getNotifications());
    });
}

let notificationsExpanded;

function initBaseNotificationActions(){
    let instance = $("header #notifications_global_container");
    $(document).click(function(event) {
        $target = $(event.target);
        if(!$target.closest('#header #notifications_global_container').length &&
            notificationsExpanded) {
            instance.click();
        }
    });
    instance.on({
        click: function (e) {
            e.stopPropagation(); //Sinon ça relance document.click
            if (notificationsExpanded) {
                notificationsExpanded = false;
                instance.find("#notifications").fadeOut({duration: 100, queue: false});
                instance.find("#connector").fadeOut({duration: 100, queue: false});
            } else {
                notificationsExpanded = true;
                instance.find("#notifications").fadeIn({
                    duration: 100, queue: false, complete: function () {
                        instance.find("#notifications").css("opacity", 1)
                    }
                });
                instance.find("#connector").fadeIn({
                    duration: 100, queue: false, complete: function () {
                        instance.find("#notifications").css("opacity", 1)
                    }
                });
            }
        }
    });
}

function initNotificationsActions(){
    let instance = $("header #notifications_global_container");
    instance.find(".notification").on({
       mousedown: function (event) {
            seeNotification($(this).data("id"));
       }
    });
}
