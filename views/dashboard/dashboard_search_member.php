<?php $title = "Chercher un membre"; ?>
<?php $canonical = "/dashboard/search_member"; ?>
<?php $description = "Recherchez un membre de GéoPole par son pseudo et contactez le afin d'échanger vos opinions." ?>
<?php $scripts = ["/scripts/searchMemberScript.js"]; ?>

<?php ob_start() ?>

    <div id="content_right">
        <div id="body18">
            <h2>Chercher un membre</h2>
            <p>Recherchez le profil d'un membre en tapant son nom dans la barre de recherche</p>
            <input type="text" id="member-input" name="name" class="text-input" placeholder="Entrez le pseudo d'un membre"/>
            <div id="results">
                <!-- AJAX -->
            </div>
            <hr style="margin-top: 20px; width: 40%;"/>
        </div>
    </div>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php");