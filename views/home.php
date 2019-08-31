<?php $title = "Articles et débats sur des thèmes variés, espace d'échange et de communication"; ?>
<?php $canonical = "/home" ?>
<?php $description = "Articles et débats sur des sujets d'actualités. Des thèmes variés: géopolitique, climatologie ou sciences humaines, tout en privilégiant l'échange." ?>
<?php $scripts = ["https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js", "/lib/importantStyle.js", "/lib/easings.net/vendor/jquery.easing.js", "/scripts/formScript.js", "/scripts/pages/homeScript.js"]; ?>
<?php ob_start(); ?>

<section class="base-wrapper" id="body14">
    <div id="artc-scroller">
        <div id="main-artc">
            <div class="home-une">
                <?php
                    $article = Post::getLastCreated(1)[0];
                    if(!is_null($article)) {
                        ArticleParser::parseUne($article);
                    } else {
                        ?>
                            <div class="message-wrapper white bordered">Aucun article n'a pour l'instant été écrit, revenez plus tard !</div>
                        <?php
                    }
                ?>
            </div>
        </div>
        <div id="last-artc" class="artc-list">
            <?php
                $articles = Post::getLastCreated(10);
                for($i = 1; $i < count($articles); $i++){
                    $article = $articles[$i];
                    ArticleParser::parseDevelopped($article);
                }
            ?>
        </div>
        <div id="most-read-artc">
            <h2 class="scroll-title">articles les plus lus</h2>
            <div class="articles">
                <?php
                    $articles = Post::getMostRead(6);
                    if(!empty($articles)) {
                        foreach ($articles as $article) {
                            ArticleParser::parseTop($article);
                        }
                    } else {
                        ?>
                            <div class="message-wrapper white bordered">Aucun article n'a pour l'instant été écrit, revenez plus tard !</div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div id="side-content">
        <div class="side-container" id="side-debates">
            <h2 class="side-title">Débats</h2>
            <?php
                $debates = Debate::getActives(10);
                if(!empty($debates)){
                    foreach ($debates as $debate){
                        DebateParser::parseSidebar($debate);
                    }
                } else {
                    ?>
                        <div class="message-wrapper white">Aucun débat n'est en ligne pour l'instant</div>
                    <?php
                }
            ?>
        </div>
        <div class="side-container" id="side-articles-comments">
            <h2 class="side-title">Derniers commentaires</h2>
            <?php
                $articleComments = Comment::getAll([], "TIMESTAMP(creation_date) DESC", "10");
                if(!empty($articleComments)){
                    foreach ($articleComments as $articleComment){
                        ArticleParser::parseSidebarComment($articleComment);
                    }
                } else {
                    ?>
                        <div class="message-wrapper white">Aucun commentaire n'a été posté</div>
                    <?php
                }
            ?>
        </div>
        <div class="side-container" id="side-debates-responses">
            <h2 class="side-title">Dernières opinions publiées</h2>
            <?php
                $debateResponses = DebateResponse::getAll([],null,10);
                if(!empty($debateResponses)){
                    foreach ($debateResponses as $debateResponse){
                        DebateParser::parseSidebarResponse($debateResponse);
                    }
                } else {
                    ?>
                        <div class="message-wrapper white">Aucune opinion n'a été postée</div>
                    <?php
                }
            ?>
        </div>
    </div>
</section>

<?= StructuredData::generateSearchBox(); ?>

<?php $content = ob_get_clean(); ?>
<?php require(APPLICATION_PATH."/views/template.php"); ?>
