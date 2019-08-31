class OptionGenerator {

    constructor(ctn, input, realInput){
        this.$ctn = $(ctn);
        this.$input = $(input);
        this.$realInput = $(realInput);
        this._bind();
    }

    _bind(){
        this.$input.on("keydown", e => {
           if(e.keyCode === 13){
               e.preventDefault();
               e.stopPropagation();
               e.stopImmediatePropagation();
               this.addOption(this.$input.val());
               this.$input.val("");
           }
        });
    }

    enable(){
        this.$input.removeAttr("disabled");
        this.resetValues();
    }

    disable(){
        this.$input.attr("disabled", "true");
        this.resetValues();
    }

    addOption(option){
        if(!this.getOptions().includes(option)){
            if(this.getRealValue().trim() === ''){ //Vérifier si il est vide
                this.$realInput.val(option);
            } else {
                this.$realInput.val(this.getRealValue() + ";"+option);
            }
            this._displayOption(option);
        }
    }

    _displayOption(option){
        let $newOpt = $("<div class='debate-option'>"+option+"</div>");
        $newOpt.on("click", e => {
           this.removeOption(option);
        });
        this.$ctn.append($newOpt);
    }

    removeOption(option){
        $(".debate-option").each((index, opt) => {
            if($(opt).text() === option){
                $(opt).remove();
            }
        });
        let options = this.getOptions();
        for(let i = 0; i < options.length; i++){
            if(options[i] === option){
                options.splice(i,1);
            }
        }
        this.$realInput.val(options.join(";"));
    }

    getOptions(){
        const rv = this.getRealValue();
        return rv.split(";");
    }

    getRealValue(){
        return this.$realInput.val();
    }

    resetValues(){
        this.$realInput.val("");
        $(".debate-option").each((index, opt) => {
            $(opt).remove();
        });
    }

}