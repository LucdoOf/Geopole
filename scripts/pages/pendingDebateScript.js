$(document).ready(function () {
    $("#fake-options").on({
       keydown: function (e) {
           if(e.keyCode == "13"){
               e.preventDefault();
               e.stopPropagation();
               e.stopImmediatePropagation();
               if($(this).val().trim() === '') return false;
               $("#options-ctn").append("<span class='debate-option'>"+$(this).val().trim()+"</span>");
               $(this).val("");
               bindEvents();
           }
       }
    });
    $("#save-debate").on({
       click: function (e) {
           e.preventDefault();
           e.stopPropagation();
           e.stopImmediatePropagation();
           $(".debate-option").each((index,option) => {
               let $real = $("#real-options");
               if($real.val().trim() !== "") $real.val($real.val() + ";");
               $real.val($real.val() + option.innerText);
           });
           $("#pending-form").submit();
       } 
    });
    $("#delete-debate").on({
       click: function (e) {
           e.preventDefault();
           e.stopImmediatePropagation();
           e.stopPropagation();
           let modal = new Modal("Supprimer le débat",[
               {name:"Précisez un message pour l'auteur",type:"textarea",className:"textarea-input"}
           ],"Annuler",(values) => {
                let text = values[0];
                $("#pending-form").append($("<input type='text' id='cancel-input' name='cancel-text'>").val(text));
                $("#pending-form").submit();
           });
           modal.show();
       } 
    });
    function bindEvents() {
        $(".debate-option").each(($index,$debate) => {
           $debate.onclick = () => {
               $debate.remove();
           }
        });
    }
    bindEvents();
});