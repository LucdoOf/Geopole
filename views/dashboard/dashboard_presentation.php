<?php $title = "Ma présentation"; ?>
<?php $canonical = "/dashboard/presentation"; ?>
<?php $description = "Personalisez votre page de profil en changeant votre présentation visible par les autres membres." ?>
<?php $stylesheet = "dashboard_presentation_style.css" ?>
<?php $scripts = []; ?>

<?php ob_start() ?>

<div id ="content_right">
	<div id="presentation">
		<div id="presentation_header">
			<h2>Ma présentation</h2>
			<p>Vous êtes ici sur votre page personnelle, celle visible par les autres membres, soignez la bien elle vous sert de vitrine !</p>
		    <hr style="width: 40%; margin-top: 20px;">
        </div>
        <?php if(!isset($edit) || !$edit): ?>
		<div id="presentation_content">
			<p>
				<?php
					echo User::getConnectedUser()->getDescription();
				?>
			</p>
            <a id="edit" class="button cta" href="/dashboard/presentation?edit=true">Editer</a>
		</div>
        <?php else: ?>
        <form method="POST" action="/dashboard/presentation">
            <textarea name="content" class="textarea-input" placeholder="Votre nouvelle présentation"><?= User::getConnectedUser()->getDescription(); ?></textarea>
            <div id="manage-controls">
                <a class="anchor return" href="/dashboard/presentation" id="back">Annuler</a>
                <input type="submit" class="button cta" value="Valider">
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php");