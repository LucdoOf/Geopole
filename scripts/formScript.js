//----------------------------------------FORM NUMBER-------------------------------------------//

function initFormNumberElements(){
	$(".formNumber").prepend(
		$("<div />", {
			class: "formNumber_container"
		}).prepend(
			$("<div />", {
				class: "formNumber_image"
			}),
			$("<input />", {
				class: "formNumber_input",
				type: "number",
				name: "formNumber",
				value: 0,
				style: "opacity: 0;"
			}),
			$("<div />", {
				class: "formNumber_parameters",
				style: "opacity: 0;"
			}).prepend(
				$("<div class='formNumber_increment'></div>", {
					class: "formNumber_increment"
				}),
				$("<div class='formNumber_decrement'></div>", {
					class: "formNumber_decrement"
				})
			)
		)
	);	

  $(".formNumber input").each(function(){
    $(this).attr("value", $(this).parent().parent().data("defaultValue"));
    $(this).attr("min", $(this).parent().parent().data("minValue"));
    $(this).attr("max", $(this).parent().parent().data("maxValue"));
  });
}

function initFormNumberFunctions(){

  function increment(target){
    if(target.val() < target.parent().parent().data("maxValue")){
        target.val(parseInt(target.val())+1);
    }
    var event = jQuery.Event("change");
    event.value = target.val();
    target.trigger(event);
  }
  
  function decrement(target){
    if(target.val() > target.parent().parent().data("minValue")){
          target.val(parseInt(target.val())-1);
    }
    var event = jQuery.Event("change");
    event.value = target.val();
    target.trigger(event);
  }
  
  $(".formNumber_container").each(function(){
    var instance = $(this);
    $(this).on({
      mouseenter: function(){
        instance.find(".formNumber_input, .formNumber_parameters").animate({
          opacity: 1
        }, {
          duration: 200,
          queue: false
        });
        
        instance.find(".formNumber_image").animate({
          top: "-100%"
        }, {
          duration: 200,
          queue: false
        })
      },
      
      mouseleave: function(){
        instance.find(".formNumber_input, .formNumber_parameters").animate({
          opacity: 0
        }, {
          duration: 200,
          queue: false
        });
        
        instance.find(".formNumber_image").animate({
          top: "0%"
        }, {
          duration: 200,
          queue: false
        })
      },    
      
      mousedown: function($event){
        $event.preventDefault();
      },
      
      mousewheel: function($event){
        $event.preventDefault();
        var target = $(this).find(".formNumber_input");
        if($event.originalEvent.wheelDelta /120 > 0){
          increment(target);
        } else {
          decrement(target);
        }
      }
    });
  });

  $(".formNumber_input").on({
    change: function(){
      $(this).parents().find(".formNumber").trigger("change");
    }
  })
  
  $(".formNumber_increment").on({
      mousedown: function(){
        var target = $(this).parents(".formNumber_container").find(".formNumber_input");
        increment(target);
      },
  });
  
  $(".formNumber_decrement").on({
      mousedown: function(){
        var target = $(this).parents(".formNumber_container").find(".formNumber_input");
        decrement(target);
      }
  });
}


//----------------------------------------FORM COLOR PICKER-------------------------------------//

class CustomColorPicker {

