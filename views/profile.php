<?php ob_start(); ?>
<?php $title = "Profil" ?>
<?php $canonical = "/profile/" . $user->getPseudo() ?>
<?php
    $description = $user->getPseudo() . " est membre GéoPole depuis le " .
    $user->parseCreationDate(null,true,false) . ". Il a posté " . count($comments) . " commentaires, " .
    "connectez vous et envoyer lui un message ou débattez avec !";
?>
<?php $scripts = ["/scripts/profileScript.js"]; ?>
<?php if(!isset($user) || !isset($comments)) header("Location: /home"); ?>

<section id="body9" class="base-wrapper padded medium">
    <div id="body9_header">
        <h1 class="section-title">Profil</h1>
        <a id="send_message" href="/dashboard/messages?foreigner=<?= $user->getId(); ?>">Envoyer un message</a>
    </div>
    <h2><?= $user->getPseudo(); ?></h2>
    <h4>Inscrit depuis le <?= $user->parseCreationDate(null,true,false) ?></h4>
    <div class="profile_card" id="description">
        <h3>Description</h3>
        <hr/>
        <p><?= $user->getDescription(); ?></p>
    </div>
    <div class="profile_card" id="comments">
        <h3>Commentaires postés</h3>
        <hr/>
        <div id="comments_content">
            <?php
                foreach ($comments as $comment){
                    ?>
                        <a href="/articles/<?= $comment->getPost_id(); ?>" class="user_comment">
                            <h4>Le <?= $comment->parseCreationDate(null,true,false) ?></h4>
                            <p><?= $comment->getContent(); ?></p>
                        </a>
                    <?php
                }
            ?>
        </div>
        <a href="#" id="comments_expand">Etendre</a>
    </div>

</section>

<?php $content = ob_get_clean() ?>
<?php require APPLICATION_PATH."/views/template.php"; ?>
