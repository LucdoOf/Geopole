<?php
if(isset($firstLetters)){
    $users = User::search($firstLetters, null, 27, ((int)$page)*9);
    for($i = 0; $i < 9; $i++) {
        if ($i < count($users)) {
            $user = $users[$i];
            ?>
            <div class="member">
                <div class="member_left">
                    <h4><?= $user->getPseudo(); ?></h4>
                    <p>Inscrit depuis
                        le <?= $user->parseCreationDate(null,true,false) ?></p>

                </div>
                <div class="member_right">
                    <a href="/profile/<?= $user->getPseudo() ?>">Voir le profil</a>
                    <a href="/dashboard/messages?foreigner=<?= $user->getId(); ?>">Envoyer un message</a>
                </div>
            </div>
            <?php
        }
    }

    if(count($users) == 0) return false; ?>
    <ul id="content_pages" style="margin: auto; margin-top:20px; display: flex; list-style: none; width: initial !important;">
        <input id="content_pages_input" value="1" type="number"/>
        <?php
            $limit = 9;
            $startI = 1;
            if($page >= 3){
                $startI = $page-1;
            }
            for($i = $startI; $i <= ceil((count($users)/$limit))+$page; $i++){
                $class = ($page+1) == $i ? "content_pages_selected" : "";
                echo "<li><a ". "class= " . $class . ">".$i."</a></li>";
            }
        ?>
    </ul>
    <?php

}