<?php 

require_once(dirname(__DIR__) . "/objects/Model.php");

class User extends Model {

    use Timeable;

    const STORAGE = "users";
    const COLUMNS = [
      "id" => true,
      "pseudo" => false,
      "password" => false,
      "firstName" => false,
      "lastName" => false,
      "rank" => true,
      "email" => false,
      "description" => true,
      "profile_pic" => true,
      "creation_date" => true
    ];

	private $_pseudo;
	private $_password;
	private $_firstName;
	private $_email;
	private $_lastName;
	private $_rank;
	private $_description;
	private $_profilePic;
	private $_creationDate;

	public function __construct($data = []){
		parent::__construct($data);
	}

    /**
     * Envoie une notification à chaque utilisateur qui follow une ou plusieurs des catégories spécifiées
     * @param $categories Category[]
     * @param $name string
     * @param $content string
     * @param $href string
     * @return int
     */
	public static function sendFollowNotifications($categories, $name, $content, $href){
        $categoryString = "";
        for($i = 0; $i < count($categories); $i++){
            if($i == 0) $categoryString = "AND ";
            $categoryString = $categoryString."users_followed_categories.category_id=".$categories[$i]->getId();
            if($i != count($categories)-1) $categoryString = $categoryString." OR ";
        }
        return SQL::insert(Notification::STORAGE,
            "(target, name, content, href, type) 
            SELECT users.id,'".$name."','".$content."','".$href."','".Notification::TYPE_ARTICLE."'
            FROM users,users_followed_categories WHERE users_followed_categories.user_id = users.id ".
            $categoryString);
	}

    /**
     * Retire une catégorie des follow de l'utilisateur
     * @param $categoryId int
     * @return false|PDOStatement
     */
	public function unfollowCategory($categoryId){
        return SQL::delete(Category::FOLLOW_STORAGE,
            ["user_id" => $this->getId(), "category_id" => $categoryId]);
    }

    /**
     * Ajoute une catégorie en tant que suivie à l'utilisateur
     * @param $category Category
     * @return int
     */
	public function followCategory($category){
        return SQL::insert(Category::FOLLOW_STORAGE,
            ["category_id" => $category->getId(), "user_id" => $this->getId()]);
    }

    /**
     * Retourne la liste des catégories follow par l'utilisateur
     * @return Category[]
     */
	public function getFollowedCategories(){
        return Category::getAll(["user_id" => $this->getId()],
            null, null, null, [Category::FOLLOW_STORAGE =>
                [Category::STORAGE.".id", Category::FOLLOW_STORAGE.".category_id"]]);
    }

    /**
     * Retourne true si les paramètres sont valides, false sinon
     * @param $first_name string
     * @param $last_name string
     * @param $pseudo string
     * @param $password string
     * @param $email string
     * @return bool
     */
    public static function checkParameters($first_name, $last_name, $pseudo, $password, $email){
        return (
            preg_match("#^[a-zA-Z]{1,32}$#", $first_name) &&
            preg_match("#^[a-zA-Z]{1,32}$#", $last_name) &&
            preg_match("#^[a-zA-Z0-9-]{3,16}$#", $pseudo) &&
            filter_var($email, FILTER_VALIDATE_EMAIL) &&
            static::checkPassword($password)
        );
    }

    /**
     * Retourne true si le password est valide, false sinon
     * @param $password string
     * @return bool
     */
    public static function checkPassword($password){
        return(
            preg_match("#[a-z]#", $password) &&
            preg_match("#[A-Z]#", $password) &&
            preg_match("#[0-9]#", $password) &&
            preg_match("#\W#", $password) &&
            strlen($password) >= 8 &&
            strlen(password_hash($password, PASSWORD_DEFAULT)) <= 256
        );
    }

    /**
     * Connecte l'utilisateur
     */
	public function connect(){
        setcookie('user_id', $this->getId(), time() + 365*24*3600, "/");
        $_COOKIE["user_id"] = $this->getId();
        setcookie('user_password', $this->getPassword(), time() + 365*24*3600, "/");
        $_COOKIE["user_password"] = $this->getPassword();
    }

    /**
     * Déconnecte l'utilisateur actuel
     */
	public static function disconnect(){
        if(isset($_COOKIE["user_id"])){
            unset($_COOKIE["user_id"]);
            setcookie("user_id", null, -1, "/");
        }
        if(isset($_COOKIE["user_password"])){
            unset($_COOKIE["user_password"]);
            setcookie("user_password",  null, -1, "/");
        }
    }

    /**
     * Retourne l'utilisateur connecté
     * @return User
     */
    public static function getConnectedUser(){
        return new User($_COOKIE["user_id"]);
    }

