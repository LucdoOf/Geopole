<?php

use Codebird\Codebird;

/**
 * Utilitaire gérant le réseau social Twitter
 * Class TwitterManager
 */
class TwitterManager
{

    /**
     * Créé un tweet lors de la création d'un article
     * @param $article Post
     * @return mixed
     */
    public static function postArticle($article){
        Codebird::setConsumerKey(TWITTER_API_KEY, TWITTER_API_KEY_SECRET); // static, see README
        $cb = Codebird::getInstance();
        $cb->setToken(TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);
        $link = ABSOLUTE_PATH . "/articles/".$article->getSlug();
        $content = $article->getTitle() . "\n";
        $params = [
            'status' => $content.$link
        ];
        $reply = $cb->statuses_update($params);
        return $reply;
    }

}