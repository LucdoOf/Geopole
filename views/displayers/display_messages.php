<?php
if(!isset($foreignUser)) { //Display message list
    $messages = User::getConnectedUser()->getMessageMap();
    foreach ($messages as $key => $messageList) {
        $foreignUser = new User($key);
        if($foreignUser->exist()) {
            $lastMessage = $messages[$key][count($messages[$key]) - 1];
            $seen = $lastMessage->getSeen() || $lastMessage->getSender() == User::getConnectedUser()->getId() ? "seen" : "notseen";
            $selected = (isset($_POST["current_conversation"]) && (int)$_POST["current_conversation"] == $key) ? "expanded" : "";
            ?>
            <div class="conversation <?= $seen ?> <?= $selected ?>" data-id="<?= $foreignUser->getId(); ?>">
                <h4 class="foreigner"><?= $foreignUser->getPseudo(); ?></h4>
                <h5 class="last_message"
                    data-id="<?= $lastMessage->getId(); ?>"><?= mb_substr($lastMessage->getContent(), 0, 150); ?></h5>
            </div>
            <?php
        }
    }
} else { //Display conversation
    $connectedUser = User::getConnectedUser();
    $foreigner = new User($foreignUser);
    if($foreigner->exist()) {
        $messages = Message::getMessagesBeetween($foreigner->getId(), $connectedUser->getId(), $page);
        $messageCount = Message::countMessagesBeetween($connectedUser->getId(), $foreigner->getId());
        if($messageCount >= 15*$page) {
            if (!empty($messages)) {
                foreach ($messages as $message) {
                    if ($message->getSender() == $connectedUser->getId()) {
                        $author = $connectedUser->getPseudo();
                        $class = "sended";
                    } else {
                        $author = $foreigner->getPseudo();
                        $class = "";
                    }
                    ?>
                    <div class="message <?= $class ?>" data-id="<?= $message->getId(); ?>">
                        <span class="content"><?= $message->getContent(); ?></span>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class='first_message'>C'est le début de votre conversation avec <?= $foreigner->getPseudo(); ?>,
                    envoyez lui un message pour lui dire bonjour !
                </div>
                <?php
            }
        } else {
            echo false;
        }
    } else {
        ?>
            <div class='first_message'>Cet utilisateur n'existe pas!
            </div>
        <?php
    }
}
