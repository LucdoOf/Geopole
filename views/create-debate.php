<?php $title = "Créer un débat"; ?>
<?php $description = "Créez un débat, donnez lui un titre, une image, une description et regardez le grandir grace à la communauté GéoPole." ?>
<?php $canonical = "/debates/create" ?>
<?php $scripts = []; ?>

<?php ob_start(); ?>

<section id="body16" class="base-wrapper padded medium">
    <a id="cancel" href="/debates" class="anchor return">Annuler</a>
    <h2>Créer un débat</h2>
    <p>
        Rentrez ci-dessous le titre de votre débat et, si vous le souhaitez, des réponses pré-définies.
        Un modérateur validera ou refusera votre débat dans les plus brefs délais
    </p>
    <form method="POST" action="/debates/create">
        <input type="text" class="text-input" placeholder="Titre du débat" id="debate-title" name="title">
        <div id="cover-container">
            <div class="image-uploader empty" id="cover" data-type="debates/" onclick="if(imageUploader) imageUploader.uploadImage()">
                <a type="file" href="javascript:void(0)" class="image-input">Télécharger une image</a>
            </div>
            <p>Image de couverture du débat, format recommandé pour l'image: 1980x1080 pixels. Si non disponible au moins respecter les proportions</p>
        </div>
        <textarea type="text" class="textarea-input" placeholder="Description" id="debate-description" name="description"></textarea>
        <label class="checkbox-input">Réponses pré-définies
            <input type="checkbox" id="option-toogle">
            <span class="checkmark"></span>
        </label>
        <div id="options-ctn">
            <input type="text" id="fake-options" class="text-input" placeholder="Entrez une réponse" disabled>
        </div>
        <input type="text" name="options" style="display: none;" id="real-options">
        <input type="submit" class="button cta" value="Envoyer">
    </form>
</section>

<script type="text/javascript" src="/scripts/modules/OptionGenerator.js"></script>
<script type="text/javascript" src="/scripts/modules/ImageUploader.js"></script>
<script type="text/javascript">
    let imageUploader;
    $(document).ready(() => {
        imageUploader = new ImageUploader();
    });
</script>
<script type="text/javascript">
    let optionGenerator = new OptionGenerator(document.getElementById("options-ctn"), document.getElementById("fake-options"), document.getElementById("real-options"));
    $("#option-toogle").on("change", function () {
        if($(this).is(":checked")){
            optionGenerator.enable();
        } else {
            optionGenerator.disable();
        }
    });
</script>
<?php if(isset($error)): ?>
    <script type="text/javascript">
        $(document).ready(() => {
            PushTransmitter.pushError("<?= $error ?>");
        });
    </script>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php require(APPLICATION_PATH."/views/template.php"); ?>