  constructor($parent, initColor){
    var instance = this;
    if(!initColor){ initColor = "#000000" }
    this.$parent = $parent;
    $parent.prepend($("<div />", { class: "formColor_palette"} ));
    $parent.prepend($("<div />", { class: "formColor_render" , style: "background-color: rgb(00, 12, 13);"}));
    this.$container = $parent.prepend($("<div />", { class: "formColor_container" }).prepend(
      $("<input />", { class: "formColor_red", type: "range", min: "0", max: "255", value: parseInt(CustomColorPicker.getPigment(initColor, 0),16)}).on({
        input: function(){ instance.updateRender() },
      }),
      $("<input />", { class: "formColor_green", type: "range", min: "0", max: "255", value: parseInt(CustomColorPicker.getPigment(initColor, 1),16)}).on({
        input: function(){ instance.updateRender() }
      }),
      $("<input />", { class: "formColor_blue", type: "range", min: "0", max: "255", value: parseInt(CustomColorPicker.getPigment(initColor, 2),16)}).on({
        input: function(){ instance.updateRender() }
      })
    ));

    this.palette = [];
    this.defaultPalette = ["#000000", "#404040", "#FF0000", "#FF6A00", "#FFD800", "#B6FF00", "#4CFF00", "#00FF21",
                           "#00FF90", "#00FFFF", "#0094FF", "#0026FF", "#4800FF", "#B200FF", "#FF00DC", "#FF006E"];
    this.palette = this.defaultPalette;

    this.$palette = $parent.find(".formColor_palette");
    this.$render = $parent.find(".formColor_render");
    this.$redInput = $parent.find(".formColor_red");
    this.$greenInput = $parent.find(".formColor_green");
    this.$blueInput = $parent.find(".formColor_blue");

    this.palette.forEach(function(value){
      instance.$palette.append($("<div />", { class: "formColor_color", style: "background-color: " + value}).on({
        mousedown: function(event){
          event.preventDefault();
          instance.updateInput(value);
        }
      }))
    });
    $parent.find(".formColor_color").each(function(index){
      $(this).css("background-color", instance.palette[index]);
    });
    this.$container.find($("input")).on({

      mousedown: function(ev, clientX){
        var $div = $(ev.target);
        var $display = $div.find('.display');

        var offset = $div.offset();
        var maxX = parseInt($div.css("width"));
        var x = ev.clientX ? ev.clientX - offset.left : clientX - offset.left;
        var maxVal = $div.attr("max");

        $div.val((x*maxVal)/maxX);
        instance.updateRender(clientX ? true : false);
        ev.preventDefault();
      }, 

      mousemove: function(e){
        var button = e.originalEvent["buttons"];
        if(button == 1){
          $(this).trigger("mousedown", e.clientX);
        }
      }
    });
    $parent.on({
      mousemove: function(e){
        var button = e.originalEvent["buttons"];
        if(button == 1 && !$(e.target).is("input") && !$(e.target).hasClass("formColor_color")){
          $(this).css("left", parseInt($(this).css("left")) + e.originalEvent["movementX"]);
          $(this).css("top", parseInt($(this).css("top")) + e.originalEvent["movementY"]);
        }
      }
    });
    this.updateRender();
  }

  updateInput(color, instant){
    var instance = this;

     instance.$parent.parent().trigger("change", [parseInt(CustomColorPicker.getPigment(color, 0), 16), parseInt(CustomColorPicker.getPigment(color, 1), 16), parseInt(CustomColorPicker.getPigment(color, 2), 16)]);

    $({red: instance.$redInput.val(), green: instance.$greenInput.val(), blue: instance.$blueInput.val()}).animate({
      red: parseInt(CustomColorPicker.getPigment(color, 0), 16),
      green: parseInt(CustomColorPicker.getPigment(color, 1), 16),
      blue: parseInt(CustomColorPicker.getPigment(color, 2), 16)
    }, {
      duration: instant ? 0 : 200,
      queue: false,
      step: function(){
        instance.$redInput.val(this.red);
        instance.$greenInput.val(this.green);
        instance.$blueInput.val(this.blue);
        instance.$render.css("background-color", "rgb(" + String(this.red) + ", " + String(this.green) + ", " + String(this.blue) + ")");
      }
    })
  }

  updateRender(instant){
    var instance = this;
    var actualColor = this.$render.css("background-color");

    instance.$parent.parent().trigger("change", [instance.$redInput.val(), instance.$greenInput.val(), instance.$blueInput.val()]);

    $({red: CustomColorPicker.getPigment(actualColor, 0),
     green: CustomColorPicker.getPigment(actualColor, 1),
     blue: CustomColorPicker.getPigment(actualColor, 2)}).animate({
      red: instance.$redInput.val(),
      green: instance.$greenInput.val(),
      blue: instance.$blueInput.val()
    }, {
      duration: instant ? 0 : 200,
      queue: false,
      step: function(){ 
        instance.$render.css("background-color", "rgb(" + String(this.red) + ", " + String(this.green) + ", " + String(this.blue) + ")");
      }, 
      complete: function(){
         instance.value = [this.red, this.green, this.blue];
         instance.$render.css("background-color", "rgb(" + String(this.red) + ", " + String(this.green) + ", " + String(this.blue) + ")");
      }
    });
  }

