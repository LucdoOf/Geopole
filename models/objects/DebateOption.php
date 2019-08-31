<?php

require_once(dirname(__DIR__) . "/objects/Model.php");

class DebateOption extends Model {

    const STORAGE = "debates_options";
    const COLUMNS = [
      "id" => true,
      "name" => false,
      "debate_id" => false
    ];

    private $_name;
    private $_debateId;

    /**
     * Représente une option de réponse d'un débat
     * DebateOption constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    /**
     * Retoure l'index de l'option parmis les options du débat
     * @return int
     */
    public function getIndex(){
        $debate = new Debate($this->getDebate_id());
        $options = $debate->getOptions();
        for($i = 0; $i < count($options); $i++){
            if($options[$i]->getId() == $this->getId()){
                return $i;
            }
        }
        return -1;
    }

    /**
     * Retourne la popularité de l'option en pourcentage
     * @return float|int
     */
    public function getPopularity(){
        $total = (int)SQL::select(DebateResponse::STORAGE, ["debate_id" => $this->getDebate_id()],
            null,null,null,[],["count(id) as counter"])->fetch()["counter"];
        $number = (int)SQL::select(DebateResponse::STORAGE, ["debate_id" => $this->getDebate_id(), "option_id" => $this->getId()],
            null,null,null,[],["count(id) as counter"])->fetch()["counter"];
        if($total == 0) return 0;
        return ($number/$total)*100;
    }

    protected function setId($id){
        $id = (int)$id;
        if ($id >= 0){
            $this->_id = $id;
        }
    }

    public function setName($name){
        if(is_string($name)){
            $this->_name = $name;
        }
    }

    public function setDebate_id($debateId){
        $debateId = (int) $debateId;
        if($debateId > 0){
            $this->_debateId = $debateId;
        }
    }

    public function getId(){ return $this->_id; }
    public function getName(){ return $this->_name; }
    public function getDebate_id(){ return $this->_debateId; }


}
