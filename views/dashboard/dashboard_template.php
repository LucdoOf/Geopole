<?php
/**
 * @var $canonical String
 */
?>
<?php ob_start(); ?>
<?php $stylesheets = []; ?>
<?php if(isset($stylesheet)) array_push($stylesheets, $stylesheet); ?>

<section id="body5" class="base-wrapper padded">
	<h1 class="section-title">Votre espace personnel</h1>
	<div id="content">
		<ul id="content_list">
            <?php $check = function($name) use($canonical) {
                if (!empty($canonical)) {
                    if($canonical == $name){
                        return "active";
                    }
                }
                return "";
            } ?>
			<div id="rank_links">
			<?php
				$user = User::getConnectedUser();
				if($user->getRank() > 1){
					echo "<li class='rank_link'><a href='/manage_article'>Ecrire un article</a></li>";
					echo "<li class='rank_link ".$check("/dashboard/manage_constants")."'><a href='/manage_constants'>Gérer les constantes</a></li>";
				    echo "<li class='rank_link ".$check("/pending_debates")."'><a href='/pending_debates'>Débats en attente</a></li>";
				}
			?>
			</div>
			<li class="dashboard_link <?= $check("/dashboard/presentation")?>"><a href="/dashboard/presentation">Ma présentation</a></li>
			<li class="dashboard_link<?= $check("/dashboard/profile") ?>"><a href="/dashboard/profile">Mon profil</a></li>
			<li class="dashboard_link <?= $check("/dashboard/follows")?>"><a href="/dashboard/follows">Mes sujets</a></li>
			<li class="dashboard_link <?= $check("/dashboard/messages")?>"><a href="/dashboard/messages">Mes messages</a></li>
            <li class="dashboard_link <?= $check("/dashboard/search_member")?>"><a href="/dashboard/search_member">Chercher un membre</a></li>
            <li class="dashboard_link <?= $check("/dashboard/notifications")?>"><a href="/dashboard/notifications">Notifications</a></li>
			<li class="dashboard_link"><a href="/disconnect" style="color: red;">Deconnection</a></li>
		</ul>
		<?= $dashboard_content ?>
	</div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require("views/template.php"); ?>

