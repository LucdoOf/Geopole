$(document).ready(function(){
    $("#comments_expand").on({
        click: function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).css("display", "none");
            $(this).fadeIn(200);
            if($(this).text() == "Réduire"){
                $(document).find("#comments_content").animate({
                    maxHeight: "300px"
                });
                $(this).text("Etendre");
            } else {
                $(document).find("#comments_content").animate({
                    maxHeight: "1000px"
                },{
                    duration: 200,
                    complete: function () {
                        $(document).find("#comments_content").css("max-height", "none");
                    }
                });
                $(this).text("Réduire");
            }
        }
    });
});