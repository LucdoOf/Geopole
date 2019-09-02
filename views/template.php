<?php
/**
 * @var $description
 * @var canonical
 * @var $content
 * @var $scripts
 * @var $image
 * @var $type
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title><?= $title ?></title>
	<meta charset="UTF-8">
	<meta name="language" content="fr-FR"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $description ?>">
    <meta name="robots" content="<?= isset($robots) ? $robots : "all" ?>">
    <meta name="theme-color" content="#252729">
    <meta name="author" content="<?= isset($author) ? $author : "Lucas Garofalo" ?>">
    <meta name="MobileOptimized" content="320"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-title" content="GéoPôle"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="#252729"/>
    <meta name="msapplication-navbutton-color" content="#252729"/>
    <meta name="application-name" content="GéoPôle"/>
    <meta name="application-url" content="<?= ABSOLUTE_PATH ?>"/>
    <meta name="geo.region" content="FR-F"/>
    <meta name="geo.placename" content="Tours"/>
    <meta name="geo.position" content="47.390047;0.688927"/>
    <meta name="ICBM" content="47.390047, 0.688927"/>
    <meta property="og:site_name" content="Geopole.net">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="<?= $title ?>">
    <meta property="og:description" content="<?= $description ?>">
    <meta property="og:url" content="<?= ABSOLUTE_PATH . $canonical ?>">
    <meta property="og:type" content="<?= empty($type) ? "website" : $type ?>">
    <meta property="og:image" content="<?= ABSOLUTE_PATH . (empty($image) ? "/res/images/android-chrome-192x192.png" : $image) ?>">
    <meta property="og:image:width" content="1440">
    <meta property="og:image:height" content="720">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="fb:app_id" content="<?= FACEBOOK_APP_ID ?>">
    <meta property="fb:admins" content="<?= FACEBOOK_PAGE_ID ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@GeopoleSite">
    <meta property="twitter:url" content="<?= ABSOLUTE_PATH . $canonical ?>">
    <meta property="twitter:title" content="<?= $title ?>">
    <meta property="twitter:description" content="<?= $description ?>">
    <meta property="twitter:image" content="<?= ABSOLUTE_PATH . (empty($image) ? "/res/images/android-chrome-192x192.png" : $image) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= ABSOLUTE_PATH . $canonical ?>">
	<link rel="alternate" hreflang="fr" href="<?= ABSOLUTE_PATH . $canonical ?>">
    <link rel="stylesheet" type="text/css" href="/res/stylesheets/css/main.css">
    <link rel="stylesheet" type="text/css" href="/res/stylesheets/form_style.css">
    <link rel="canonical" href="<?= ABSOLUTE_PATH . $canonical ?>">
    <link rel="manifest" href="<?= ABSOLUTE_PATH . "/manifest.json" ?>">
    <link rel="author" href="/humans.txt">
    <?php if(!empty($pagePrev) && !is_null($pagePrev)): ?>
        <link rel="prev" href="<?= $pagePrev ?>">
    <?php endif; ?>
    <?php if(!empty($pageNext) && !is_null($pageNext)): ?>
        <link rel="next" href="<?= $pageNext ?>">
    <?php endif; ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="/lib/easings.net/vendor/jquery.easing.js"></script>
    <script type="text/javascript" src="/scripts/templateScript.js"></script>
    <script type="text/javascript" src="/scripts/notificationScript.js"></script>
    <script type="text/javascript" src="/scripts/modules/CategoryPicker.js"></script>
    <script type="text/javascript" src="/scripts/modules/MobileMenu.js"></script>
    <script type="text/javascript" src="/scripts/core/PushTransmitter.js"></script>
    <script type="text/javascript" src="/scripts/core/Modal.js"></script>
    <script type="text/javascript" src="/scripts/core/Utils.js"></script>
    <script type="text/javascript">
        const USER_INFO = {
          connected: <?= User::isConnected() == true ? "true" : "false" ?>
        };
    </script>
    <script type="text/javascript">
        $(document).ready(() => {
            new CategoryPicker();
            new MobileMenu();
        });
    </script>
</head>
<body>
	<header>
        <div id="header_top">
            <a href="javascript:void(0)" id="hamburger"><img src="/res/images/hamburger.svg" alt="hamburger"></a>
            <a href="/dashboard" id="profile"><img src="/res/images/profile.svg" alt="profile"></a>
            <div id="header_left">
                <form action="/search" method="GET">
                    <input type="text" id="global-searcher" placeholder="Rechercher.." name="query">
                </form>
            </div>
            <div id="geopole">
                <a id="geopole-link" href="/home">GéoPôle</a>
                <nav>
                    <ul>
                        <li><a href="/home">ACCUEIL</a></li>
                        <li><a id="artc-link" href="/articles">ARTICLES</a></li>
                        <li><a href="/debates">DEBATS</a></li>
                    </ul>
                </nav>
            </div>
            <div id="header_right">
                <?php
                if(isset($_COOKIE["user_id"]) || !isset($_COOKIE["user_password"])){
                    if(User::isConnected()){
                    ?>
                        <a id="connection" href="/dashboard">Bienvenue <?= User::getConnectedUser()->getFirstName() ?></a>
                        <div id="notifications_global_container">
                            <img src="/res/images/office/bell.png" alt="bell"/>
                            <h5 id="notifications_counter" style="display: none">4</h5>
                            <img src="/res/images/connector.png" alt="vote" id="connector" style="display: none"/>
                            <div id="notifications" style="display: none">
                                <!-- Ajax loaded content -->
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <a id="connection" href="/login">CONNECTION</a>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
        <div id="menu">
            <div id="menu-categories">
                <div class="col-wrapper">
                    <?php
                        $categories = Category::getAll();
                        for($i = 0; $i < 5; $i++){
                            if(isset($categories[$i])){
                                $category = $categories[$i];
                                if($i == 3){ ?>
                                        </div>
                                        <div class="col-wrapper">
                                <?php } ?>
                                        <a href="/articles?category=<?= $category->getId(); ?>" class="menu-category" data-id="<?= $category->getId(); ?>"><?= $category->getName(); ?></a>
                                <?php }
                            }
                        ?>
                        <a href="/articles" class="menu-category more-category" data-id="-1">Toutes les catégories</a>
                    </div>
            </div>
            <div id="menu-artc"></div>
        </div>
	</header>
    <div id="mobile-menu">
        <form id="mobile-search" action="/search" method="GET">
            <input type="text" id="global-mobile-searcher" placeholder="Rechercher.." name="query">
        </form>
        <ul class="link-list">
            <li class="link"><a href="/home">Accueil</a></li>
            <li class="link"><a href="/articles">Articles</a></li>
            <li class="link"><a href="/debates">Débats</a></li>
            <li class="link"><a href="/contact">Contact</a></li>
        </ul>
    </div>
    <div id="main-scroller">
	    <?= $content ?>
    </div>
    <div id="push-notifications"></div>
    <div id="modal-container"></div>
    <?php if($canonical !== "/search"): ?>
        <a id="search_icon" href="/search"></a>
    <?php endif ?>
	<footer>
		<div id="footer-top">
            <div id="socials">
                <a href="#"><img src="/res/images/facebook.svg" alt="Facebook"></a>
                <a href="#"><img src="/res/images/instagram.svg" alt="Instagram"></a>
                <a href="#"><img src="/res/images/twitter.svg" alt="Twitter"></a>
            </div>
            <nav>
                <a href="/articles">Articles</a>
                <a href="/debates">Débats</a>
                <a href="/contact">Contact</a>
            </nav>
        </div>
        <div id="footer-bot">
            <div id="made"><div class="text-wrapper">Site indépendant fait avec <img src="/res/images/love.svg" alt="Love"></div> par Maëva Darnault et Lucas Garofalo à Tours</div>
            <small>© Geopole. Tout droits réservés.</small>
            <small><a href="<?= ABSOLUTE_PATH ?>/privacy.pdf">Politiques de confidentialités</a></small>
        </div>
	</footer>

	<?php
        if(isset($scripts) && is_array($scripts)) {
            for ($i = 0; $i < count($scripts); $i++) {
                echo '<script src="' . $scripts[$i] . '"></script>';
            }
        }
	?>
</body>
</html>