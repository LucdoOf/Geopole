<?php

abstract class Model {

    const STORAGE = "";
    const COLUMNS = [];

	protected $_id = 0;

    /**
     * Model constructor.
     * @param array|int $data Données sous forme de tableau associatif
     */
	public function __construct($data = []){
	    if(is_array($data)){
            $this->hydrate($data);
        } else {
            $id = (int)$data;
            if ($id > 0) {
                $this->_id = $id;
                $this->load();
            } 
        }
	}

    /**
     * Récupère des données et en base et hydrate l'objet
     */
	private function load(){
        $req = SQL::select($this::STORAGE, ["id" => $this->_id]);
        if($req->rowCount() > 0){
            $this->hydrate($req->fetch());
        } else {
            $this->_id = 0;
        }
    }

    /**
     * Sauvegarde l'objet en base ou l'insère en fonction de son identifiant
     */
    public function save(){
	    if($this->_id <= 0){
	        $this->_id = SQL::insert($this::STORAGE, $this->toArray(false));
        } else {
	        SQL::update($this::STORAGE, $this->toArray(), ["id" => $this->_id]);
        }
    }

    /**
     * Supprime l'objet en base
     */
    public function delete(){
       if($this->_id > 0){
           SQL::delete($this::STORAGE, ["id" => $this->_id]);
       }
    }

    /**
     * Retourne la liste des propriétés de l'objet listées dans la constante columns
     * @param bool $includeDefault Inclure les champs ayant une valeur SQL par défaut ?
     * @return array
     */
    public function toArray($includeDefault = true){
        $values = [];
	    if(!empty($this::COLUMNS)){
	        foreach ($this::COLUMNS as $column => $default){
	            if($includeDefault == false && $default == true) continue;
                $saveMethod = 'getSave'.ucfirst($column);
                if(method_exists($this, $saveMethod)){
                   $values[$column] = $this->$saveMethod();
                } else {
                    $method = 'get'.ucfirst($column);
                    if (method_exists($this, $method)){
                        $values[$column] = $this->$method();
                    }
                }
            }
        }
	    return $values;
    }

    /**
     * Hydrate l'objet en fonction d'une array associative
     * @param $data []
     */
	private function hydrate($data){
		if($data != false){
	  		foreach ($data as $key => $value){
	   			$method = 'set'.ucfirst($key);
	    		if (method_exists($this, $method)){
	      			$this->$method($value);
	    		}
	  		}
  		}
	}

    /**
     * Retourne la liste des objets concernés
     * @param array|string $where
     * @param null $order
     * @param null $limit
     * @param null $offset
     * @param array $join_tables
     * @param array $additionnalSelect
     * @param null $customWhere
     * @return array
     */
	public static function getAll($where = [], $order = null, $limit = null, $offset = null, $join_tables = [], $additionnalSelect = [], $customWhere = null){
        return SQL::instantiateAll(SQL::select(static::STORAGE, $where, $order, $limit, $offset, $join_tables, $additionnalSelect, $customWhere), static::class);
    }

    /**
     * Recherche un objet avec une clef
     * @param $query
     * @param null $order
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public static function search($query, $order = null, $limit = null, $offset = null){
	    return SQL::instantiateAll(SQL::search(static::STORAGE, array_keys(static::COLUMNS), $query, $order, $limit, $offset), static::class);
    }

    /**
     * Fait une requête select sur la table de l'objet avec les conditions sélectionnées
     * @param $where
     * @param null $order
     * @param null $limit
     * @param null $offset
     * @param array $join_tables
     * @param array $additionnalSelect
     * @return Model
     */
    public static function select($where, $order = null, $limit = null, $offset = null, $join_tables = [], $additionnalSelect = []){
        return new static(SQL::select(static::STORAGE, $where, $order, $limit, $offset, $join_tables, $additionnalSelect)->fetch());
    }

    /**
     * Met a jour un objet ou une liste d'objet
     * @param $data
     * @param $where
     * @return false|PDOStatement
     */
    public static function update($data, $where){
        return SQL::update(static::STORAGE, $data, $where);
    }

    /**
     * Compte le nombre d'objet présent en base
     * @param $where
     * @param array $join_tables
     * @return mixed
     */
    public static function count($where = [], $join_tables = []){
        return SQL::select(static::STORAGE, $where, null, null, null, $join_tables, ["count(".static::STORAGE.".id) AS counter"])->fetch()["counter"];
    }

    /**
     * Retourne le dernier objet créé en base
     * @return Model
     */
    public static function getLatest(){
        return new static(SQL::selectMax(static::STORAGE, "id")->fetch());
    }

    /**
     * Vérifie l'existence d'un Model
     * @return bool
     */
	public function exist(){
		return $this->_id > 0;
	}

}


