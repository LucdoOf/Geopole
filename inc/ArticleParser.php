<?php
/**
 * Utilitare de display d'articles
 * Class ArticleParser
 */
class ArticleParser
{

    /**
     * Affiche un article sous forme de une
     * @param $post Post
     * @displaying Title,First category, Description, Date, Author, Image
     */
    public static function parseUne($post){
        $categories = $post->getCategories();
        $category = empty($categories) ? new Category(0) : $categories[0];
        $uri = empty($post->getSlug()) ? $post->getId() : $post->getSlug();
        $date = $post->parseCreationDate(null, true, false);
        $author = new User($post->getAuthor());
        $authorName = $author->exist() ? $author->getPseudo() : "";
        $str = "
            <div class='article une'>
                <a href='/articles/$uri'>
                    <div class='content'>
                        <img src='".$post->getImage()."' alt='".$post->getSlug()."' onerror=\"this.onerror=null; this.src='/res/images/not-found.png'\"/>
                        <div class='text'>
                            <div class='header'>
                                <h3 class='title'>
                                    <span class='category'>".$category->getName()."</span>
                                    ".$post->getTitle()."
                                </h3>
                            </div>
                            <span class='description'>".$post->getDescription()."</span>
                            <span class='date'>Le $date par $authorName</span>
                        </div>
                    </div>
                </a>
            </div>
        ";
        echo $str;
    }

    /**
     * Affiche un article sous sa forme développée en ligne
     * @param $post Post
     * @displaying Title,First category, Description, Date, Image
     */
    public static function parseDevelopped($post){
        $uri = empty($post->getSlug()) ? $post->getId() : $post->getSlug();
        $categories = $post->getCategories();
        $category = empty($categories) ? new Category(0) : $categories[0];
        $date = $post->parseCreationDate(null,true,false);
        $author = new User($post->getAuthor());
        $authorName = $author->exist() ? $author->getPseudo() : "";
        $str = "
            <div class='article dvp'>
                <a href='/articles/$uri'>
                    <div class='content'>
                        <img src='".$post->getImage()."' alt='".$post->getSlug()."' onerror=\"this.onerror=null; this.src='/res/images/not-found.png'\"/>
                        <div class='text'>
                            <h3 class='title'>
                                <span class='category'>".$category->getName()."</span>
                                ".$post->getTitle()."
                            </h3>
                            <span class='description'>".$post->getDescription()."</span>
                            <span class='date'>Le $date par $authorName</span>
                        </div>
                    </div>
                </a>
            </div>
        ";
        echo $str;
    }

    /**
     * Affiche un article dans la liste des plus lus
     * @param $post Post
     */
    public static function parseTop($post){
        $uri = empty($post->getSlug()) ? $post->getId() : $post->getSlug();
        $str = "
            <div class='article top'>
                <a href='/articles/$uri'>
                    <div class='content'>
                        <img src='".$post->getImage()."' alt='".$post->getSlug()."' onerror=\"this.onerror=null; this.src='/res/images/not-found.png'\"/>
                        <div class='text'>
                            <h3 class='title'>
                                ".$post->getTitle()."
                            </h3>
                        </div>
                    </div>
                </a>
            </div>
        ";
        echo $str;
    }

    /**
     * Affiche un commentaire d'un article
     * @param $comment Comment
     * @param $subComments Comment[]
     */
    public static function parseComment($comment, $subComments){
        $dateStr = $comment->parseCreationDate(null,true,false);
        $author = new User($comment->getAuthor());
        $authorStr = $author->getPseudo();
        $connectedUser = User::getConnectedUser();
        $hasLiked = $connectedUser->exist() ? $comment->userHasReacted("like", $connectedUser) : false;
        $hasDisliked = $connectedUser->exist() ? $comment->userHasReacted("dislike", $connectedUser) : false;
        $likeClass = $hasLiked ? 'pressed' : '';
        $dislikeClass = $hasDisliked ? 'pressed' : '';
        $dataAuthor = $comment->getParent_id() > 0 ? "data-author='".$authorStr."'" : "";
        ob_start(); ?>
            <div class='comment' data-id='<?=$comment->getId()?>' <?= $dataAuthor ?>>
                <img class='profile-image' src='<?=$author->getProfile_pic()?>' alt='<?=$authorStr?>' onerror=\"this.onerror=null; this.src='/res/images/svg/user.svg'\">
                <div class='column-wrapper right'>
                    <span class='content'><?=$comment->getContent()?></span>
                    <div class='row-wrapper signature'>
                        <span class='author'>Par <?=$authorStr?></span>
                        <span class='date'>le <?=$dateStr?></span>
                    </div>
                    <div class='row-wrapper align footer'>
                        <span class='like <?=$likeClass?>'></span>
                        <span class='dislike <?=$dislikeClass?>'></span>
                        <a href='javascript:void(0)' class='response-anchor'>Répondre</a>    
                    </div>
                    <?php if(!empty($subComments)): ?>
                        <div class="sub-responses">
                            <span class='sub-responses-title'>Réponses</span>
                            <?php foreach ($subComments as $subComment){
                                self::parseComment($subComment, []);
                            } ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php $str = ob_get_clean();
        echo $str;
    }

