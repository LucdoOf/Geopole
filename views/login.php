<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Tableau de bord</title>
    <meta charset="UTF-8">
    <meta name="language" content="fr-FR"/>
    <meta name="description" content="Connectez vous ou créez un compte pour publier des débats, commentez des articles et accéder à tout les autres services GéoPole."/>
    <meta name="robots" content="all">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="alternate" hreflang="x-default" href="<?= ABSOLUTE_PATH . "/login" . (isset($signUp) ? "/sign_up" : "") ?>">
    <meta name="theme-color" content="#252729">
    <meta name="author" content="Lucas Garofalo">
    <meta name="MobileOptimized" content="320"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-title" content="GéoPôle"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="application-name" content="GéoPôle"/>
    <meta name="application-url" content="<?= ABSOLUTE_PATH ?>"/>
    <meta name="geo.region" content="FR-F"/>
    <meta name="geo.placename" content="Tours"/>
    <meta name="geo.position" content="47.390047;0.688927"/>
    <meta name="ICBM" content="47.390047, 0.688927"/>
    <link rel="manifest" href="<?= ABSOLUTE_PATH . "/manifest.json" ?>">
    <link rel="alternate" hreflang="fr" href="<?= ABSOLUTE_PATH . "/login" . (isset($signUp) ? "/sign_up" : "") ?>">
    <link rel="canonical" href="<?= ABSOLUTE_PATH . "/login" . (isset($signUp) ? "/sign_up" : "") ?>">
    <link rel="stylesheet" type="text/css" href="/res/stylesheets/form_style.css">
    <link rel="stylesheet" type="text/css" href="/res/stylesheets/css/main.css">
    <link rel="author" href="/humans.txt">
    <meta property="fb:app_id" content="<?= FACEBOOK_APP_ID ?>">
    <meta property="fb:admins" content="<?= FACEBOOK_PAGE_ID ?>">
</head>
<body style="height: 100%;">
	<div id="body4">			
		<?php
			if(isset($signUp)){
				?>
				<form method="POST" action="/login/sign_up">
					<a href="/home"><h1>Geopôle</h1></a>
					<h2>Inscription</h2>
					<?php
						if(isset($error)){
							echo "<p class='form-error'>".$error."</p>";
						}
					?>
                    <label for="email">
                        <input type="email" placeholder="Email" name="email" class="text-input"/>
                    </label>
                    <div class="label-group">
                        <label for="first_name">
                            <input type="text" placeholder="Prénom" class="text-input" name="first_name"/>
                        </label>
                        <label for="last_name">
                            <input type="text" placeholder="Nom" class="text-input" name="last_name"/>
                        </label>
                    </div>
                    <label for="pseudo">
                        <input type="text" placeholder="Pseudo" class="text-input" name="pseudo"/>
                    </label>
                    <label for="password">
                        <input type="password" placeholder="Mot de passe" class="text-input" name="password"/>
                    </label>
                    <label for="password_confirm">
                        <input type="password" placeholder="Confirmez le mot de passe" class="text-input" name="password_confirm"/>
                    </label>
					<input class="button cta" type="submit" name="submit" id="submit" value="Valider">
					<a href="/login">Ou connectez vous</a>
                    <small id="privacy">En vous inscrivant vous acceptez nos <a href="/privacy.pdf">conditions générales d'utilisations</a></small>
				</form>
			<?php 
			} else {
				?>
				<form method="POST" action="/login">
					<a href="/home"><h1>Geopôle</h1></a>
					<h2>Connection</h2>
					<?php
						if(isset($error)){
							echo "<p class='form-error'>".$error."</p>";
						}
					?>
                    <label for="pseudo">
                        <input type="text" placeholder="Pseudo" class="text-input" name="pseudo"/>
                    </label>
                    <label for="password">
                        <input type="password" placeholder="Mot de passe" class="text-input" name="password"/>
                    </label>
                    <input class="button cta" type="submit" value="Valider"/>
					<a href="/login/sign_up">Ou créez un compte</a>
				</form>
			<?php }
		?>
</body>
</html>
