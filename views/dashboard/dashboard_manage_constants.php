<?php $title = "Gérer les constantes"; ?>
<?php $canonical = "/dashboard/manage_constants" ?>
<?php $description = null ?>
<?php $robots = "none" ?>
<?php $scripts = ["/scripts/constantScript.js"]; ?>
<?php ob_start() ?>

	<div id="content_right">
		<div id="body8">
			<h2>Gérer les constantes</h2>
			<div class="constant_content" id="categories">
				<h3>Catégories</h3>
				<div id="categories_content">
                    <!--Categories-->
				</div>
                <input type="text" class="text-input" placeholder="Ajouter une catégorie" style="width: 200px">
			</div>
		</div>
	</div>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php"); ?>