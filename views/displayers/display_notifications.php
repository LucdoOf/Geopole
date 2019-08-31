<?php
$notifications = User::getConnectedUser()->getNotifications();
for ($i = 0; $i < 6; $i++){
    if ($i < count($notifications)) {
    $notification = $notifications[$i];
    $class = "notification";
    if($notification->getSeen()){$class=$class." seen";}
    ?>
        <a href="<?= $notification->getHref(); ?>" class="<?= $class ?>" data-id="<?= $notification->getId(); ?>">
            <div class="notification_header">
                <h3 class="notification_title"><?= $notification->getName(); ?></h3>
                <h4 class="notification_date">
                    <?= $notification->parseCreationDate(null,true,false) ?>
                </h4>
            </div>
            <p class="notification_content"><?= $notification->getContent(); ?></p>
        </a>
    <?php
    }
}
for($i = 6; $i < count($notifications); $i++){
    $notification = $notifications[$i];
    ?>
        <a class="notification" data-id="<?= $notification->getId(); ?>" style="display: none">
    <?php
}
if(count($notifications) >= 7){
    ?>
        <a href="/dashboard/notifications" id="notification_more">Et <?= (count($notifications)-6) . " " . ((count($notifications) == 7) ? " autre " : " autres ") ?></a>
    <?php
}