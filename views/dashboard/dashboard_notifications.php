<?php $title = "Notifications"; ?>
<?php $canonical = "/dashboard/notifications" ?>
<?php $description = "Gérez vos notifications GéoPole et consultez ici les articles ou les messages que vous avez manqué." ?>
<?php $scripts = ["/scripts/listNotificationScript.js"]; ?>
<?php ob_start() ?>

    <div id="content_right">
        <div id="body10">
            <h2>Centre des notifications</h2>
            <?php
                if(User::getConnectedUser()->getRank() > 1){
                    ?>
                    <form id="send_notifications" method="POST" action="/dashboard/notifications?send=true">
                        <textarea name="content" class="textarea-input" placeholder="Ecrivez votre notification ici"><?= User::getConnectedUser()->getDescription(); ?></textarea>
                        <input type="submit" class="button cta" value="Envoyer une notification générale">
                    </form>
                    <?php
                }
            ?>
            <p class="title">Gérez vos notifications</p>
            <div id="notifications">
                <!--Notifications-->
            </div>
            <hr style="margin-top: 20px; width: 40%;"/>
        </div>
    </div>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php"); ?>
