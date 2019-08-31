<?php
/**
 * @var $errorMsg
 */
?>
<?php $title = "Une erreur est survenue"; ?>
<?php $canonical = null ?>
<?php $description = null ?>
<?php $robots = "none" ?>
<?php ob_start() ?>
<section id="body23" class="base-wrapper padded filled">
    <h1 class="section-title">Oops..! Une erreur est survenue</h1>
    <?= $errorMsg ?>
</section>
<?php $content = ob_get_clean(); ?>
<?php require(APPLICATION_PATH."/views/template.php"); ?>

