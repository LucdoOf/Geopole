<?php
/**
 * Class Notifier
 * Utilitaire recenssant les notifications envoyés aux utilisateurs
 */
class Notifier
{

    /**
     * Envoie une notification
     * @param $targetId int
     * @param $name string
     * @param $content string
     * @param $href string
     * @param $type string
     * @param $metadata string
     */
    private static function sendNotification($targetId, $name, $content, $href, $type, $metadata){
        $notification = new Notification();
        $notification->setTarget($targetId);
        $notification->setName($name);
        $notification->setContent($content);
        $notification->setHref($href);
        $notification->setType($type);
        $notification->setMetadata($metadata);
        $notification->save();
    }

    /**
     * Envoie une notification lors de l'envoie d'un sous commentaire a l'auteur du commentaire parent
     * @param $parentAuthor int Identifiant de l'auteur du commentaire parent
     * @param $subAuthorPseudo String Pseudo de l'auteur du sous commentaire
     * @param $subContent String Contenu du sous commentaire
     * @param $articleSlug String Slug de l'article pour la redirection
     */
    public static function articleSubComment($parentAuthor, $subAuthorPseudo, $subContent, $articleSlug){
        static::sendNotification(
            $parentAuthor,
            $subAuthorPseudo . " a répondu à votre commentaire !",
            $subContent,
            "/articles/".$articleSlug,
            "article-comment-subcomment",
            ""
        );
    }

    /**
     * Envoie une notification lors de l'envoie d'un commentaire au mentionné
     * @param $parentAuthor int Identifiant de l'auteur du commentaire parent
     * @param $subAuthorPseudo String Pseudo de l'auteur du sous commentaire
     * @param $subContent String Contenu du sous commentaire
     * @param $articleSlug String Slug de l'article pour la redirection
     */
    public static function articleCommentMention($parentAuthor, $subAuthorPseudo, $subContent, $articleSlug){
        static::sendNotification(
            $parentAuthor,
            $subAuthorPseudo . " vous a mentionné dans un commentaire",
            $subContent,
            "/articles/".$articleSlug,
            "article-comment-mention",
            ""
        );
    }

    /**
     * Envoie une notification lors du like d'un commentaire a l'auteur
     * @param $commentAuthor int Id de l'auteur du commentaire
     * @param $actionAuthorPseudo String Nom de l'utilisateur qui a réagi
     * @param $articleSlug String slug de l'article pour la redirection
     */
    public static function articleCommentLiked($commentAuthor, $actionAuthorPseudo, $articleSlug){
        static::sendNotification(
            $commentAuthor,
            $actionAuthorPseudo . " a aimé votre commentaire",
            "Allez vérifier les autres commentaires pour en débatre !",
            "/articles/" . $articleSlug,
            "article-comment-react",
            ""
        );
    }

    /**
     * Envoie une notification lors de l'envoie d'un sous commentaire a l'auteur du commentaire parent
     * @param $parentAuthor int Identifiant de l'auteur du commentaire parent
     * @param $subAuthorPseudo String Pseudo de l'auteur du sous commentaire
     * @param $subContent String Contenu du sous commentaire
     * @param $debateSlug String Slug du débat pour la redirection
     */
    public static function debateSubResponse($parentAuthor, $subAuthorPseudo, $subContent, $debateSlug){
        static::sendNotification(
            $parentAuthor,
            $subAuthorPseudo . " a répondu à votre réponse !",
            $subContent,
            "/debates/".$debateSlug,
            "debate-response-subresponse",
            ""
        );
    }

    /**
     * Envoie une notification lors de l'envoie d'un commentaire au mentionné
     * @param $parentAuthor int Identifiant de l'auteur du commentaire parent
     * @param $subAuthorPseudo String Pseudo de l'auteur du sous commentaire
     * @param $subContent String Contenu du sous commentaire
     * @param $debateSlug String Slug du débat pour la redirection
     */
    public static function debateResponseMention($parentAuthor, $subAuthorPseudo, $subContent, $debateSlug){
        static::sendNotification(
            $parentAuthor,
            $subAuthorPseudo . " vous a mentionné dans une réponse",
            $subContent,
            "/debates/".$debateSlug,
            "debate-response-mention",
            ""
        );
    }

    /**
     * Envoie une notification lors du like d'un commentaire a l'auteur
     * @param $commentAuthor int Id de l'auteur du commentaire
     * @param $actionAuthorPseudo String Nom de l'utilisateur qui a réagi
     * @param $debateSlug String slug du débat pour la redirection
     */
    public static function debateResponseLiked($commentAuthor, $actionAuthorPseudo, $debateSlug){
        static::sendNotification(
            $commentAuthor,
            $actionAuthorPseudo . " a aimé votre réponse",
            "Allez vérifier les autres réponses pour en débatre !",
            "/debates/" . $debateSlug,
            "debate-response-react",
            ""
        );
    }





}