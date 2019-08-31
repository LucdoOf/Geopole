<?php
class SearchController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            if(!isset($_POST["query"])) {
                $this->search($url, isset($params["query"]) ? urldecode($params["query"]) : "");
            } else {
                $query = $_POST["query"];
                $articlesEnabled = $_POST["articles"];
                $debatesEnabled = $_POST["debates"];
                $echo = [];
                if($articlesEnabled == "true"){
                    ob_start();
                        $articles = Post::search($query);
                        $this->displayArticles($articles);
                    $echo["articles"] = ob_get_clean();
                }
                if($debatesEnabled == "true"){
                    ob_start();
                        $debates = Debate::search($query);
                        $this->displayDebates($debates);
                    $echo["debates"] = ob_get_clean();
                }
                header('Content-Type: application/json');
                echo json_encode($echo);
            }
        }

    }

    private function search($url, $query){
        require_once(APPLICATION_PATH . "/views/search.php");
    }

    private function displayDebates($debates){
        foreach ($debates as $debate){
            DebateParser::parseSearch($debate);
        }
    }

    private function displayArticles($articles){
        foreach ($articles as $article){
            ArticleParser::parseSearch($article);
        }
    }

}