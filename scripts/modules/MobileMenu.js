class MobileMenu {

    constructor() {
        this.$hamburger = $("#hamburger img");
        this.$menuCtn = $("#mobile-menu");
        this._bind();
        this.showed = false;
    }

    _bind(){
        this.$hamburger.on("click", () => {
            if(this.showed === true){
                this.hide();
            } else {
                this.show();
            }
        });
    }

    show(){
        this.showed = true;
        this.$hamburger.get(0).src = "/res/images/cross.svg";
        this.$menuCtn.addClass("expanded");
        this.$menuCtn.css("display","block");
        $("body").css("overflow-y", "hidden");
        this.$menuCtn.animate({
           width:"100%",
           opacity: 1
        }, {
            duration:600,
            queue: false,
            easing: "easeOutQuint"
        });
    }

    hide(){
        this.showed = false;
        this.$hamburger.get(0).src = "/res/images/hamburger.svg";
        this.$menuCtn.removeClass("expanded");
        $("body").css("overflow-y", "auto");
        this.$menuCtn.animate({
            width:"0",
            opacity: 0
        }, {
            duration:600,
            queue: false,
            easing: "easeOutQuint",
            complete: function () {
                if(this.showed === false) {
                    console.log("d");
                    $(this).css("display", "none");
                }
            }
        });
    }


}