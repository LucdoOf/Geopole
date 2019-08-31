<?php
class ArticlesController
{

    const ARTICLE_BY_PAGE = 20;

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 4){
            throw new Exception("Page introuvable", 404);
        } else {
            if(count($url) == 1) {
                $category = isset($params["category"]) ? new Category((int)$params["category"]) : null;
                $page = isset($params["page"]) ? $params["page"] : 0;
                if(is_null($category) || $category->exist()) {
                    $this->articleList($url, $params, $category, $page);
                } else {
                    throw new Exception("Categorie introuvable", 404);
                }
            } else {
                $slug = $url[1];
                $post = Post::getBySlug($slug);
                if($post->exist() && !empty($slug)){
                    if(count($url) == 4){
                        if(User::isConnected()) {
                            $connectedUser = User::getConnectedUser();
                            $comment = new Comment($url[3]);
                            if ($comment->exist()) {
                                if ($url[2] == "reply") {
                                    if(isset($_POST["content"]) && !empty($_POST["content"])) {
                                        $parent = $comment;
                                        if ($comment->getParent_id() > 0) {
                                            $parent = new Comment($comment->getParent_id());
                                        }
                                        Notifier::articleSubComment($comment->getAuthor(), $connectedUser->getPseudo(), $_POST["content"], $post->getSlug());
                                        if (Str::startsWith($_POST["content"], "#")) {
                                            $users = Str::extractUsersFromString($_POST["content"]);
                                            if (!empty($users) && $users[0]->getId() != $comment->getAuthor()) {
                                                Notifier::articleCommentMention($comment->getAuthor(), $connectedUser->getPseudo(), $_POST["content"], $post->getSlug());
                                            }
                                        }
                                        $parent->subComment($connectedUser->getId(), $_POST["content"]);
                                        header("Location: /articles/".$post->getSlug());
                                    } else {
                                        throw new Exception("Rentrez un message valide");
                                    }
                                } else if ($url[2] == "react") {
                                    if(isset($_POST["action"]) && !empty($_POST["action"])){
                                        if(isset($_POST["remove"]) && $_POST["remove"] == "true"){
                                            $comment->clearReactions($connectedUser);
                                        } else {
                                            if($_POST["action"] == "like" && $connectedUser->getId() != $comment->getAuthor()) {
                                                Notifier::articleCommentLiked($comment->getAuthor(), $connectedUser->getPseudo(), $post->getSlug());
                                            }
                                            $comment->react($connectedUser, $_POST["action"]);
                                        }
                                    } else {
                                        throw new Exception("Action invalide");
                                    }
                                } else {
                                    throw new Exception("Page introuvable (2)");
                                }
                            } else {
                                throw new Exception("Commentaire introuvable", 404);
                            }
                        } else {
                            throw new Exception("Vous devez être connecté", 400);
                        }
                    } else if(count($url) == 2) {
                        $this->article($url, $post);
                    }
                } else {
                    throw new Exception("Article introuvable", 404);
                }
            }
        }

    }

    private function article($url, $post){
        $post->setViewed($post->getViewed() + 1);
        $post->save();
        $category = $post->getCategories()[0];
        $comments = $post->getHeadingComments();
        require_once(APPLICATION_PATH . "/views/article.php");
    }

    private function articleList($url, $params, $category, $page){
        $articles = Post::getLastCreated(ArticlesController::ARTICLE_BY_PAGE, $category, $page*ArticlesController::ARTICLE_BY_PAGE);
        $counter = Post::countByCategory($category);
        require_once(APPLICATION_PATH . "/views/article_list.php");
    }
}