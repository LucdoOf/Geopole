<?php
/**
 * Tout les objets possédant une date de création sont enfant de cette interface
 * Trait Timeable
 */
trait Timeable
{

    /**
     * Retourne la date de création de l'objet
     * @return DateTime|null
     */
    public function creationDate(){
        if(isset($this->_creationDate)){
            try {
                return new DateTime($this->_creationDate);
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Retourne la chaine de caractère de la date de création
     * @param $separator string Séparateur
     * @param bool $dayMonthYear Afficher
     * @param bool $hourMinutes Afficher
     * @return string
     */
    public function parseCreationDate($separator = "à", $dayMonthYear = true, $hourMinutes = true){
        return Str::parseDate($this->creationDate(), $separator, $dayMonthYear, $hourMinutes);
    }

}