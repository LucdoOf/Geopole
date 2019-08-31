<?php
    $title = "Gérer un article";
    $stylesheets = ["dashboard_create_article_style.css"];
    $canonical = "/dashboard/manage_article";
    $scripts = ["/scripts/formScript.js", "/scripts/managePostScript.js"];
    $description = null;
    $robots = "none";
?>
<?php ob_start() ?>
    <section id="body6" class="base-wrapper padded medium" <?php if(isset($post)){ echo "data-post=" . $post->getId(); } ?> >
        <form id="form" method="POST" action="<?= $action ?>" enctype="multipart/form-data">
            <a id="cancel" href="/dashboard" class="anchor return">Annuler</a>
            <h2>Titre de l'article</h2>
            <input type="text" class="text-input" name="title" id="title" placeholder="Titre de l'article"/>

            <h2>Couverture de l'article</h2>
            <div id="cover-container">
                <div class="image-uploader empty" id="cover" data-type="articles/" onclick="if(imageUploader) imageUploader.uploadImage()">
                    <a type="file" href="javascript:void(0)" class="image-input">Télécharger une image</a>
                </div>
                <p>Format recommandé pour l'image: 1980x1080 pixels. Si non disponible au moins respecter les proportions</p>
            </div>

            <h2>Catégories de l'article</h2>
            <div id="categories">
                <?php
                    $categories = Category::getAll();
                    $categoriesString = "";
                    foreach ($categories as $key => $category) {
                        $categoriesString = $categoriesString . $category->getName() . ";";
                    }
                ?>
                <div class="formInput" data-tag="true" data-list="<?= $categoriesString ?>">
                    <input type="text" name="categories"/>
                    <div contenteditable="true" class="input" data-length="20" data-default="Catégories" style="width: 200px"></div>
                </div>
            </div>

            <h2>Description de l'article</h2>
            <textarea id="description" name="description" class="textarea-input" placeholder="Description de l'article"></textarea>

            <h2>Slug</h2>
            <input type="text" id="slug" name="slug" class="text-input" placeholder="longeur-corgis"/>

            <h2>Contenu de l'article</h2>
            <textarea id="content" name="content"></textarea>

            <div id="manage-controls">
                <input type="submit" class="button cta" value="Valider">
            </div>
        </form>
    </section>
    <script src="https://cdn.tiny.cloud/1/<?= TYNYMCE_API_KEY ?>/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script type="text/javascript" src="/scripts/modules/ImageUploader.js"></script>
    <script type="text/javascript">
        let imageUploader;
        $(document).ready(() => {
            imageUploader = new ImageUploader();
        });
    </script>
    <script type="text/javascript">
        tinymce.init({
            selector: '#content',
            plugins: 'autoresize print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
            resize: true,
            branding: false,
            autoresize_min_height: 400,
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
            mobile: {
                theme: 'mobile',
                plugins: [ 'autosave', 'lists', 'autolink' ],
                toolbar: [ 'undo', 'bold', 'italic', 'styleselect' ]
            }
        }).then(()=>{
            initializeContent();
        });
    </script>
    <?php if(isset($error)): ?>
        <script type="text/javascript">
            $(document).ready(() => {
                PushTransmitter.pushError("<?= $error ?>");
            });
        </script>
    <?php endif; ?>
<?php
    $content = ob_get_clean();
    require("views/template.php");
?>