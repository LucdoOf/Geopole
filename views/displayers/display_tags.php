<?php
    if(isset($categories) && !empty($categories)){
        foreach ($categories as $key => $categorie) {
            echo "<a href='#' class='category' data-id=".$categorie->getId().">".$categorie->getName()."</a>";
        }
    }

