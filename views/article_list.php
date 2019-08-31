<?php
/**
 * @var $page int
 * @var $counter int
 */
$title = "Liste des articles";
$categoryStr = "?";
if(!is_null($category)) $categoryStr = "?category=".$category->getId()."&";
if(!is_null($category)) $description = "Retrouvez la liste de nos articles de " . $category->getName() . " commentez les et partagez les pour en débatre.";
else $description = "Tout nos articles tout sujet confondus avec tout les commentaires de nos membres.";
$canonical = "/articles".$categoryStr."page=".$page;
$pagePrev = $page > 0 ? "/articles".$categoryStr."page=".($page-1) : null;
$pageNext = ceil($counter/ArticlesController::ARTICLE_BY_PAGE)-1 > $page ? "/articles".$categoryStr."page=".($page+1) : null;
$scripts = []; ?>

<?php ob_start(); ?>

<section id="body13" class="base-wrapper">
    <section id="categories">
        <?php
            foreach (Category::getAll() as $cat){
                $disabled = (!is_null($category) && $category->getId() == $cat->getId()) ? "" : "disabled";
                $link = (!is_null($category) && $category->getId() == $cat->getId()) ? "/articles" : "/articles?category=".$cat->getId();
                ?>
                    <a class="category rounded <?= $disabled ?>" href="<?= $link ?>"><?= $cat->getName(); ?></a>
                <?php
            }
        ?>
    </section>
    <section id="content">
        <div class="articles">
            <?php
                foreach (array_slice($articles, 0, round(count($articles)/2)) as $article){
                    ArticleParser::parseCol($article);
                }
            ?>
        </div>
        <div class="articles">
            <?php
                foreach (array_slice($articles, round(count($articles)/2), count($articles)-round(count($articles)/2)) as $article){
                    ArticleParser::parseCol($article);
                }
            ?>
        </div>
        <aside id="suggestions" class="ctm-section">
            <section id="latest-articles">
                <h2>Derniers articles</h2>
                <?php
                    foreach (Post::getLastCreated(10) as $article){
                        ArticleParser::parseRounded($article);
                    }
                ?>
            </section>
        </aside>
    </section>
    <section class="pager">
        <?= Pager::buildPager(intval($counter), ArticlesController::ARTICLE_BY_PAGE, intval($page), function ($page) use ($categoryStr) {
            return "/articles".$categoryStr."page=".$page;
        }) ?>
    </section>
</section>

<?php $content = ob_get_clean(); ?>
<?php require("template.php"); ?>

