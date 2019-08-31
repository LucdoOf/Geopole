<?php
/**
 * Utilitaire de gestion de pagination
 * Class Pager
 */
class Pager
{
    /**
     * @param $itemCount int
     * @param $maxPerPage int
     * @param $actualPage int
     * @param $buildHref Closure
     * @return string
     */
    public static function buildPager($itemCount, $maxPerPage, $actualPage, $buildHref){
        $numberOfPage = ceil($itemCount/$maxPerPage);
        $pager = "";
        if($numberOfPage > 1 && $actualPage !== 0){
            $pager .= "<a class='previous-page' href='".$buildHref($actualPage-1)."'></a>";
            $pager .= "<a class='first-page' href='".$buildHref(0)."'>Première page</a>";
        }
        $pager .= "<span class='actual-page'>".($actualPage+1)."</span>";
        if($numberOfPage > 1 && $numberOfPage-1 > $actualPage){
            $pager .= "<a class='last-page' href='".$buildHref($numberOfPage-1)."'>Dernière page</a>";
            $pager .= "<a class='next-page' href='".$buildHref($actualPage+1)."'></a>";
        }
        return $pager;
    }
}