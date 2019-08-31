<?php

class Notification extends Model {

    use Timeable;

    const STORAGE = "notifications";
    const COLUMNS = [
      "id" => true,
      "target" => false,
      "name" => false,
      "content" => false,
      "href" => false,
      "seen" => true,
      "type" => false,
      "metadata" => false,
      "creation_date" => true
    ];

    const TYPE_ADMIN = "admin";
    const TYPE_MESSAGE = "message";
    const TYPE_ARTICLE = "article";

    private $_target;
    private $_name;
    private $_content;
    private $_href = "/home";
    private $_seen;
    private $_type;
    private $_metadata = null;
    private $_creationDate;

    public function __construct($data = []){
        parent::__construct($data);
    }

    /**
     * Envoie une notification à tout les utilisateurs enregistrés
     * @param $name string
     * @param $content string
     * @param $href string
     * @param $metadata string
     */
    public static function sendGlobalNotification($name, $content, $href, $metadata){
        SQL::insert(static::STORAGE, "(target,name,type,content,href,metadata) SELECT id,'$name','".static::TYPE_ADMIN."','$content','$href','$metadata' FROM ".User::STORAGE);
    }

    protected function setId($id){
        $id = (int) $id;
        if($id > 0){
            $this->_id = $id;
        }
    }

    public function setTarget($target){
        $target = (int) $target;
        if($target >= 0) {
            $this->_target = $target;
        }
    }

    public function setName($name){
        if(is_string($name)){
            $this->_name = $name;
        }
    }

    public function setContent($content){
        if(is_string($content)){
            $this->_content = $content;
        }
    }

    public function setHref($href){
        if(is_string($href)){
            $this->_href = $href;
        }
    }

    public function setSeen($seen){
        $this->_seen = $seen;
    }

    public function setType($type){
        if(is_string($type)){
            $this->_type = $type;
        }
    }

    public function setMetadata($metadata){
        $this->_metadata = $metadata;
    }

    public function setCreation_date($creationDate){
        $this->_creationDate = $creationDate;
    }

    public function getId(){ return $this->_id; }
    public function getTarget(){ return $this->_target; }
    public function getName(){ return $this->_name; }
    public function getContent(){ return $this->_content; }
    public function getHref(){ return $this->_href; }
    public function getSeen(){ return $this->_seen; }
    public function getType(){ return $this->_type; }
    public function getMetadata(){ return $this->_metadata; }
    public function getCreation_date(){ return $this->_creationDate; }

}