  static getPigment(color, pigmentIndex){
    if(!color.includes("rgb")){
      if(!color.includes("#")){
        var formatedColor = color.split("0x")[1];
        return formatedColor.substr(pigmentIndex*2, 2);
      } else {
        var formatedColor = color.split("#")[1];
        return formatedColor.substr(pigmentIndex*2, 2);
      }
    } else {
      if(color.includes("rgba")){
        var formatedColor = color.split("rgba(")[1];
        formatedColor = formatedColor.substr(0, formatedColor.length-1);
        return formatedColor.split(", ")[pigmentIndex];
      } else {
        var formatedColor = color.split("rgb(")[1];
        formatedColor = formatedColor.substr(0, formatedColor.length-1);
        return formatedColor.split(", ")[pigmentIndex];
      }
    }
  }

}

function initFormColorElementsAndFunctions(){
  $(".formColor").each(function(){
    var customColorPicker = new CustomColorPicker($(this), $(this).data("defaultColor"));
  });
}

//--------------------------------------FORM SELECT---------------------------------------------//

class CustomSelect {

  constructor($parent){
    var instance = this;
    this.$parent = $parent;
    this.$select = $parent.find("select");
    $parent.append($("<div/>", { class: "formSelect_selected" }));
    $parent.append($("<div/>", { class: "formSelect_optionsDiv" }));

    this.$selected = $parent.find(".formSelect_selected");
    this.$optionsDiv = $parent.find(".formSelect_optionsDiv");

    if($parent.data("width")){
      this.$selected.css("width", $parent.data("width"));
    }

    $parent.find("option").each(function(){
      instance.$optionsDiv.append($("<div/>", { class: "formSelect_option formSelect_option_hided", value: $(this).attr("value")}).html($(this).html()).css("width", $parent.data("width")));
    });

    this.$selected.on({
      mousedown: function(event){
        if($parent.hasClass("formGray")) return;
        event.preventDefault();
        instance.roll();
      }
    });

    this.$optionsDiv.find(".formSelect_option").each(function(index){
      $(this).on({
        mousedown: function(event){
          if($parent.hasClass("formGray")) return;
          event.preventDefault();
          instance.select(index);
          instance.roll();
        }
      })
    });

    this.select(0);
  }

  select(index){
    this.$selected.html($(this.$optionsDiv.children()[index]).html());
    this.$select.prop('selectedIndex', index)
    this.$select.trigger("change");
  }

  roll(){
    this.$optionsDiv.find(".formSelect_option").each(function(index){
      if($(this).hasClass("formSelect_option_hided")){
        $(this).style("display", "flex", "important");
        $(this).animate({
          opacity:1,
          top:((100*index+100)-(1*index)) + "%" //POur lespace le 1* index
        }, {
          duration:200,
          queue: false,
          complete: function(){
            $(this).toggleClass("formSelect_option_hided");
          }
        });
      } else {
        $(this).animate({
          opacity:0,
          top:"100%"
        }, {
          duration:200,
          queue: false,
          complete: function(){
            $(this).style("display", "none", "important");
            $(this).toggleClass("formSelect_option_hided");
          }
        });
      }
    });
  }

}

function initFormSelectElementsAndFunctions(){
  $(".formSelect").each(function(){
    var customSelect = new CustomSelect($(this));  
  });
}

//---------------------------------------FORM CHECKBOX------------------------------------------//


function getTextWidth(text, font) {
    // re-use canvas object for better performance
    var canvas = getTextWidth.canvas || (getTextWidth.canvas = document.createElement("canvas"));
    var context = canvas.getContext("2d");
    context.font = font;
    var metrics = context.measureText(text);
    return metrics.width;
}

class CustomCheckboxField {

