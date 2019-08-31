<?php
/**
 * Générateur de sitemap
 * Class Sitemap
 */
class Sitemap
{

    /**
     * Créé un fichier XML
     */
    public static function generate(){
        $file = fopen(APPLICATION_PATH . "/sitemap.xml", "w");
        fwrite($file, self::buildContent());
        fclose($file);
    }

    /**
     * Construit le contenu du site
     * @return string
     */
    private static function buildContent(){
        $content = self::buildDefault();
        $content .= self::buildCategories();
        $content .= self::buildDebates();
        $content .= self::buildArticles();
        $content .= self::endDefault();
        return $content;
    }

    /**
     * Construit le contenu lié au articles
     * @return string
     */
    private static function buildArticles(){
        $xml = "";
        $path = ABSOLUTE_PATH;
        foreach (Post::getAll() as $post){
            $xml .= "
            <url>
                <loc>$path/articles/".$post->getSlug()."</loc>
                <changefreq>weekly</changefreq>
            </url>";
        }
        return $xml;
    }

    /**
     * Construit le contenu lié au page de listing articles
     * @return string
     */
    private static function buildCategories(){
        $xml = "";
        $path = ABSOLUTE_PATH;
        $articleByPage = ArticlesController::ARTICLE_BY_PAGE;
        foreach (Category::getAll() as $category){
            $posts = Post::countByCategory($category);
            $pageNumber = ceil($posts/$articleByPage);
            for($i = 0; $i < $pageNumber; $i++) {
                $xml .= "
                <url>
                    <loc>$path/articles?category=".$category->getId()."&amp;page=$i</loc>
                    <changefreq>weekly</changefreq>
                </url>";
            }
        }
        $posts = Post::count();
        $pageNumber = ceil($posts/$articleByPage);
        for($i = 0; $i < $pageNumber; $i++) {
            $xml .= "
                <url>
                    <loc>$path/articles?page=$i</loc>
                    <changefreq>weekly</changefreq>
                </url>";
        }
        return $xml;
    }

    /**
     * Construit le contenu lié au débats
     * @return string
     */
    private static function buildDebates(){
        $xml = "";
        $path = ABSOLUTE_PATH;
        $responseByPage = DebatesController::RESPONSES_BY_PAGE;
        foreach (Debate::getActives() as $debate){
            $responses = $debate->countResponses();
            $pageNumber = ceil($responses/$responseByPage);
            for($i = 0; $i < $pageNumber; $i++) {
                $xml .= "
                <url>
                    <loc>$path/debates/".$debate->getSlug()."?page=$i</loc>
                    <changefreq>weekly</changefreq>
                </url>";
            }
        }
        return $xml;
    }

    /**
     * Construit le contenu par défaut du sitemap
     * @return string
     */
    private static function buildDefault(){
       $xml = "<?xml version='1.0' encoding='UTF-8'?>";
       $path = ABSOLUTE_PATH;
       $xml .= "
       <urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'> 
           <url>
              <loc>$path/login</loc>
              <changefreq>yearly</changefreq>
           </url> 
           <url>
              <loc>$path/login/sign_up</loc>
              <changefreq>yearly</changefreq>
           </url> 
           <url>
              <loc>$path/home</loc>
              <changefreq>always</changefreq>
           </url>
           <url>
              <loc>$path/articles</loc>
              <changefreq>always</changefreq>   
           </url>
           <url>
              <loc>$path/contact</loc>
              <changefreq>yearly</changefreq> 
           </url>
           <url>
              <loc>$path/dashboard</loc>
              <changefreq>yearly</changefreq>
           </url>
           <url>
              <loc>$path/dashboard/follows</loc>
              <changefreq>yearly</changefreq>
           </url>
           <url>
              <loc>$path/dashboard/messages</loc>
              <changefreq>yearly</changefreq>
           </url>
           <url>
              <loc>$path/dashboard/search_member</loc>
              <changefreq>yearly</changefreq>
           </url>
           <url>
              <loc>$path/dashboard/notifications</loc>
              <changefreq>yearly</changefreq>
           </url>
           <url>
              <loc>$path/dashboard/profile</loc>
              <changefreq>yearly</changefreq>
           </url>
           <url>
              <loc>$path/search</loc>
              <changefreq>yearly</changefreq>
           </url>
           <url>
              <loc>$path/debates</loc>
              <changefreq>always</changefreq>   
           </url>";
       return $xml;
    }

    /**
     * Chaine de texte de fin de sitemap
     * @return string
     */
    private static function endDefault(){
        return "</urlset>";
    }

}