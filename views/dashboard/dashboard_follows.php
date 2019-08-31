<?php $title = "Mes sujets"; ?>
<?php $canonical = "/dashboard/follows" ?>
<?php $stylesheet = "dashboard_follows_style.css" ?>
<?php $description = "Programmez une notification lorsqu'un article concernant une catégorie spécifique est publié." ?>
<?php $scripts = ["/scripts/formScript.js", "/scripts/followsScript.js"]; ?>
<?php ob_start() ?>

    <div id="content_right">
        <div id="body11">
            <h2>Mes sujets</h2>
            <h3>Programmez une notification lorsqu'un article sort avec une catégorie spécifique</h3>
            <div id="followed">
                <div class="constant_content" id="categories">
                    <h3>Catégories</h3>
                    <div id="categories_content">
                        <!--Categories-->
                    </div>
                    <?php
                        $categories = Category::getAll();
                        $categoriesString = "";
                        foreach ($categories as $key => $category) {
                            $categoriesString = $categoriesString . $category->getName() . ";";
                        }
                    ?>
                    <div class="formInput" data-tag="true" data-list="<?= $categoriesString ?>">
                        <input type="text" name="categories"/>
                        <div contenteditable="true" class="input" data-length="20" data-default="Ajouter une catégorie" style="width: 200px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php"); ?>