    /**
     * Affiche un article en collonne cf listing article
     * @param $post Post
     * @displaying Title,Categories, Description, Date, Author, Image
     */
    public static function parseCol($post){
        $uri = empty($post->getSlug()) ? $post->getId() : $post->getSlug();
        $author = new User($post->getAuthor());
        $authorName = $author->exist() ? $author->getPseudo() : "";
        $date = $post->parseCreationDate(null,true,false);
        $messageCounter = count($post->getHeadingComments());
        $categories = $post->getCategories();
        $categoriesStr = empty($categories) ? "" : implode(", ",array_map(function ($category){
            return $category->getName();
        },$categories));
        $str = "
            <a href='/articles/$uri'>
                <div class='article col ctm-section'>
                    <img src='".$post->getImage()."' alt='".$post->getSlug()."' onerror=\"this.onerror=null; this.src='/res/images/not-found.png'\">
                    <div class='content'>
                        <div class='informations'>
                            <span>Par $authorName</span>
                            <span>Le $date</span>
                        </div>
                        <h2 class='title'>".$post->getTitle()."</h2>
                        <p class='description'>".$post->getDescription()."</p>
                    </div>
                    <div class='footer'>
                        <div class='separator'>
                            <hr>
                            <span class='message-count'>$messageCounter</span>
                        </div>
                        <div class='categories'>$categoriesStr</div>
                    </div>
                </div>
            </a>
        ";
        echo $str;
    }

    /**
     * Affiche un article sous sa forme minifié en carré
     * @param $post Post
     * @displaying Title, Image
     */
    public static function parseMin($post){
        $uri = empty($post->getSlug()) ? $post->getId() : $post->getSlug();
        $categories = $post->getCategories();
        $categoriesStr = empty($categories) ? "" : implode(", ",array_map(function ($category){
            return $category->getName();
        },$categories));
        $str = "
            <div class='article min'>
                <a href='/articles/$uri'>
                    <h3 class='title'>".$post->getTitle()."</h3>
                    <img src='".$post->getImage()."' alt='".$post->getSlug()."' onerror=\"this.onerror=null; this.src='/res/images/not-found.png'\"/>
                </a>
            </div>
        ";
        echo $str;
    }

    /**
     * Affiche un article sous une forme minimale avec mignature en rond
     * @param $post Post
     * @displaying Title,Categories
     */
    public static function parseRounded($post){
        $uri = empty($post->getSlug()) ? $post->getId() : $post->getSlug();
        $categories = $post->getCategories();
        $categoriesStr = empty($categories) ? "" : implode(", ",array_map(function ($category){
            return $category->getName();
        },$categories));
        $str = "
            <a href='/articles/$uri'>
                <article>
                    <img src='".$post->getImage()."' alt='".$post->getSlug()."' onerror=\"this.onerror=null; this.src='/res/images/not-found.png'\">
                    <div class='text'>
                        <span class='categories'>$categoriesStr</span>
                        <span class='title'>".$post->getTitle()."</span>
                    </div>
                </article>
            </a>
        ";
        echo $str;
    }

    /**
     * Affiche un article dans les résultats de recherche
     * @param $article Post
     */
    public static function parseSearch($article){
        $str = "
            <a href='/articles/'".$article->getSlug()."'>
                <span class='title'>".$article->getTitle()."</span>
            </a>
        ";
        echo $str;
    }

    /**
     * Affiche un commentaire dans la sidebar
     * @param $comment Comment
     */
    public static function parseSidebarComment($comment){
        $post = new Post($comment->getPost_id());
        $author = new User($comment->getAuthor());
        $countLike = $comment->countReactions("like");
        $str = "
            <div class='side-box'>
                <a href='/articles/".$post->getSlug()."'>
                    <div class='content'>
                        <span class='author'>".$author->getPseudo().": </span>
                        <span class='content'>".Str::cutStr($comment->getContent(), 200)."</span>
                    </div>
                    ";
                    if($countLike > 0) {
                        $str .= "
                        <div class='likes'>
                            <span data-count='$countLike' class='like pressed big'></span>
                        </div>
                    ";
                    }
                    $str.= "
                </a>
            </div>
        ";
        echo $str;
    }

}