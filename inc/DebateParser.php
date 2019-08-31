<?php
/**
 * Utilitaire de parse de debat
 * Class DebateParser
 */
class DebateParser
{

    /**
     * Affiche une à un débat
     * @param $response DebateResponse
     * @param $subResponses DebateResponse[]
     */
    public static function parseResponse($response, $subResponses){
        $dateStr = $response->parseCreationDate();
        $author = new User($response->getAuthor());
        $authorStr = $author->getPseudo();
        $connectedUser = User::getConnectedUser();
        $option = new DebateOption($response->getOption_id());
        $index = $option->getIndex();
        $hasLiked = $connectedUser->exist() ? $response->userHasReacted("like", $connectedUser) : false;
        $hasDisliked = $connectedUser->exist() ? $response->userHasReacted("dislike", $connectedUser) : false;
        $likeClass = $hasLiked ? 'pressed' : '';
        $dislikeClass = $hasDisliked ? 'pressed' : '';
        $dataAuthor = $response->getParent_id() > 0 ? "data-author='".$author->getPseudo()."'" : "";
        ob_start(); ?>
        <div class='response index-<?=$index?>' data-id='<?=$response->getId()?>' <?= $dataAuthor ?>>
            <img class='profile-image' src='<?=$author->getProfile_pic()?>' alt='<?=$authorStr?>' onerror=\"this.onerror=null; this.src='/res/images/svg/user.svg'\">
            <div class='column-wrapper right'>
                <?php if(!empty($response->getTitle())): ?>
                    <span class="title"><?= $response->getTitle() ?></span>
                <?php endif; ?>
                <span class='content'><?=$response->getContent()?></span>
                <div class='row-wrapper signature'>
                    <span class='author'>Par <?=$authorStr?></span>
                    <span class='date'>le <?=$dateStr?></span>
                </div>
                <div class='row-wrapper align footer'>
                    <span class='like <?=$likeClass?>'></span>
                    <span class='dislike <?=$dislikeClass?>'></span>
                    <a href='javascript:void(0)' class='response-anchor'>Répondre</a>
                </div>
                <?php if(!empty($subResponses)): ?>
                    <div class="sub-responses">
                        <span class='sub-responses-title'>Réponses</span>
                        <?php foreach ($subResponses as $subResponse){
                            self::parseResponse($subResponse, []);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php $str = ob_get_clean();
        echo $str;
    }

    /**
     * Affiche un débat sur la sidebar
     * @param $debate Debate
     */
    public static function parseSidebar($debate){
        $author = new User($debate->getAuthor());
        $authorStr = $author->getPseudo();
        $dateStr = $debate->parseCreationDate();
        $commentCount = $debate->countResponses();
        $str = "
            <div class='side-box'>
                <a href='/debates/".$debate->getSlug()."'>
                    <div class='content'>
                        <h3 class='title'>".$debate->getTitle()."</h3>
                        <span class='date'>Par $authorStr le $dateStr</span>
                    </div>
                    <div class='footer'>
                        <span class='comment-count'>$commentCount</span>
                    </div>
                </a>
            </div>
        ";
        echo $str;
    }

    /**
     * Parse une réponse dans la sidebar de la page d'accueil
     * @param $response DebateResponse
     */
    public static function parseSidebarResponse($response){
        $user = new User($response->getAuthor());
        $debate = new Debate($response->getDebate_id());
        $option = new DebateOption($response->getOption_id());
        $str = "
            <div class='side-box'>
                <a href='/debates/".$debate->getSlug()."'>
                    <h3 class='title'>".$debate->getTitle()."</h3>
                    <div class='content'>
                        <span class='author'>".$user->getPseudo().": </span>
                        <span class='option'>".$option->getName()."</span>
                    </div>
                </a>
            </div>
        ";
        echo $str;
    }

    /**
     * Affiche un débat en cours d'approbation dans le dashboard
     * @param $debate Debate
     */
    public static function parsePending($debate){
        $options = $debate->getOptions();
        if(!empty($options)){
            $optionsStr = "";
            foreach ($options as $option){
                $optionsStr .= "<span class='response'>".$option->getName()."</span>";
            }
        } else {
            $optionsStr = "Pas d'options";
        }
        $str = "
            <a href='/pending_debates/".$debate->getId()."'>
                <div class='debate pending'>
                    <h3 class='title'>".$debate->getTitle()."</h3>
                    <div class='debate-content'>
                            <img class='image' src='".$debate->getSlug()."' alt='".$debate->getTitle()."' onerror=\"this.onerror=null; this.src='/res/images/not-found.png'\">
                            <div class='debate-right'>
                                <p class='description'>".$debate->getDescription()."</p>
                                <div class='options'>".$optionsStr."</div>
                            </div>
                    </div>
                </div>
            </a>
        ";
        echo $str;
    }

    /**
     * Affiche un débat dans la liste des débats
     * @param $debate Debate
     */
    public static function parseList($debate){
        $lastMessage = $debate->getHeadingResponses(1, 0);
        $authorObj = new User($debate->getAuthor());
        $author = $authorObj->getPseudo();
        $dateStr = $debate->parseCreationDate();
        $pinned = $debate->getPinned() == 1 ? "pinned" : "";
        if(!empty($lastMessage)){
            $lastAuthor = new User($lastMessage[0]->getAuthor());
            $lastMessageStr = "
                <span class='last-message'>
                    ".$lastMessage[0]->getTitle()."
                </span>
                <span class='author'>
                    Par ".$lastAuthor->getPseudo()."
                    le ".$lastMessage[0]->parseCreationDate()."
                </span>
            ";
        } else {
            $lastMessageStr = "&mdash;";
        }
        $str = "
            <a href='/debates/".$debate->getSlug()."' class='row debate $pinned'>
                <div class='left'>
                    <span class='title'>".$debate->getTitle()."</span>
                    <span class='author'>
                        Par $author le $dateStr
                    </span>
                </div>
                <div class='right'>
                    $lastMessageStr
                </div>
            </a>
        ";
        echo $str;
    }

    /**
     * Affiche un débat dans les résultats de recherche
     * @param $debate Debate
     */
    public static function parseSearch($debate){
        $str = "
            <a href='/debates/".$debate->getSlug()."'>
                <span class='title'>".$debate->getTitle()."</span>
            </a>
        ";
        echo $str;
    }

}