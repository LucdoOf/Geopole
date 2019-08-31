function initializeContent(){

	$(".formInput").on({
		tag: function(event, tag){
			let instance = $(this);
			if(!getTagList($(this)).includes(tag)){
				$(this).parent().append($("<div class='tag'>" + tag + "</div>").on({
					mousedown: function(){
						$(this).remove();
						instance.find("input").val(getTagList(instance).join(","));
					}
				}));
				instance.find("input").val(getTagList(instance).join(","));
			} else {
				$(this).parent().find(".tag").each(function(){
					let instance = $(this);
					if($(this).html() == tag){
						$({opa: 0}).animate({
							opa: 1
						}, {
							duration: 200,
							queue: false,
							step: function(){
								instance.css("opacity", this.opa);
							}
						});
					}
				});
			}
		}
	});

	function getTagList($parent){
		var toReturn = [];
		$parent.parent().find(".tag").each(function(){
			toReturn.push($(this).html());
		});
		return toReturn;
	}

	//Initialisation edit 
	if($("#body6").data("post")){
		var post = $("#body6").data("post");
		$.post("/send_data", { data:"post", type:"title", post_id:post }).done(function(data){
			console.log(data);
			$("#title").val(data);
		});
		$.post("/send_data", { data:"post", type:"description", post_id:post }).done(function(data){
			$("#description").html(data);
		});
		$.post("/send_data", { data:"post", type:"slug", post_id:post }).done(function (data) {
			$("#slug").val(data);
		});
		$.post("/send_data", { data:"post", type:"content", post_id:post }).done(function(data){
			tinymce.get('content').setContent(data);
		});
		$.post("/send_data", { data:"post", type:"categories", post_id:post }).done(function(data){
			var dataArray = data.split(",");
			for (var i = 0; i < dataArray.length; i++) {
				if(dataArray[i] != ""){
					$("#categories .formInput").trigger("tag", dataArray[i]);
				}
			}
		});
		$.post("/send_data", { data:"post", "type":"image", post_id:post }).done(function (data) {
			console.log(data);
			if(data.trim() !== '' && data !== null) {
				imageUploader.setImage(data);
			}
		});
	}
}