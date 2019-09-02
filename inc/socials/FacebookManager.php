<?php

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphNode;

/**
 * Utilitaire gérant le compte FB
 * Class FacebookManager
 */
class FacebookManager
{

    /**
     * Créé une publication lors de la création d'un article
     * @param $article Post
     * @return GraphNode
     * @throws FacebookSDKException
     */
    public static function postArticle($article){
        $config = array();
        $config['app_id'] = FACEBOOK_APP_ID;
        $config['app_secret'] = FACEBOOK_APP_SECRET;
        $fb = new Facebook($config);
        // Returns a `FacebookFacebookResponse` object
        $response = $fb->post(
            '/' . FACEBOOK_PAGE_ID . '/feed',
            array (
                'message' => $article->getTitle() . "\n\n" . $article->getDescription(),
                'link' => ABSOLUTE_PATH . '/articles/'.$article->getSlug()
            ),
            FACEBOOK_ACC_TOKEN
        );
        $graphNode = $response->getGraphNode();
        return $graphNode;
    }


}