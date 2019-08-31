class PushTransmitter {

    static sendPush(text, type){
        if(!type) type = "";
        let notification = document.createElement("div");
        notification.className = "push-notification " + type;
        notification.innerText = text;
        notification = $(notification);
        $("#push-notifications").append(notification);
        notification.animate({opacity:1});
        setTimeout(() => {
            this.disapear(notification);
        }, 10000);
        notification.on("click", () => {
            this.disapear(notification);
        });
    }

    static pushSuccess(text){
        this.sendPush(text, "success");
    }

    static pushError(text){
        this.sendPush(text, "error");
    }

    static disapear(notification){
        if(!$(notification).hasClass("disapearing")){
            $(notification).addClass("disapearing");
            $(notification).animate({opacity:0},{duration:200, queue:false,done:function () {
                    $(notification).remove();
                }
            });
        }
    }

}