<?php
/**
 * @var $query String
 */
?>
<?php ob_start(); ?>
<?php $title = "Rechercher" ?>
<?php $canonical = "/search" ?>
<?php
    $description = "Chercher les articles et les débats qui vous interessent avec vos critères"
?>
<?php $scripts = ["scripts/pages/searchScript.js"]; ?>

<section id="body22" class="base-wrapper padded">
    <h1 class="section-title">Rechercher</h1>
    <p style="margin-bottom: 20px;">Précisez-nous ce que vous recherchez puis rentrez des mots clefs dans la barre de recherche, nous nous occupons de vous trouver les meilleurs articles et débats.</p>
    <div id="search-form">
        <div class="field">Que recherchez vous ?
            <label class="checkbox-input">Articles
                <input type="checkbox" name="articles" id="search-articles" checked>
                <span class="checkmark"></span>
            </label>
            <label class="checkbox-input">Débats
                <input type="checkbox" name="debates" id="search-debates" checked>
                <span class="checkmark"></span>
            </label>
        </div>
        <label class="field" id="search-field">
            Entrez des mots clefs, un titre d'un article ou bien une catégorie
            <input type="text" id="search-input" class="text-input" value="<?= $query ?>" placeholder="Géopolitique, Chine..">
            <a href="javascript:void(0)" id="search-image"></a>
        </label>
    </div>
    <div id="search-results" class="empty">
        <!-- Ajax loaded content -->
    </div>
</section>

<?php $content = ob_get_clean() ?>
<?php require APPLICATION_PATH."/views/template.php"; ?>
