<?php
/**
 * @var $debate Debate
 * @var $options DebateOption[]
 */
?>
<?php $title = "Débat " . $debate->getId() . " en attente" ?>
<?php $canonical = "/pending_debates/".$debate->getId() ?>
<?php $description = null ?>
<?php $robots = "none" ?>
<?php $scripts = []; ?>
<?php ob_start() ?>

    <div id="content_right">
        <div id="body21">
            <form method="POST" action="/pending_debates/<?= $debate->getId() ?>/treat" id="pending-form">
                <h2>Traiter un débat (Par <a target="_blank" href="/profile/<?= $author ?>"><?= $author ?></a>)</h2>
                <label class="field">
                    Titre du débat
                    <input type="text" class="text-input" value="<?= $debate->getTitle() ?>" placeholder="Titre du débat" name="title">
                </label>
                <label class="field">
                    Description du débat
                    <textarea class="textarea-input" placeholder="Description" name="description"><?= $debate->getDescription() ?></textarea>
                </label>
                <label class="field">
                    Slug du débat
                    <input type="text" class="text-input" name="slug" value="<?= $debate->getSlug() ?>" placeholder="Slug du débat">
                </label>
                <div id="cover-container">
                    <div class="image-uploader empty" id="cover" data-type="articles/" onclick="if(imageUploader) imageUploader.uploadImage()">
                        <a type="file" href="javascript:void(0)" class="image-input">Télécharger une image</a>
                    </div>
                    <p>Format recommandé pour l'image: 1980x1080 pixels. Si non disponible au moins respecter les proportions</p>
                </div>
                <div id="options-ctn" class="field no-label">
                    <input type="text" id="fake-options" class="text-input" placeholder="Entrez une réponse">
                    <?php foreach ($options as $option): ?>
                        <span class="debate-option"><?= $option->getName() ?></span>
                    <?php endforeach; ?>
                </div>
                <input type="text" name="options" style="display: none;" id="real-options">
                <div id="pending-actions">
                    <input style="margin-right: 10px;" type="submit" id="save-debate" class="button cta" value="Enregistrer le débat">
                    <a href="javascript:void(0)" id="delete-debate" class="button red">Supprimer le débat</a>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        var debateId = <?= $debate->getId(); ?>;
    </script>
    <script type="text/javascript" src="/scripts/modules/ImageUploader.js"></script>
    <script type="text/javascript">
        let imageUploader;
        $(document).ready(() => {
            imageUploader = new ImageUploader();
            imageUploader.setImage("<?= $debate->getImage() ?>");
        });
    </script>
    <script type="text/javascript" src="/scripts/pages/pendingDebateScript.js"></script>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php"); ?>