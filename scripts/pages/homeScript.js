let offsetTop = $("#side-content").offset().top;
let $sideContent = $("#side-content");
let sideShowed = false;

$(document).ready(() => {
    document.dispatchEvent(new Event("scroll"));
});

$(document).on({
    scroll:function (e) {
        /**if(!sideShowed && offsetTop !== $("#side-content").offset().top) offsetTop = $("#side-content").offset().top;

        let scrollTop = $(this).scrollTop();
        let footerTop = $("footer").offset().top;

        if(scrollTop >= offsetTop && !sideShowed){
            sideShowed = true;
            $sideContent.addClass("expanded");
            $sideContent.css("top", "unset");
        } else if(scrollTop < offsetTop && sideShowed) {
            sideShowed = false;
            $sideContent.removeClass("expanded");
            $sideContent.css("bottom", 0);
            $sideContent.css("top", "40px");
        } else if(scrollTop >= offsetTop && sideShowed){
            if(scrollTop > footerTop-parseInt($sideContent.css("height"))){
                $sideContent.css("bottom", -(footerTop-scrollTop-parseInt($sideContent.css("height"))));
                $sideContent.css("top", "unset");
            } else {
                $sideContent.css("bottom", 0);
                $sideContent.css("top", 0);
            }
        }
         **/
    }
});