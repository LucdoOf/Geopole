$(document).ready(function(){

    displayCategories();

    function initEvents(){
        $(".category").on({
            click: function(e){
                console.log("a");
                var instance = $(this);
                e.preventDefault();
                $.post(
                    "/manage_followed_tags",
                    {
                        type:"category",
                        id:$(this).data("id")
                    }
                ).done(function(data){
                    instance.fadeOut(200);
                });
            }
        });
    }

    $("#categories .formInput").on({
        tag:function(e, tag){
            var value = tag;
            if(tag == "") return;
            $.post(
                "/manage_followed_tags",
                {
                    type:"category",
                    name:value
                }).done(function(data){
                    console.log(data);
                    displayCategories();
                }
            );
        }
    });

    function displayCategories(){
        $.post(
            "/display_tags?type=followed_categories").done(function(data){
            $("#categories_content").fadeOut(200, function(){
                $("#categories_content").fadeIn(200);
                $("#categories_content").html(data);
                initEvents();
            });
        });
    }

});