$(document).ready(function(){

	$(".formRating input").on({
		change: function(){
			var val = $(this).val();
			$.post(
			"/rate_post",
				{
					post_id: $("#body3").data("id"),
					rating: val
				}
			);
		}
	});

	$(".formGray").animate({
		opacity: 0.5
	}, 200);

	let confirmButton = $("submit-comment");
	let officeContent = $("#comment-content");
	let officeForm = $("#comment-form");
	let initialFormAction = officeForm.attr("action");

	$(".edit").on({
		click: function(e){
			e.preventDefault();
			e.stopPropagation();
			if($(this).hasClass("editGray")) return;
			let commentContent = $(this).parent().siblings().find(".comment_content");
			let newConfirmText;
			let newText;
			let newContentText;
			let actualEdit = $(this);
			if($(this).text() == "Annuler"){
				newText = "Editer";
				newConfirmText = "Valider";
				newContentText = "";
				$(".edit").each(function(){
					if(!$(this).is(actualEdit)){
						$(this).removeClass("editGray");
						$(this).animate({
							opacity: 1
						}, { duration: 200, queue: false});
					}
				});
				officeForm.attr("action", initialFormAction);
			} else {
				newText = "Annuler";
				newConfirmText = "Editer";
				newContentText = commentContent.text();
				$(".edit").each(function(){
					if(!$(this).is(actualEdit)){
						$(this).addClass("editGray");
						$(this).animate({
							opacity: 0.5
						}, { duration: 200, queue: false});
					}
				});
				officeForm.attr("action", "/manage_comment/edit?comment_id=" + commentContent.data("id"));
			}
			$(confirmButton).fadeOut(200, function(){
					confirmButton.val(newConfirmText);
					confirmButton.fadeIn(200);
			});
			$(this).fadeOut(200, function(){
				$(this).text(newText);
				$(this).fadeIn(200);
			});
			$(officeContent).fadeOut(200, function(){
				officeContent.text(newContentText);
				officeContent.fadeIn(200);
			});
			$('html,body').animate({scrollTop: $("#comment-content").offset().top-10}, { duration: 200, queue: false});
		}
	});

});