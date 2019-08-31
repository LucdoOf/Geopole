$(document).ready(function(){

	displayCategories();

	function initEvents(){
		$(".category").on({
			click: function(e){
				let instance = $(this);
				e.preventDefault();
				$.post(
				"/manage_constants",
				{
					type:"category",
					id:$(this).data("id")
				}
				).done(function(){
					instance.fadeOut(200);
				});
			}
		});
	}

	$("#categories .text-input").on({
		keydown:function(e){
			if(e.keyCode == "13"){
				var value = $(this).val();
				if(value === "") return false;
				$(this).val("");
				$(this).html("");
				$.post(
				"/manage_constants",
				{
					type:"category",
					name:value
				}).done(function(){
					displayCategories();
				});			
			}
		}
	});

	function displayCategories(){
		$.post(
			"/display_tags?type=categories").done(function(data){
			$("#categories_content").fadeOut(200, function(){
				$("#categories_content").fadeIn(200);
				$("#categories_content").html(data);
				initEvents();
			});
		});
	}

});