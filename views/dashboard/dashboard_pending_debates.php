<?php
/**
 * @var $debates Debate[]
 */
?>
<?php $title = "Débats en attente"; ?>
<?php $canonical = "/pending_debates" ?>
<?php $description = null ?>
<?php $robots = "none" ?>
<?php $scripts = []; ?>
<?php ob_start() ?>

    <div id="content_right">
        <div id="body20">
            <h2>Débats en attente</h2>
            <?php if(!empty($debates)):
                foreach ($debates as $debate){
                    DebateParser::parsePending($debate);
                }
            else: ?>
                <p id="empty-debates">Il n'y a aucun débat en attente pour le moment</p>
            <?php endif; ?>
        </div>
    </div>

<?php $dashboard_content = ob_get_clean(); ?>
<?php require("dashboard_template.php"); ?>