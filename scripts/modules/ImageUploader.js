class ImageUploader {

    constructor(params) {
        this.params = params;
        let input = document.createElement("input");
        input.type = "file";
        input.className = "temp-image-uploader";
        input.dataset["type"] = $(".image-uploader")[0].dataset["type"];
        let realInput = document.createElement("input");
        realInput.type = "text";
        realInput.className = "temp-real-image-uploader";
        realInput.name = $(".image-uploader")[0].id;
        $(".image-uploader").append($(realInput));
        $(".image-uploader").append($(input));
        this.$uploader = $(".temp-image-uploader");
        this._bind();
    }

    _bind(){
        let ctx = this;
        this.$uploader.on({
           click: function(e) {
               e.stopImmediatePropagation();
               e.stopPropagation();
           },
           change: function () {
               let formData = new FormData();
               formData.append('file', $(this)[0].files[0]);
               $(".image-uploader").addClass("loading");
               $.ajax({
                   url : '/post/uploadImage?type='+$(this).data("type"),
                   type : 'POST',
                   data : formData,
                   processData: false,  // tell jQuery not to process the data
                   contentType: false,  // tell jQuery not to set contentType
               }).done(function (data) {
                    if(data.error){
                        PushTransmitter.pushError(data.error);
                        $(".temp-real-image-uploader").val("");
                        if(ctx.params && ctx.params.onerror) ctx.params.onerror(data.error);
                    } else if(data.path){
                        PushTransmitter.pushSuccess("Fichier uploadé avec succès");
                        ctx.setImage(data.path);
                        if(ctx.params && ctx.params.onsuccess) ctx.params.onsuccess(data.path);
                    } else {
                        console.log(data);
                        PushTransmitter.pushError("Une erreur est survenue, contactez le support");
                        $(".temp-real-image-uploader").val("");
                        if(ctx.params && ctx.params.onerror) ctx.params.onerror("Une erreur est survenue, contactez le support");
                    }
                    if(ctx.params && ctx.params.then) ctx.params.then(data);
                    $(".image-uploader").removeClass("empty");
                    $(".image-uploader").removeClass("loading");
               });
           }
        });
    }

    setImage(path){
        $(".image-uploader").removeClass("empty");
        $(".image-uploader").css("background-image","url("+path+")");
        $(".temp-real-image-uploader").val(path);
    }

    uploadImage() {
        this.$uploader.click();
    }

}