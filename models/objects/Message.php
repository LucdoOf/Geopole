<?php

require_once(dirname(__DIR__) . "/objects/Model.php");

class Message extends Model
{

    const STORAGE = "messages";
    const COLUMNS = [
      "id" => true,
      "sender" => false,
      "target" => false,
      "content" => false,
      "seen" => true,
      "creation_date" => true
    ];

    private $_sender;
    private $_target;
    private $_content;
    private $_seen;
    private $_creationDate;

    /**
     * Représente un message dans une conversation
     * Message constructor.
     * @param $data
     */
    public function __construct($data = []){
        parent::__construct($data);
    }

    /**
     * Retourne la liste des messages entre deux utilisateurs
     * @param $user1 int
     * @param $user2 int
     * @param $page int
     * @return Message[]
     */
    public static function getMessagesBeetween($user1, $user2, $page = 0){
        return array_reverse(static::getAll("(sender=$user1 AND target=$user2) OR (sender=$user2 AND target=$user1)",
            "TIMESTAMP(creation_date) DESC", 15, 15*$page));
    }

    /**
     * Compte le nombre de message entre un deux utilisateurs
     * @param $user1 int
     * @param $user2 int
     * @return int
     */
    public static function countMessagesBeetween($user1, $user2){
        return (int)SQL::select(static::STORAGE, "(sender=$user1 AND target=$user2) OR (sender=$user2 AND target=$user1)",
            null,null,null,[],["COUNT(id) AS counter"])->fetch()["counter"];
    }

    /**
     * Met la conversation en vu dans un sens
     * @param $sender int
     * @param $target int
     */
    public static function seeMessages($sender, $target){
        static::update(["seen" => 1], ["sender" => $sender, "target" => $target]);
        $senderObject = new User($sender);
        $notifications = $senderObject->getNotifications();
        foreach ($notifications as $notification){
            if($notification->getType() == Notification::TYPE_MESSAGE){
                $notificationSender = (int)$notification->getMetadata();
                if($notificationSender == $target){
                    $notification->setSeen(true);
                    $notification->save();
                }
            }
        }
    }

    protected function setId($id){
        $id = (int) $id;
        if($id >= 0){
            $this->_id = $id;
        }
    }

    public function setContent($content){
        $this->_content = $content;
    }

    public function setTarget($target){
        $target = (int) $target;
        if($target >= 0){
            $this->_target = $target;
        }
    }

    public function setSender($sender){
        $sender = (int) $sender;
        if($sender >= 0){
            $this->_sender = $sender;
        }
    }

    public function setSeen($seen){
        $this->_seen = (boolean) $seen;
    }

    protected function setCreation_date($creationDate){
        $this->_creationDate = $creationDate;
    }

    public function getId(){ return $this->_id; }
    public function getSender(){ return $this->_sender; }
    public function getTarget(){ return $this->_target; }
    public function getContent(){ return $this->_content; }
    public function getSeen(){ return $this->_seen; }
    public function getCreation_date(){ return $this->_creationDate; }

}