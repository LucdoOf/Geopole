<?php

require_once(dirname(__DIR__) . "/objects/Model.php");

class Debate extends Model
{

    use Timeable;

    const STORAGE = "debates";
    const COLUMNS = [
      "id" => true,
      "author" => false,
      "title" => false,
      "pinned" => false,
      "creation_date" => true,
      "description" => false,
      "image" => false,
      "pending" => false,
      "slug" => false
    ];

    private $_author;
    private $_title;
    private $_pinned;
    private $_creationDate;
    private $_description;
    private $_image;
    private $_pending;
    private $_slug;

    /**
     * Représente un débat
     * Debate constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    /**
     * Retire toutes les options du débat
     * @return false|PDOStatement
     */
    public function cleanOptions(){
        return SQL::delete(DebateOption::STORAGE, ["debate_id" => $this->getId()]);
    }

    /**
     * Répond au débat
     * @param $content string
     * @param $title string
     * @param $optionId int
     * @param $author int
     * @return DebateResponse
     */
    public function reply($content, $title, $optionId, $author){
        $response = new DebateResponse();
        $response->setContent($content);
        $response->setTitle($title);
        $response->setOption_id($optionId);
        $response->setAuthor($author);
        $response->setDebate_id($this->getId());
        $response->save();
        return $response;
    }

    /**
     * Compte le nombre de réponses apportées au débat
     * @param bool $heading
     * @return int
     */
    public function countResponses($heading = true){
        $where = ["debate_id" => $this->getId()];
        if($heading == true) $where["parent_id"] = -1;
        return (int) SQL::select(DebateResponse::STORAGE, $where,
            null,null,null,[], ["count(id) as counter"])->fetch()["counter"];
    }

    /**
     * Retourne la liste des réponses sans parents
     * @param null|int $limit
     * @param null|int $offset
     * @return DebateResponse[]
     */
    public function getHeadingResponses($limit = null, $offset = null){
        return DebateResponse::getAll(["debate_id" => $this->getId(), "parent_id" => -1], "TIMESTAMP(creation_date) DESC", $limit, $offset);
    }

    /**
     * Retourne la liste des options du débat
     * @return DebateOption[]
     */
    public function getOptions(){
        return DebateOption::getAll(["debate_id" => $this->getId()]);
    }

    /**
     * Ajoute une option au débat
     * @param $name string
     * @return DebateOption
     */
    public function addOption($name){
        $option = new DebateOption();
        $option->setDebate_id($this->getId());
        $option->setName($name);
        $option->save();
        return $option;
    }

    /**
     * Retourne la liste des débats attachés
     * @param bool $pending
     * @return Debate[]
     */
    public static function getPinneds($pending = false){
        return static::getAll(["pinned" => true, "pending" => $pending], "TIMESTAMP(creation_date) DESC");
    }

    /**
     * Retourne la liste des débats actifs
     * @param null|int $limit
     * @return Debate[]
     */
    public static function getActives($limit = null){
        return static::getAll(["pending" => false], "TIMESTAMP(creation_date) DESC", $limit);
    }

    /**
     * Retourne la liste des débats en attente
     * @return Debate[]
     */
    public static function getPendings(){
        return static::getAll(["pending" => true], "TIMESTAMP(creation_date) ASC");
    }

    /**
     * Retourne un débat par son slug
     * @param $slug
     * @return Debate
     */
    public static function getBySlug($slug){
        return static::select(["slug" => $slug]);
    }

    protected function setId($id){
        $id = (int)$id;
        if ($id >= 0){
            $this->_id = $id;
        }
    }

    public function setAuthor($author){
        $author = (int)$author;
        if($author >= 0){
            $this->_author = $author;
        }
    }

    public function setTitle($title){
        if(is_string($title)){
            $this->_title = $title;
        }
    }

    public function setDescription($description){
        if(is_string($description)){
            $this->_description = htmlspecialchars($description);
        }
    }

    public function setPending($pending){
        $this->_pending = $pending;
    }

    public function setPinned($pinned){
        $this->_pinned = $pinned;
    }

    public function setCreation_date($date){
        $this->_creationDate = $date;
    }

    public function setImage($image){
        $this->_image = $image;
    }

    public function setSlug($slug){
        $this->_slug = $slug;
    }

    public function getId(){ return $this->_id; }
    public function getAuthor(){ return $this->_author; }
    public function getTitle(){ return $this->_title; }
    public function getDescription(){ return $this->_description; }
    public function getPinned(){ return $this->_pinned; }
    public function getCreation_date(){ return $this->_creationDate; }
    public function getImage(){ return $this->_image; }
    public function getSlug(){ return $this->_slug; }
    public function getPending(){ return $this->_pending; }


}
