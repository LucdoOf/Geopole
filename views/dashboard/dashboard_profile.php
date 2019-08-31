<?php
/**
 * @var $debates Debate[]
 */
?>
<?php $title = "Votre profil GéoPôle"; ?>
<?php $canonical = "/dashboard/profile" ?>
<?php $description = "Modifiez votre profil GéoPôle, changez votre image de profil, réinitialisez votre mot de passe ou modifiez vos informations" ?>
<?php $scripts = []; ?>
<?php ob_start() ?>

    <div id="content_right">
        <div id="body23">
            <h2>Votre profil</h2>
            <label for="cover-container">
                Image de profil
                <div id="cover-container">
                    <div class="image-uploader empty" id="cover" data-type="profiles/" onclick="if(imageUploader) imageUploader.uploadImage()">
                        <a type="file" href="javascript:void(0)" class="image-input">Télécharger une image</a>
                    </div>
                    <p>Format recommandé pour l'image: 128x128 pixels. Si non disponible au moins respecter les proportions</p>
                </div>
            </label>
        </div>
    </div>

    <script type="text/javascript" src="/scripts/modules/ImageUploader.js"></script>
    <script type="text/javascript">
        let imageUploader;
        $(document).ready(() => {
            imageUploader = new ImageUploader({
                onsuccess: function (path) {
                    $.post(
                        "/dashboard/profile",
                        {
                            image: path
                        }
                    ).done(function (data) {
                        console.log(data);
                       PushTransmitter.pushSuccess("Votre image de profil a bien été changé !");
                    });
                }
            });
            <?php if(!empty(User::getConnectedUser()->getProfile_pic())): ?>
                imageUploader.setImage("<?= User::getConnectedUser()->getProfile_pic() ?>");
            <?php endif ?>
        });
    </script>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php"); ?>