<?php
/**
 * Utilitaire permettant de générer des données structurées
 * Class StructuredData
 */
class StructuredData
{

    /**
     * Génère des données structurées pour un article
     * @param $article Post
     */
    public static function generateArticle($article){
        try {
            $published = $article->creationDate();
            $published = $published->format(DateTime::ATOM);
            $authorObj = new User($article->getAuthor());
            $author = $authorObj->getPseudo();
            $str =
                '<script type="application/ld+json">
                { 
                    "@context":"https://schema.org",
                    "type":"Article",
                    "mainEntityOfPage":{
                        "@type":"WebPage",
                        "@id":"' . ABSOLUTE_PATH . "/articles/" . $article->getSlug() . '"
                    },
                    "headline":"' . $article->getTitle() . '",
                    "image":[
                        "' . ABSOLUTE_PATH . $article->getImage(). '"
                    ],
                    "dateModified":"' . $published . '",
                    "datePublished":"' . $published .'",
                    "author":{
                        "@type":"Person",
                        "name":"' . $author . '"
                    },
                    "publisher":{
                        "@type":"Organization",
                        "name":"GéoPôle",
                        "logo":{
                            "@type":"ImageObject",
                            "url":"' . LOGO_AMP_PATH . '"
                        }
                    },
                    "description":"' . Str::cutStr($article->getDescription(), 150) . '"
                }
                </script>';
            echo $str;
        } catch (Exception $e) {
            //TODO LOGGER
            var_dump($e);
        }
    }

    /**
     * Génère les données structurées nécessaires à la génération d'une searchbox dans les sitelink google
     */
    public static function generateSearchBox(){
        $str ='
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "WebSite",
              "url": "https://www.geopole.net/",
              "potentialAction": [{
                "@type": "SearchAction",
                "target": "https://www.geopole.net/search?query={search_term_string}",
                "query-input": "required name=search_term_string"
              }]
            }
            </script>
        ';
        echo $str;
    }

}