    /**
     * Retourne true si l'utilisateur est connecté, false sinon
     * @return bool
     */
    public static function isConnected(){
        if(isset($_COOKIE["user_id"]) && isset($_COOKIE["user_password"])){
            $user = new User($_COOKIE["user_id"]);
            if(isset($user) && hash_equals($_COOKIE["user_password"], $user->getPassword())){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Retourne un utilisateur par son pseudo
     * @param $pseudo string
     * @return Model
     */
	public static function getByPseudo($pseudo){
	    return static::select(["pseudo" => $pseudo]);
    }

    /**
     * Envoi un message a un utilisateur
     * @param $target User
     * @param $content String
     */
	public function sendMessage($target, $content){
        $message = new Message();
        $message->setSender($this->getId());
        $message->setContent($content);
        $message->setTarget($target->getId());
        $message->save();
        $notifications = $target->getNotifications();
        foreach ($notifications as $notification){
            if($notification->getType() == Notification::TYPE_MESSAGE){
                $notificationSender = (int)$notification->getMetadata();
                if($notificationSender == $this->getId()){
                    $notification->delete();
                }
            }
        }
        $notification = new Notification();
        $notification->setTarget($target->getId());
        $notification->setName($this->getPseudo()." vous a envoyé un message");
        $notification->setContent($content);
        $notification->setHref("/dashboard/messages?foreigner=".$this->getId());
        $notification->setType(Notification::TYPE_MESSAGE);
        $notification->setMetadata($this->getId());
        $notification->save();
    }

    /**
     * Retourne une array sous la forme: L'id de la target => la liste des messages
     * @return array
     */
	public function getMessageMap(){
        $messages = $this->getMessages();
        $toReturn = [];
        foreach ($messages as $message){
            if($this->getId() == $message->getSender()){
                if(!isset($toReturn[$message->getTarget()])){
                    $toReturn[$message->getTarget()] = [];
                }
                array_push($toReturn[$message->getTarget()], $message);
            } else if($this->getId() == $message->getTarget()){
                if(!isset($toReturn[$message->getSender()])){
                    $toReturn[$message->getSender()] = [];
                }
                array_push($toReturn[$message->getSender()], $message);
            }
        }
        return $toReturn;
    }

    /**
     * Retourne la liste des messages envoyés ou reçus de l'utilisateur
     * @return Message[]
     */
	public function getMessages(){
	    return Message::getAll(["target" => $this->getId(), "sender" => $this->getId()], null, null,null,[],[],"OR");
    }

    /**
     * Retourne la liste des notifications de l'utilisateur
     * @param int $page
     * @param null $limit
     * @return Notification[]
     */
	public function getNotifications($page = null, $limit = null){
	    return Notification::getAll(["target" => $this->getId()], "TIMESTAMP(creation_date) DESC", $limit, is_null($page) ? $page : $page*9);
    }

    /**
     * Retourne true si l'utilisateur a déjà noté l'article
     * @param $post Post
     * @return bool
     */
	public function hasRatedPost($post){
        return SQL::select(Post::RATING_STORAGE, ["user_id" => $this->getId(), "post_id" => $post->getId()])->rowCount() > 0;
    }

    /**
     * Note un article ou change la précédente note si elle existe
     * @param $post Post
     * @param $rating int
     */
    public function ratePost($post, $rating){
	    if(!$this->hasRatedPost($post)){
            SQL::insert(Post::RATING_STORAGE, ["post_id" => $post->getId(), "user_id" => $this->getId(), "rating" => $rating]);
        } else {
            SQL::update(Post::RATING_STORAGE, ["rating" => $rating], ["user_id" => $this->getId(), "post_id" => $post->getId()]);
        }
    }

	protected function setId($id){
		$id = (int) $id;
		if($id >= 0){
			$this->_id = $id;
		}
	}

	public function setPseudo($pseudo){
		if(is_string($pseudo) && preg_match("#[a-zA-Z0-9-]{3,16}#", $pseudo)){
			$this->_pseudo = $pseudo;
		}
	}

	public function setPassword($password){
		if(is_string($password) && preg_match("#[a-z]#", $password) && preg_match("#[A-Z]#", $password) && preg_match("#[0-9]#", $password) && preg_match("#\W#", $password) && strlen($password) >= 8 && strlen(password_hash($password, PASSWORD_DEFAULT)) <= 256){
			$this->_password = $password;
		}
	}

	public function setFirstName($firstName){
		if(is_string($firstName) && preg_match("#[a-zA-Z]{1,32}#", $firstName)){
			$this->_firstName = $firstName;
		}
	}

	public function setLastName($lastName){
		if(is_string($lastName) && preg_match("#[a-zA-Z]{1,32}#", $lastName)){
			$this->_lastName = $lastName;
		}
	}

	public function setRank($rank){
		$rank = (int) $rank;
		if($rank >= 0){
			$this->_rank = $rank;
		}
	}

	public function setDescription($description){
		if(is_string($description)){
			$this->_description = $description;
		}
	}

	public function setProfile_pic($profilePic){
	    $this->_profilePic = $profilePic;
    }

	protected function setCreation_date($creationDate){
		$this->_creationDate = $creationDate;
	}

	public function setEmail($email){
        $this->_email = $email;
    }

	public function getId(){ return $this->_id; }
	public function getPseudo(){ return $this->_pseudo; }
	public function getPassword(){ return $this->_password; }
	public function getFirstName(){ return $this->_firstName; }
	public function getLastName(){ return $this->_lastName; }
	public function getRank(){ return $this->_rank; }
	public function getDescription(){ return $this->_description; }
	public function getCreation_date(){ return $this->_creationDate; }
	public function getProfile_pic(){ return $this->_profilePic; }
	public function getEmail(){ return $this->_email; }


}