  constructor($parent){
    var instance = this;
    this.$parent = $parent;

    $parent.find("input").wrap($("<div />", { class: "formCheckbox_container" })).toggleClass("formCheckbox_hided");
    $parent.find("input").parent().append($("<span />", { class: "formCheckbox" }));
    $parent.find("label").each(function(index){
      $(this).appendTo($parent.find(".formCheckbox_container").eq(index));
    });

    $parent.css("height", $parent.find(".formCheckbox").length*(32+10)+10 + "px");

    $parent.find(".formCheckbox").on({
      mousedown: function(){
        if($(this).parent().hasClass("formGray")) return;
        $(this).parent().find("input").prop("checked", $(this).parent().find("input").prop("checked") ? false : true);
        $(this).parent().find("input").trigger("change");
        var goal = $(this).parent().find("input").prop("checked") ? Math.round(getTextWidth($(this).parent().find("label").html(), "normal 19pt calibri")) : 0;
        $(this).parent().find("label").animate({
          'width': goal + "px",
        }, {
          duration: 300,
          easing: 'easeOutQuint',
          step: function(){
            $(this).css("overflow", "visible");
          }
        });
      }
    });
  }

}

function initFormCheckboxElementsAndFunctions(){
  $(".formCheckboxField").each(function(){
    var customCheckboxField = new CustomCheckboxField($(this));
  });
}

//---------------------------------------FORM RATING--------------------------------------------//

class CustomRating {

  constructor($parent){
    var instance = this;
    this.$parent = $parent;

    this.$select = $parent.find("input");
    this.$select.attr("min", 0);
    this.$select.attr("max", 5);
    if(!this.$select.attr("value")){
      this.$select.attr("value", 0);
    }
    this.cancelZero = this.$parent.data("cancelzero");
    console.log(this.cancelZero);

    for (let i = 0; i < 5; i++){
      var classToAdd = "formRating_star";
      if(i < instance.$select.attr("value")){
        classToAdd = classToAdd + " formRating_starOn";
      }
      this.$parent.append($("<span />", { class: classToAdd }).on({
        mousedown: function(){
          if(instance.$parent.hasClass("formGray")) return false;
          if(i == 0 && instance.$select.val() == 1){
            if(instance.cancelZero) return false;
            instance.$select.val(0);
            instance.$parent.find(".formRating_star").removeClass("formRating_starOn");
          } else {
            for (var j = 0; j < 5; j++){
              instance.$parent.find(".formRating_star").eq(j).removeClass("formRating_starOn");
            }
            for (var j = 0; j < i+1 ; j++){
              instance.$parent.find(".formRating_star").eq(j).toggleClass("formRating_starOn");
            }
            instance.$select.val(i+1);
          }
          instance.$select.trigger("change");
        }
      }));
    }

  }

}

function initFormRatingElementsAndFunctions(){
  $(".formRating").each(function(){
    var customRating = new CustomRating($(this));
  });
}

//--------------------------------------FORM CALENDAR-------------------------------------------//

class CustomCalendar {

  constructor($parent){
    var instance = this;
    this.$parent = $parent;
    this.$month = $parent.find(".formCalendar_selectMonth");
    this.$year = $parent.find(".formCalendar_selectYear");
    this.$day = $parent.find(".formCalendar_selectDay");

    this.months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre"];
    this.days = ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"]; //0 = DIMANCHE DANS GET DAY :)

    this.$titleContainer = $parent.append($("<div />", { class: "titleContainer" })).find(".titleContainer");
    this.$leftButton = this.$titleContainer.append($("<div />", { class: "leftButton" })).find(".leftButton");
    this.$title = this.$titleContainer.append($("<div />", { class: "title" })).find(".title");
    this.$rightButton = this.$titleContainer.append($("<div />", { class: "rightButton" })).find(".rightButton");
    this.$dayTitle = $parent.append($("<div />", { class: "dayTitle" })).find(".dayTitle");
    this.$firstWeek = $parent.append($("<div/>", { class: "week" })).find(".week").eq(1);
    this.$secondWeek = $parent.append($("<div/>", { class: "week" })).find(".week").eq(2);
    this.$thirdWeek = $parent.append($("<div/>", { class: "week" })).find(".week").eq(3);
    this.$fourthWeek = $parent.append($("<div/>", { class: "week" })).find(".week").eq(4);
    this.$fifthWeek = $parent.append($("<div/>", { class: "week" })).find(".week").eq(5);

    this.$rightButton.on({
      mousedown: function(){
        if(instance.isGray()) return false; 
        var newMonth = instance.month === 11 ? 0 : instance.month+1;
        var newYear = instance.month === 11 ? instance.year+1 : instance.year;
        instance.display(newMonth, newYear);
      }
    });
    this.$leftButton.on({
      mousedown: function(){
        if(instance.isGray()) return false; 
        var newMonth = instance.month === 0 ? 11 : instance.month-1;
        var newYear = instance.month === 0 ? instance.year-1 : instance.year;
        instance.display(newMonth, newYear);
      }
    });

    for(var i = 0; i < 7; i++){ instance.$dayTitle.append($("<span/>", { class: "formCalendar_titleDay" })); }
    for(var i = 1; i < 38; i++){ instance.$parent.find(".week").eq(Math.floor((i-1)/7)).append($("<span/>", { class: "formCalendar_day" })); }
    
    var date = new Date(Date.now());
    this.display(date.getMonth(), date.getFullYear(), date.getDate());
  }

