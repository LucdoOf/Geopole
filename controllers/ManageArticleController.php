<?php
class ManageArticleController
{

    public function __construct($url, $params)
    {
        if (isset($url) && count($url) > 2) {
            throw new Exception("Page introuvable");
        } else {
            if (!User::isConnected() || User::getConnectedUser()->getRank() < 2) {
                header("Location: /home");
            } else {
                if (isset($_POST["title"]) && strlen($_POST["title"]) <= 32 && strlen($_POST["title"]) > 0 && isset($_POST["description"]) && !empty($_POST["description"]) && isset($_POST["content"]) && !empty($_POST["content"]) && isset($_POST["categories"]) && !empty($_POST["categories"]) && isset($_POST["slug"]) && !empty($_POST["slug"]) && isset($_POST["cover"]) && !empty($_POST["cover"])) {
                    $categoriesExplode = explode(",", $_POST["categories"]);
                    $newCategories = [];
                    foreach ($categoriesExplode as $key => $categorieString) {
                        array_push($newCategories, Category::getByName($categorieString));
                    }
                    if (!isset($url[1])) {
                        $post = new Post(["title" => $_POST["title"],
                            "description" => $_POST["description"],
                            "content" => $_POST["content"],
                            "author" => User::getConnectedUser()->getId(),
                            "slug" => $_POST["slug"],
                            "cover" => $_POST["cover"]]);
                        $post->save();
                        foreach ($newCategories as $key => $category) {
                            $post->addCategory($category);
                        }
                        User::sendFollowNotifications($newCategories, $_POST["title"], $_POST["description"], "/articles/".$post->getId());
                        $uri = empty($post->getSlug()) ? $post->getId() : $post->getSlug();
                        TwitterManager::postArticle($post);
                        FacebookManager::postArticle($post);
                        header("Location: /articles/$uri");
                    } else {
                        if ((int)($url[1]) > 0) {
                            $post = new Post((int)($url[1]));
                            $post->setTitle($_POST["title"]);
                            $post->setDescription($_POST["description"]);
                            $post->setContent($_POST["content"]);
                            $post->setSlug($_POST["slug"]);
                            $post->setImage($_POST["cover"]);
                            $post->clearCategories();
                            foreach ($newCategories as $key => $category) {
                                $post->addCategory($category);
                            }
                            $post->save();
                            $uri = empty($post->getSlug()) ? $post->getId() : $post->getSlug();
                            header("Location: /articles/$uri");
                        } else {
                            throw new Exception("Impossible d'éditer l'article");
                        }
                    }
                    Sitemap::generate();
                } else {
                    $error = null;
                    if(isset($_POST["cover"]) && empty($_POST["cover"])) $error = "Veuillez choisir une image";
                    if(isset($_POST["slug"]) && empty($_POST["slug"])) $error = "Veuillez rentrez un slug";
                    if(isset($_POST["categories"]) && empty($_POST["categories"])) $error = "Veuillez rentrez au moins une catégorie";
                    if(isset($_POST["content"]) && empty($_POST["content"])) $error = "Rentrez un contenu";
                    if(isset($_POST["description"]) && empty($_POST["description"])) $error = "Rentrez une description";
                    if (isset($_POST["title"]) && (strlen($_POST["title"]) == 0 || strlen($_POST["title"]) > 32)) $error = "Rentrez un titre entre 0 et 32 caractères";
                    if (isset($url[1]) && (int)($url[1]) > 0) {
                        $this->editPost($url, $error, new Post($url[1]));
                    } else {
                        $this->createPost($url, $error);
                    }
                }
            }
        }
    }

    private function createPost($url, $error = NULL){
        $action = "/manage_article";
        require_once(APPLICATION_PATH."/views/dashboard/dashboard_manage_post.php");
    }

    private function editPost($url, $error = NULL, $post){
        $action = "/manage_article/".$post->getId();
        require_once(APPLICATION_PATH."/views/dashboard/dashboard_manage_post.php");
    }


}