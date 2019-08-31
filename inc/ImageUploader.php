<?php
/*
 * Utilitaire d'upload d'image
 * Class ImageUploader
 */
class ImageUploader
{

    /**
     * Dossier de destination général
     */
    const UPLOAD_DIRECTORY = APPLICATION_PATH . "/upload/images/";
    /**
     * Taille maximum autorisée d'une image
     */
    const MAX_SIZE = 500000;
    /**
     * Message d'erreurs possibles
     */
    const ERRORS = [
        0 => "Le fichier choisi n'est pas une image",
        1 => "Une image de ce nom existe déjà",
        2 => "Cette image est trop lourde",
        3 => "Seules les images jpg et png sont autorisées",
        4 => "Une erreur est survenue lors du téléchargement de votre image"
    ];

    /**
     * Upload une image sur le serveur
     * @param $file
     * @param $type
     * @return array Contenant une clef error si il y a une erreur, sinon une clef path content le chemin du fichier uploadé
     */
    public static function uploadImage($file,$type){
        $target = self::UPLOAD_DIRECTORY . $type . sprintf("%08d", intval(self::getLastUploadedImageId($type))+1) .".". pathinfo($file["name"], PATHINFO_EXTENSION);
        $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        if(self::isImageFile($file)){
            if(!file_exists($target)){
                if($file["size"] < self::MAX_SIZE){
                    if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg"){
                        if(move_uploaded_file($file["tmp_name"], $target)){
                            return ["path" => explode("www",$target)[1]];
                        } else {
                            return ["error" => self::ERRORS[4]];
                        }
                    } else {
                        return ["error" => self::ERRORS[3]];
                    }
                } else {
                    return ["error" => self::ERRORS[2]];
                }
            } else {
                return ["error" => self::ERRORS[1]];
            }
        } else {
            return ["error" => self::ERRORS[0]];
        }
    }

    /**
     * Retourne le nom de la dernière image uploadée
     * @param $type
     * @return int
     */
    public static function getLastUploadedImageId($type){
        $target = self::UPLOAD_DIRECTORY . $type;
        $files = glob($target . "*");
        //array_multisort(array_map('filemtime', $files), SORT_NUMERIC, SORT_DESC, $files);
        if(!empty($files)) {
            $explode = explode(DIRECTORY_SEPARATOR, $files[count($files)-1]);
            return explode(".", $explode[count($explode) - 1])[0];
        }
        return 0;
    }

    /**
     * Vérifie si un fichier est une image
     * @param $file
     * @return bool
     */
    public static function isImageFile($file){
        return getimagesize($file["tmp_name"]) != false;
    }
}
