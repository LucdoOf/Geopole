class Modal {

    /**
     * Modal constructor
     * @param title String Titre du modal
     * @param content Object|String Contenu array ou string
     * @param cancelButton String titre du cancel
     * @param onConfirm function
     * @param onCancel function
     */
    constructor(title, content, cancelButton, onConfirm, onCancel){
        this.title = title;
        this.content = content;
        this.cancelButton = cancelButton;
        this.onConfirm = onConfirm;
        this.onCancel = onCancel;
        this.inputs = [];
        this.$container = $("#modal-container");
    }

    show(){
        let element = document.createElement("div");
        element.className = "modal";
        let title = document.createElement("p");
        title.className = "modal-title";
        title.innerText = this.title;

        let actions = document.createElement("div");
        actions.className = "modal-actions";
        let confirm = document.createElement("a");
        confirm.className = "modal-confirm button cta";
        confirm.innerText = "Valider";
        confirm.onclick = () => {
            this.onConfirm(this.inputs.map(input => input.value));
        };
        actions.appendChild(confirm);
        if(typeof this.cancelButton !== "undefined"){
            let cancel = document.createElement("a");
            cancel.innerText = this.cancelButton;
            cancel.className = "modal-cancel button red";
            if(typeof this.onCancel === "undefined"){
                cancel.onclick =  () => {
                    this.hide();
                }
            } else {
                cancel.onclick = () => {
                    this.onCancel();
                };
            }
            actions.appendChild(cancel);
        }

        let content = document.createElement("div");
        content.className = "modal-content";

        switch (typeof this.content) {
            case "object":
                for(let i = 0; i < this.content.length; i++){
                    let inputJSON = this.content[i];
                    let label = document.createElement("label");
                    label.className = "field";
                    label.innerText = inputJSON.name;
                    let input = document.createElement(inputJSON.type);
                    input.className = inputJSON.className + " modal-input";
                    this.inputs.push(input);
                    label.appendChild(input);
                    content.appendChild(label);
                }
                break;
            case "string":
                let p = document.createElement("p");
                content.appendChild(p);
                break;
        }
        element.appendChild(title);
        element.appendChild(content);
        element.appendChild(actions);
        this.$element = $(element);
        this.$container.addClass("visible");
        this.$container.innerHTML = "";
        this.$container.append($(element));
    }

    hide(){
        this.$container.removeClass("visible");
        this.$element.remove();
    }

}