  display(month, year, day){
    var instance = this;
    this.month = month;
    this.year = year;
    this.day = day ? day : 1;

    this.$month.val(this.month);
    this.$year.val(this.year);
    this.$day.val(this.day);

    var date = new Date(year, month, 1);

    this.$title.text(this.months[month] + " " + year);

    for(var i = date.getDay(); i < date.getDay()+7; i++){
      var day = i < 7 ? i : i - 7;
      this.$dayTitle.find(".formCalendar_titleDay").eq((i - date.getDay())).html(this.days[day]);
    }

    for(var i = 1; i < 38; i++){
      var increment = i == 1 ? i : date.getDate() + 1; //Obligé de faire date + 1 pour les cas de depassement (etrange)
      date.setDate(increment);
      var classToAdd = date.getMonth() === instance.month && date.getFullYear() === instance.year ? 'formCalendar_day' : 'formCalendar_day formCalendar_otherMonthDay'
      let newDate = new Date(date);
      instance.$parent.find(".formCalendar_day").eq(i-1).html(date.getDate()).attr("class", classToAdd).off("mousedown").on({ //On utilite attr pour supprimer toutes les anciennes
         mousedown: function(){
          if(instance.isGray()) return false; 
          instance.$month.val(newDate.getMonth());
          instance.$year.val(newDate.getFullYear());
          instance.$day.val(newDate.getDate());
          instance.$parent.find(".formCalendar_day").removeClass("formCalendar_focusDay");
          $(this).toggleClass("formCalendar_focusDay");
          instance.$parent.trigger("change");
        }
      });
    }
    instance.$parent.trigger("change");
    instance.$parent.find(".formCalendar_day").eq(this.day-1).attr("class", "formCalendar_day formCalendar_focusDay");
  }

  isGray(){
    return this.$parent.hasClass("formGray");
  }

}

function initFormCalendarElementsAndFunctions(){
  $(".formCalendar").each(function(){
    var customCalendar = new CustomCalendar($(this));
  });
}

//--------------------------------------FORM INPUT-------------------------------------------//

class CustomInput {

