<?php

require_once(dirname(__DIR__) . "/objects/Model.php");

class DebateResponse extends Model {

    use Timeable;

    const STORAGE = "debates_responses";
    const REACT_STORAGE = "debates_responses_likes";
    const COLUMNS = [
      "id" => true,
      "debate_id" => false,
      "content" => false,
      "option_id" => false,
      "title" => false,
      "author" => false,
      "parent_id" => false,
      "creation_date" => true
    ];

    private $_debateId;
    private $_content;
    private $_optionId;
    private $_title;
    private $_author;
    private $_parentId = -1;
    private $_creationDate;

    /**
     * Retourne true si l'utilisateur a réagi à la réponse avec l'action donné, false sinon
     * @param $action "like"|"dislike"
     * @param $user User
     * @return bool
     */
    public function userHasReacted($action, $user){
        return SQL::select($this::REACT_STORAGE, ["action" => $action, "user_id" => $user->getId(), "response_id" => $this->getId()])->rowCount() > 0;
    }

    /**
     * Ajoute une réaction à la réponse
     * @param $user User
     * @param $action "like"|"dislike"
     */
    public function react($user, $action){
        $this->clearReactions($user);
        SQL::insert($this::REACT_STORAGE, ["user_id" => $user->getId(), "response_id" => $this->getId(), "action" => $action]);
    }

    /**
     * Retire les réactions de la réponse pour un utilisateur
     * @param $user User
     */
    public function clearReactions($user){
        SQL::delete($this::REACT_STORAGE, ["user_id" => $user->getId(), "response_id" => $this->getId()]);
    }

    /**
     * Récupère la liste des sous réponses de cette réponse
     * @return DebateResponse[]
     */
    public function getSubResponses(){
        return $this::getAll(["parent_id" => $this->getId()], "TIMESTAMP(creation_date) ASC");
    }

    /**
     * Répond à une réponse
     * @param $content string
     * @param $author int
     * @return DebateResponse
     */
    public function subReply($content, $author){
        $response = new DebateResponse();
        $response->setDebate_id($this->getDebate_id());
        $response->setContent($content);
        $response->setOption_id(-1);
        $response->setTitle("");
        $response->setParent_id($this->getId());
        $response->setAuthor($author);
        $response->save();
        return $response;
    }

    protected function setId($id){
        $id = (int) $id;
        if($id > 0){
            $this->_id = $id;
        }
    }

    public function setDebate_id($id){
        $id = (int) $id;
        if($id > 0){
            $this->_debateId = $id;
        }
    }

    public function setContent($content){
        if(is_string($content)){
            $this->_content = $content;
        }
    }

    public function setOption_id($id){
        $this->_optionId = $id;
    }

    public function setTitle($title){
        if(is_string($title)){
            $this->_title = $title;
        }
    }

    public function setAuthor($author){
        $author = (int) $author;
        if($author > 0){
            $this->_author = $author;
        }
    }

    protected function setParent_id($parentId){
        $this->_parentId = $parentId;
    }

    protected function setCreation_date($date){
        $this->_creationDate = $date;
    }

    public function getId(){ return $this->_id; }
    public function getDebate_id(){ return $this->_debateId; }
    public function getContent(){ return $this->_content; }
    public function getOption_id(){ return $this->_optionId; }
    public function getTitle(){ return $this->_title; }
    public function getAuthor(){ return $this->_author; }
    public function getParent_id(){ return $this->_parentId; }
    public function getCreation_date(){ return $this->_creationDate; }

}
