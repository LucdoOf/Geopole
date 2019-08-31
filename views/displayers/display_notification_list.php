<?php
$notifications = User::getConnectedUser()->getNotifications($page, 27);
for($i = 0; $i < 9; $i++) {
    if ($i < count($notifications)) {
        $notification = $notifications[$i];
        $class = "notification";
        if($notification->getSeen()){$class=$class." seen";}
        ?>
        <div class="<?= $class ?>">
            <div class="notification_left">
                <a class="notification_title" href="<?= $notification->getHref(); ?>"><?= $notification->getName(); ?></a>
                <p><?= $notification->getContent(); ?></p>
                <p>
                    Le <?= $notification->parseCreationDate(null,true,false) ?>
                </p>
            </div>
            <div class="notification_right">
                <?php if($notification->getSeen()){
                    ?>
                        <h4 style="display: inline-block">Lue</h4>
                    <?php
                } else {
                    ?>
                        <a href="javascript:void(0)" class="notification_viewed" data-id="<?= $notification->getId(); ?>">Marquer comme lue</a>
                    <?php
                } ?>
                <a href="javscript:void(0)" class="notification_delete" data-id="<?= $notification->getId(); ?>">Supprimer</a>
            </div>
        </div>
        <?php
    }
}

if(count($notifications) == 0) return false; ?>
<ul id="content_pages" style="margin: auto; margin-top:20px; display: flex; list-style: none; width: initial !important;">
    <input id="content_pages_input" value="1" type="number"/>
    <?php
    $limit = 9;
    $startI = 1;
    if($page >= 3){
        $startI = $page-1;
    }
    for($i = $startI; $i <= ceil((count($notifications)/$limit))+$page; $i++){
        $class = ($page+1) == $i ? "content_pages_selected" : "";
        echo "<li><a ". "class= " . $class . ">".$i."</a></li>";
    }
    ?>
</ul>