  constructor($parent, tag, list){
    var instance = this;
    this.$parent = $parent;
    this.$div = $parent.find("div");
    this.length = this.$div.data("length");
    this.default = this.$div.data("default");
    this.message = this.$div.data("message");
    this.tag = tag;
    this.list = list;
    this.selectedCompletion = 0;

    this.$div.css("color", "#B2B2B2");
    this.$div.html(this.default);
    this.$completionDiv = this.$parent.append($("<div/>", { class: "formInput_completionDiv" })).find(".formInput_completionDiv");
    if(this.message){
      this.$div.wrap($("<div/>", { class: "formInput_header" }));
      this.$parent.find(".formInput_header").append($("<p/>", { class: "formInput_message" }));
      this.$message = this.$parent.find(".formInput_message");
      instance.$message.text(instance.message);
    }

    this.$div.on({
      input: function(){
        if(instance.tag){
          instance.$completionDiv.empty();
          if($(this).html() == "&nbsp;" || $(this).html() == "" || $(this).html() == null) return false;
          for(let i = 0; i < instance.list.length; i++){
            if(list[i].startsWith($(this).html()) && instance.$completionDiv.find(".formInput_completion").length < 4 && list[i] != $(this).html()){
              instance.$completionDiv.append($("<div class='formInput_completion'>" + list[i] + "</div>").on({
                mousedown: function(){
                  instance.$div.html(list[i]);
                  instance.$div.trigger("input");
                  instance.$div.trigger("keydown", true);
                }
              }));
              instance.$completionDiv.find(".formInput_completion").eq(instance.selectedCompletion).addClass("formInput_selectedCompletion");
            }
          }
        } else {
          instance.$parent.find("input").val($(this).html());
        }
      },
      keydown: function(event, custom){
        if(($(this).text().length > instance.length && event.keyCode != 8) || !instance.tag && event.keyCode == 13){
          event.preventDefault();
          //return false; Si problèmes enlever le commentaire
        } 
        //TAG
        if(instance.tag && (event.keyCode == 32 || event.keyCode == 13 || custom)){
          if(instance.list.includes($(this).html())){
            instance.$parent.trigger("tag", [$(this).html()]);
            $(this).html("");
            instance.$div.trigger("input");
          } else if(event.keyCode == 13 && instance.$completionDiv.find(".formInput_completion").length > 0){
            $(this).html(instance.$completionDiv.find(".formInput_completion").eq(instance.selectedCompletion).html());
            instance.$div.trigger("keydown", true);
          }
          event.preventDefault();
          return false;
        }
        if(instance.tag && (event.keyCode == 40 || event.keyCode == 38)){
          event.preventDefault();
          if(event.keyCode == 40 && instance.selectedCompletion < instance.$completionDiv.find(".formInput_completion").length-1){
            instance.selectedCompletion = instance.selectedCompletion+1;
          } else if(event.keyCode == 38 &&  instance.selectedCompletion > 0){
            instance.selectedCompletion = instance.selectedCompletion-1;
          }
          instance.$div.trigger("input");
        } else {
          instance.selectedCompletion = 0;
        }

      },
      focus: function(){
        if($(this).html() == instance.default){
          $(this).html("");
        }
        instance.$div.css("color", "black");
        instance.$div.trigger("input");
        if(instance.message){
          instance.$message.animate({
            opacity: 1
          }, {
            duration: 100,
            queue: false
          });
        }
      },
      blur: function(){
        if(!/\S/.test($(this).html()) || $(this).html() == "&nbsp;"){
          instance.$div.css("color", "#B2B2B2");
          $(this).html(instance.default);
        }
        instance.$completionDiv.empty();
        if(instance.message){
          instance.$message.animate({
            opacity: 0
          }, {
            duration: 100,
            queue: false,
          });
        }
      }
    });

  }

}

function initFormInputElementsAndFunctions(){
  $(".formInput").each(function(){
    var customInput = new CustomInput($(this), $(this).data("tag"), $(this).data("list") ? $(this).data("list").split(";") : []);
  });
}

//--------------------------------------FORM PASSWORD-------------------------------------------//



class CustomPassword {

  constructor($parent, tag, list){
    var instance = this;
    this.$parent = $parent;
    this.default = $parent.data("default");
    this.message = $parent.data("message");
    this.$parent.append($("<span/>", { class:"default"}));
    this.$default = $parent.find("span");
    instance.$default.text(instance.default);

    if(instance.message){
      $parent.append($("<p/>", { class:"message" }));
    }
    this.$message = $parent.find(".message");
    instance.$message.text(instance.message);

    this.$parent.find("input").on({
      focus: function(){
        instance.$default.empty();
        $(this).trigger("input");
        if(instance.message){
          instance.$message.animate({
            opacity: 1
          }, {
            duration: 100,
            queue: false
          });
        }
      },
      blur: function(){
        if(!/\S/.test($(this).val()) || $(this).val() == "&nbsp;"){
          instance.$default.text(instance.default);
        }
        if(instance.message){
          instance.$message.animate({
            opacity: 0
          }, {
            duration: 100,
            queue: false,
          });
        }
      }
    });
    this.$parent.find(".default").on({
      click: function(){
        instance.$parent.find("input").trigger("focus");
      }
    });
  }

}



function initFormPasswordElementsAndFunctions(){
  $(".formPassword").each(function(){
    var customPassword = new CustomPassword($(this));
  });
}


$(document).ready(function(){

	initFormNumberElements();
	initFormNumberFunctions();

  initFormColorElementsAndFunctions();
	
  initFormSelectElementsAndFunctions();

  initFormCheckboxElementsAndFunctions();

  initFormRatingElementsAndFunctions();

  initFormCalendarElementsAndFunctions();

  initFormInputElementsAndFunctions();

  initFormPasswordElementsAndFunctions();
});
