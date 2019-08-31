<?php

require_once(dirname(__DIR__) . "/objects/Model.php");

class Category extends Model {

    const STORAGE = "categories";
    const FOLLOW_STORAGE = "users_followed_categories";
    const COLUMNS = [
      "id" => true,
      "name" => false
    ];

	private $_name;

    /**
     * Représente une catégorie d'un article
     * Category constructor.
     * @param $data
     */
	public function __construct($data = []){
		parent::__construct($data);
	}

    /**
     * Récupère une catégorie par son nom
     * @param $name string
     * @return Category
     */
	public static function getByName($name){
	    return static::select(["name" => $name]);
    }


	protected function setId($id){
		$id = (int) $id;
		if($id >= 0){
			$this->_id = $id;
		}
	}

	public function setName($name){
		if(is_string($name) && !empty($name)){
			$this->_name = $name;
		} else {
			$this->_name = "Catégorie inconnue";
		}
	}

	public function getId(){ return $this->_id; }
	public function getName(){ return $this->_name; }

}





