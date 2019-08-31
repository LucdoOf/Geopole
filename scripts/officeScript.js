function styleCommand(nom, argument){
    if (typeof argument === 'undefined') {
        argument = '';
    }
    if(!$(".office_content").is(":focus")){
   		$(".office_content").focus();
   		$(".office_content").caretToEnd();
    }
	console.log(document.execCommand(nom, false, argument));
}

$(document).ready(function(){

$(".office").each(function(){
	var actionData = $(this).data("action");
	var actionText = $(this).data("action_text");
	$(this).prepend(
		$("<div />", {
			class: "office_parameters"
		}).prepend(
			$("<div />", {
				class: "office_fontStyle"
			}).prepend(
				$("<span />", {
					class: "office_bold",
					onclick: "styleCommand('bold');"
				}),
				$("<span />", {
					class: "office_italic",
					onclick: "styleCommand('italic');"
				}),
				$("<span />", {
					class: "office_underline",
					onclick: "styleCommand('underline');"
				}),
				$("<span />", {
					class: "office_bar",
					onclick: "styleCommand('strikeThrough');"
				}),
				$("<span />", {
					class: "office_exponent",
					onclick: "styleCommand('superscript');"
				}),
				$("<span />", {
					class: "office_index",
					onclick: "styleCommand('subscript');"
				})
			),

			$("<div />", {
				class: "office_fontPolicy formSelect"
			}).prepend(
				$("<select />", {
					name: "fontPolicy",
					onchange: "styleCommand('fontName', $('.office .office_fontPolicy select').val());"
				}).prepend(
					$("<option>Arial</option>", {
						value: "Arial"
					}),
					$("<option>Calibri</option>", {
						value: "Calibri"
					})
				)
			),

			$("<div />", {
				class: "office_fontSize formNumber"
			}).data("defaultValue", 3).data("minValue", 1).data("maxValue", 7),

			$("<div />", {
				class: "office_fontColor"
			}).prepend(
				$("<span />", {
					class: "office_highlight"
				}).prepend(
					$("<div />", {
						class: "formColor"
					}).data("defaultColor", "#f4f5f6")
				),
				$("<span />", {
					class: "office_color"
				}).prepend(
					$("<div />", {
						class: "formColor"
					}).data("defaultColor", "#07120a")
				)
			), 
			$("<div />", {
				class: "office_fontLink"
			}).prepend(
				$("<span />", {
					class: "office_fontLinkSpan",
					onclick: "styleCommand('createLink', $('.office .office_fontLink input').val());"
				}),
				$("<input />", {
					type: "text",
					class: "office_fontLinkInput"
				}),
				$("<span />", {
					class: "office_fontLinkSpan2",
					onclick: "styleCommand('unlink');"
				})
			),
			$("<div />", {
				class: "office_fontImage"
			}).prepend(
				$("<span />", {
					class: "office_fontImageSpan",
					onclick: "styleCommand('insertImage', $('.office .office_fontImage input').val());"
				}),
				$("<input />", {
					type: "text",
					class: "office_fontImageInput"
				})
			),
			$("<span />", {
				class: "office_unstyle",
				onclick: "styleCommand('removeFormat');"
			})
		),

		$("<form />", {
			method: "POST",
			action: actionData
		}).prepend(
			$("<input />", {
				type: "hidden",
				name: "content"
			}),
			$("<div />", {
				id: "test",
				class: "office_content",
				contentEditable: true
			}).on({
				input: function(){
					$(this).parent().find("input[type='hidden']").val($(this).html());
				}
			}),
			$("<input />", {
				class: "formButton",
				type: "submit",
				value: actionText ? actionText : "Valider"
			}).click(function(e){
				/*$.post("/Blog/index.php?action=dashboard",
				{
					content: $(".office_content").html()
				},  function(data, status){
	    			alert("Data: " + data + "\nStatus: " + status);
	  			});*/
			})
		)
	);
	if(!actionData){
		$(this).find(".formButton").css("display", "none");
	}
	$(this).find(".formColor").css("top", "100%").css("left", "0%").fadeOut(10);
});


$(".office_fontSize").on({
	change: function($event){
		styleCommand("fontSize", $event.value)
	}
})

$(".office").find("span").on({
	mousedown: function($event){
		$event.preventDefault();
	}
})

$(".office").each(function(){
	var officeInstance = $(this);
	$(this).find(".office_fontStyle").find("span").on({
		mousedown: function($event){
			if($(this).hasClass("office_exponent") && officeInstance.find(".office_index").hasClass("office_buttonDown") ||
			   $(this).hasClass("office_index") && officeInstance.find(".office_exponent").hasClass("office_buttonDown")){
				officeInstance.find(".office_exponent").toggleClass("office_buttonDown");
				officeInstance.find(".office_index").toggleClass("office_buttonDown");
			} else {
				$(this).toggleClass("office_buttonDown");
			}
		}
	})
});

$(".office").find(".office_color, .office_highlight").on({
	mousedown: function($event){
		if($($event.target).hasClass("office_color") || $($event.target).hasClass("office_highlight")){
			$(this).toggleClass("office_buttonDown_2");
			if($(this).hasClass("office_buttonDown_2")){
				$(this).find(".formColor").css("top", "100%").css("left", "0%").fadeIn(200);
				//$(this).find(".formColor").css("top", "100%").css("left", "0%").css("display", "flex");
			} else {
				//$(this).find(".formColor").css("display", "none");
				$(this).find(".formColor").css("top", "100%").css("left", "0%").fadeOut(200);
			}
		}
	},

	change: function($event, r, g, b){
		if($($event.target).hasClass("office_color")){
			styleCommand("foreColor", "rgb(" + r + "," + g + "," + b + ")");
		} else {
			styleCommand("hiliteColor", "rgb(" + r + "," + g + "," + b + ")");
		}
	}

});

$(".office").find(".office_fontLink span, .office_fontImage span, .office_unstyle").on({
	mousedown: function($event){
		$(this).toggleClass("office_buttonDown");
	},

	mouseup: function($event){
		$(this).toggleClass("office_buttonDown");
	}
});
});



