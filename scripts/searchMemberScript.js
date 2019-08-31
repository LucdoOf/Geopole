$(document).ready(function () {
   let timeout = null;
   $("#member-input").on({
       input: function (event) {
           if(timeout) clearTimeout(timeout);
           timeout = setTimeout(() => {
               let value = $(this).val();
               displayMembers(value, 0);
           }, 200);
       }
   });
});

function initPageEvent(){
    $("#content_pages li a").on({
        mousedown: function(e){
            e.preventDefault();
            e.stopPropagation();
            $("#content_pages_input").val($(this).html());
            displayMembers($("#member-input").val(), $(this).html()-1)
            $("html, body").animate({
                scrollTop: $("#content_right").offset().top
            });
        }
    });
}

function displayMembers(firstLetters, page){
    $.post( "/display_members", {
        firstLetters: firstLetters,
        page: page
    }).done(function (data) {
        $(document).find("#results").fadeOut(200, function () {
            $(document).find("#results").html(data).fadeIn(200);
            initPageEvent();
        });
    });
}