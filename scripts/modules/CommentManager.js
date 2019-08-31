class CommentManager {

    constructor(type, replyUrl, reactUrl) {
        this.type = type;
        this.replyUrl = replyUrl;
        this.reactUrl = reactUrl;
        this.bind();
    }

    bind() {
        let ctx = this;
        $(".response-anchor").each(function () {
            $(this).get(0).onclick = function () {
                let $parent = $(this).closest(".response,.comment");
                //let $parentSubResponse = $(this).closest(".sub-response");
                //let $parent = $parentSubResponse.length > 0 ? $parentSubResponse : $parentResponse;
                if (!$(this).hasClass("responding")) {
                    $(this).addClass("responding");
                    ctx.displayResponseForm($parent);
                } else {
                    $(this).removeClass("responding");
                    ctx.hideResponseForm($parent);
                }
            };
        });
        $(".like,.dislike").each(function () {
            $(this).get(0).onclick = function () {
                let $parentResponse = $(this).closest(".response,.comment");
                let $parentSubResponse = $(this).closest(".sub-response");
                let $parent = $parentSubResponse.length > 0 ? $parentSubResponse : $parentResponse;
                if ($parent === $parentSubResponse) {
                    ctx.reactSubResponse($parent, $(this).hasClass("like") ? "like" : "dislike", $(this).hasClass("pressed"));
                } else {
                    ctx.reactResponse($parent, $(this).hasClass("like") ? "like" : "dislike", $(this).hasClass("pressed"));
                }
            };
        });
    }

    displayResponseForm($parent){
        let $right = $parent.children(".right");
        let responseId = $parent.attr("data-id");
        let subAuthor = $parent.attr("data-author");
        let formStr = "<form class='reply-form column-wrapper' method='POST' action='"+this.replyUrl+"/reply/"+responseId+"'>";
        if($right.children(".sub-responses").length > 0){
            $right.children(".sub-responses").before(formStr);
        } else {
            $right.append(formStr);
        }
        let $replyDiv = $right.children(".reply-form");
        $replyDiv.append("<textarea class='textarea-input' name='content' type='text' placeholder='Entrez une réponse'>"+(subAuthor ? "#"+subAuthor+" " : "")+"</textarea>");
        $replyDiv.append("<input type='submit' class='button cta'>");
        $replyDiv.children("textarea").focus();
        Utils.moveCaretToEnd($replyDiv.children("textarea").get(0));
    }

    updateResponse($parent){
        let ctx = this;
        $parent.addClass("loading");
        let responseId = $parent.attr("data-id");
        $.post(
            "/send_data",
            {
                data: "parser",
                type: this.type,
                id: responseId
            }
        ).done(function (data) {
            $parent.replaceWith($(data));
            ctx.bind();
        });
    }

    reactResponse($parent, action, remove){
        if(Utils.isConnected()) {
            let ctx = this;
            let responseId = $parent.attr("data-id");
            $parent.addClass("loading");
            $.post(
                this.reactUrl + "/react/" + responseId,
                {
                    action: action,
                    remove: remove
                }
            ).done(function () {
                ctx.updateResponse($parent);
            });
        } else {
            PushTransmitter.pushError("Vous devez vous connecter pour réagir à un commentaire");
        }
    }

    hideResponseForm($parent){
        let $right = $parent.children(".right");
        let $replyDiv = $right.children(".reply-form");
        $replyDiv.remove();
    }

}
