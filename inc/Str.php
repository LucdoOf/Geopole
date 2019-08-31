<?php
/**
 * Utilitaire de gestion de chaine de caractères
 * Class Str
 */
class Str
{

    /**
     * Cut un string proprement avec une longueur donnée (-3 pour les 3 petits points de la fin)
     * @param $str string
     * @param $length int
     * @return string
     */
    public static function cutStr($str, $length){
        $wrapped = wordwrap($str, $length-3);
        $lines = explode("\n", $wrapped);
        $new_str = $lines[0] . '...';
        return $new_str;
    }

    /**
     * Vérifie si un string commence avec un needle
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * Récupère les utilisateurs depuis une chaine de caractère
     * @param $string
     * @return User[]
     */
    public static function extractUsersFromString($string){
        $users = [];
        $splits = explode(" ", $string);
        foreach ($splits as $split){
            if(strlen($split) > 1 && self::startsWith($split, "#")){
                $user = User::getByPseudo(substr($split,1));
                if($user->exist()){
                    $users[] = $user;
                }
            }
        }
        return $users;
    }

    /**
     * Retourne une date parsé au format spécifié
     * @param $date DateTime
     * @param bool $dayMonthYear Afficher le jour le mois et l'année ?
     * @param bool $hourMinutes Afficher les heures et les minutes ?
     * @param string $separator Séparateur entre les deux parties
     * @return string
     */
    public static function parseDate($date, $separator = "à", $dayMonthYear = true, $hourMinutes = true){
        $str = "";
        if($dayMonthYear == true){
            $str .= $date->format("d/m/Y");
        }
        if(!is_null($separator) && $dayMonthYear == true && $hourMinutes == true){
            $str .= " " . $separator . " ";
        }
        if($hourMinutes == true){
            $str .= $date->format("H:i");
        }
        return $str;
    }

}