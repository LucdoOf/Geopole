<?php $title = $post->getTitle(); ?>
<?php $canonical = "/articles/" . $post->getSlug() ?>
<?php $description = Str::cutStr($post->getDescription(), 255) ?>
<?php $authorObj = new User($post->getAuthor()); ?>
<?php $author = $authorObj->getPseudo() ?>
<?php $scripts = ["/scripts/formScript.js","/scripts/postScript.js"]; ?>
<?php $image = $post->getImage(); ?>
<?php $type = "article" ?>

<?php ob_start(); ?>

<section id="body3" class="base-wrapper padded medium" data-id=<?=$post->getId()?>>
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
	<h1><?= $post->getTitle() ?></h1>
    <p id="post-description"><?= htmlspecialchars($post->getDescription()) ?></p>
    <img id="post-image" src="<?= $post->getImage() ?>" alt="<?= $post->getTitle(); ?>" onerror="this.onerror=null; this.src='/res/images/not-found.png'">
	<div id="post-content"><?= $post->getContent() ?></div>
	<p id="post_author"><?= "Par " . $author ?></p>
	<p id="post_date"><?= "Écrit le " . $post->parseCreationDate(null,true,false) ?></p>
    <p id="post_viewed">Vu <?= $post->getViewed() ?> fois</p>
    <?php
        if(User::isConnected() && User::getConnectedUser()->getRank() >= 1){
            echo "<a href='/manage_article/" . $post->getId() . "' class='editPost'>Editer</a>";
        }
    ?>
	<div id="post_footer">
		<?php 
			if(!User::isConnected()){
				echo "<div class='formRating formGray' data-cancelZero='true'>";
			} else {
				echo "<div class='formRating' data-cancelZero='true'>";
			}
		?>
		<input type="number" name="rating" value=<?= (int)$post->getRating() ?>>
	</div>
	<div id="post-social">
		<p>Cet article à reçu en moyenne une note de <?= (int)$post->getRating() ?>/5 Vous aussi notez le !</p>
	</div>
	<div id="post-comments">
        <h3>Commentaires</h3>
		<?php
        if(empty($comments)){
            echo "<p id='first-comment'>Soyez le premier à commenter cet article !</p>";
        }
        ?>
        <?php if(User::isConnected()){
            ?>
            <form id="comment-form" method="POST" action="/manage_comment/post?post_id=<?= $post->getId();  ?>">
                <textarea id="comment-content" name="content" placeholder="Ecrivez votre commentaire ici" class="textarea-input"></textarea>
                <input type="submit" id="submit-comment" class="button cta" value="Envoyer"/>
            </form>
            <?php
        } ?>
        <div id="comments-ctn">
            <?php
                if(!empty($comments)) {
                    foreach ($comments as $comment) {
                        $subComments = $comment->getSubComments();
                        ArticleParser::parseComment($comment, $subComments);
                    }
                }
            ?>
        </div>
	</div>
</section>

<script type="text/javascript" src="/scripts/modules/CommentManager.js"></script>
<script type="text/javascript">
    var commentManager = new CommentManager("post_comment", "/articles/<?= $post->getSlug() ?>", "/articles/<?= $post->getSlug() ?>");
</script>

<?php StructuredData::generateArticle($post); ?>

<?php $content = ob_get_clean(); ?>
<?php require("template.php"); ?>
