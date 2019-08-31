<?php $title = "Messages"; ?>
<?php $canonical = "/dashboard/messages" ?>
<?php $description = "Discutez avec les membres de GéoPole et échangez vos opinions dans un cadre privé grâce à notre système de message." ?>
<?php $scripts = ["/scripts/messageScript.js"]; ?>
<?php ob_start() ?>

<div id="content_right">
    <div id="body11">
        <div id="content_right_header">
            <h2>Messages</h2>
            <a id="screen_control" href="#">Mode plein écran</a>
        </div>
        <div id="conversations_container">
            <div id="conversation_side">
                <a href="/dashboard/search_member">Ajouter</a>
                <div id="conversation_list">
                    <!-- Conversations -->
                </div>
            </div>
            <div id="conversation_middle" <?= isset($foreigner) ? "data-foreigner='".$foreigner."'": ""?>>
                <div id="scrollable_area">
                    <a class="scroll_top" style="display: none;">Voir plus...</a>
                    <div id="conversation_content">
                        <p class="info">Pour commencer, sélectionnez une conversation ou cliquez sur le bouton +</p>
                        <!-- Conversation actuelle -->
                    </div>
                </div>
                <div id="conversation_bottom">
                    <div id="conversation_input" class="default" contenteditable="true"></div>
                    <img id="send" src="/res/images/send.png" alt="send">
                </div>
            </div>
        </div>
    </div>
</div>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php"); ?>
