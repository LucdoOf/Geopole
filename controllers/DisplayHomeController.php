<?php
class DisplayHomeController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            switch ($_POST["type"]){
                case "last_articles":
                    $page = isset($_POST["page"]) ? $_POST["page"] : 0;
                    $this->displayLastArticles($page);
                    break;
                case "most_read_articles":
                    $top = isset($_POST["top"]) ? $_POST["top"] : 1;
                    $this->displayMostReadArticles($top);
                    break;
                case "category-articles":
                    $id = (isset($_POST["id"]) && (int)$_POST["id"] > 0) ? $_POST["id"] : null;
                    $this->displayCategoryArticles($id);
                    break;
            }
        }

    }

    private function displayMostReadArticles($top){
        $type = "most_read_articles";
        require_once(APPLICATION_PATH . "/views/displayers/display_home_articles.php");
    }

    private function displayLastArticles($page){
        $type = "last_articles";
        require_once(APPLICATION_PATH . "/views/displayers/display_home_articles.php");
    }

    private function displayCategoryArticles($id){
        $type = 'category-articles';
        require_once(APPLICATION_PATH . "/views/displayers/display_home_articles.php");
    }

}