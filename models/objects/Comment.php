<?php

require_once(dirname(__DIR__) . "/objects/Model.php");

class Comment extends Model {

    use Timeable;

    const STORAGE = "comments";
    const REACT_STORAGE = "posts_comments_likes";
    const COLUMNS = [
        "id" => true,
        "post_id" => false,
        "author" => false,
        "content" => false,
        "parent_id" => false,
        "creation_date" => true
    ];

	private $_postId;
	private $_author;
	private $_content;
	private $_parentId = -1;
	private $_creationDate;

    /**
     * Représente un commentaire dans un article
     * Comment constructor.
     * @param $data
     */
	public function __construct($data = []){
		parent::__construct($data);
	}

    /**
     * Compte le nombre d'action effectuées sur ce commentaire
     * @param $action "like"|"dislike"
     * @return int
     */
	public function countReactions($action){
	    return (int)SQL::select($this::REACT_STORAGE, ["comment_id" => $this->getId(), "action" => $action],
            null,null,null,[],["count(id) AS counter"])->fetch()["counter"];
    }

    /**
     * Ajoute une réaction au commentaire
     * @param $user User
     * @param $action "like"|"dislike"
     */
	public function react($user, $action){
	    $this->clearReactions($user);
	    SQL::insert($this::REACT_STORAGE, ["user_id" => $user->getId(), "comment_id" => $this->getId(), "action" => $action]);
    }

    /**
     * Retire les réactions du commentaire pour un utilisateur
     * @param $user User
     */
    public function clearReactions($user){
        SQL::delete($this::REACT_STORAGE, ["user_id" => $user->getId(), "comment_id" => $this->getId()]);
    }

    /**
     * Retourne true si l'utilisateur a réagi au commentaire avec l'action donné, false sinon
     * @param $action "like"|"dislike"
     * @param $user User
     * @return bool
     */
	public function userHasReacted($action, $user){
        return SQL::select($this::REACT_STORAGE, ["action" => $action, "user_id" => $user->getId(), "comment_id" => $this->getId()])->rowCount() > 0;
    }

    /**
     * Récupère la liste des commentaires enfants
     * @return Comment[]
     */
	public function getSubComments(){
	    return parent::getAll(["parent_id" => $this->getId()]);
    }

    /**
     * Ajoute un sous-commentaire au commentaire
     * @param $author
     * @param $content
     */
    public function subComment($author, $content){
        $comment = new Comment(["parent_id" => $this->getId(), "post_id" => $this->getPost_id(), "content" => $content, "author" => $author]);
        $comment->save();
    }

    /**
     * Retourne la liste des commentaires laissés par un utilisateur
     * @param $user User
     * @return array
     */
    public static function getUserComments($user){
        return static::getAll(["author" => $user->getId()], "TIMESTAMP(creation_date) DESC");
    }

	protected function setId($id){
		$id = (int) $id;
		if($id >= 0){
			$this->_id = $id;
		}
	}

	protected function setPost_id($id){
		$id = (int) $id;
		if($id >= 0){
			$this->_postId = $id;
		}
	}

	protected function setAuthor($author){
		$author = (int) $author;
		if($author >= 0){
			$this->_author = $author;
		}
	}

	public function setContent($content){
		if(is_string($content) && !empty($content)){
			$this->_content = htmlspecialchars($content);
		} else {
			$this->_content = "Commentaire vide";
		}
	}

	protected function setParent_id($id){
	    $this->_parentId = $id;
    }

	protected function setCreation_date($creationDate){
		$this->_creationDate = $creationDate;
	}

	public function getId(){ return $this->_id; }
	public function getPost_id(){ return $this->_postId; }
	public function getAuthor(){ return $this->_author; }
	public function getContent(){ return $this->_content; }
	public function getParent_id(){ return $this->_parentId; }
	public function getCreation_date(){ return $this->_creationDate; }

}
