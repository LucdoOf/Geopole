<?php
/**
 * @var $params Array
 */
header('Content-Type: application/json');
if(isset($_FILES["file"]) && isset($params["type"])) {
    $result = ImageUploader::uploadImage($_FILES["file"], $params["type"]);
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Aucun fichier renseigné"]);
}