<?php
/**
 * Générateur de commandes SQL
 * Class SQL
 */
class SQL
{

    /** @var PDO Instance de PDO */
    private static $db;

    /**
     * Connection à la BDD
     * @return PDO
     */
    private static function db(){
        if(isset(self::$db)) return self::$db;
        $db = new PDO('mysql:host=mysql-lucasnonime.alwaysdata.net;port=3306;dbname=lucasnonime_blogdb;charset=utf8', '169783', 'root');
        if(IS_DEV === true){
            $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        }
        self::$db = $db;
        return $db;
    }

    /**
     * @param $tableName string Nom de la table
     * @param array|string $where [] paramètres de sélection
     * @param $order string ordre de tri
     * @param $limit int limite de la requête
     * @param $offset int offset de la requête
     * @param array $join_tables
     * @param array $additionnalSelect
     * @param null $customWhere
     * @return false|PDOStatement
     */
    public static function select($tableName, $where = [], $order = null, $limit = null, $offset = null, $join_tables = [], $additionnalSelect = [], $customWhere = null){
        $req = "SELECT $tableName.*";
        if(!empty($additionnalSelect)){
            $req .= "," . implode(",",$additionnalSelect);
        }
        $req .= " FROM $tableName";
        if(!empty($join_tables)){
            $joinClause = [];
            foreach ($join_tables as $table => $params){
                $joinClause[] = "INNER JOIN $table ON $params[0] = $params[1]";
            }
            $req .= " " . implode(" ", $joinClause);
        }
        if(is_array($where)) {
            if (!empty($where)) {
                $whereClause = [];
                foreach ($where as $key => $value) {
                    if (is_numeric($value)) {
                        $whereClause[] = $key . "=" . $value;
                    } else {
                        $whereClause[] = $key . "=" . "'" . $value . "'";
                    }
                }
                $req .= " WHERE " . implode(is_null($customWhere) ? " AND " : " " . $customWhere . " ", $whereClause);
            }
        } else if(is_string($where)){
            if(!empty($where)) {
                $req .= " WHERE $where";
            }
        }
        if(!is_null($order)) $req.= " ORDER BY " . $order;
        if(!is_null($limit)) $req.= " LIMIT " . $limit;
        if(!is_null($offset)) $req.= " OFFSET " . $offset;
        return self::db()->query($req);
    }

    /**
     * Recherche une ligne dans une base en fonction des colonnes passées en arguments
     * @param $tableName
     * @param $columns
     * @param $query
     * @param null $order
     * @param null $limit
     * @param null $offset
     * @return false|PDOStatement
     */
    public static function search($tableName, $columns, $query, $order = null, $limit = null, $offset = null){
        $req = "SELECT * FROM $tableName";
        if(!empty($columns)){
            $whereClause = [];
            foreach ($columns as $column){
                $whereClause[] = $column ." LIKE '%".$query."%'";
            }
            $req .= " WHERE " . implode(" OR ", $whereClause);
        }
        if(!is_null($order)) $req.= " ORDER BY " . $order;
        if(!is_null($limit)) $req.= " LIMIT " . $limit;
        if(!is_null($offset)) $req.= " OFFSET " . $offset;
        return self::db()->query($req);
    }

    /**
     * Insére une ligne dans une table SQL
     * @param $tableName string Nom de la table
     * @param $data [] paramètres d'insertion
     * @return int
     */
    public static function insert($tableName, $data){
        $req = "INSERT INTO $tableName";
        if(is_array($data)) {
            $cols = [];
            $values = [];
            foreach ($data as $col => $value) {
                $cols[] = $col;
                if (is_numeric($value)) {
                    $values[] = $value;
                } else {
                    $values[] = "'$value'";
                }
            }
            $req .= " (" . implode(",", $cols) . ") VALUES(" . implode(",", $values) . ")";
        } else if(is_string($data)){
            $req .= " $data";
        }
        self::db()->query($req);
        return (int)self::db()->lastInsertId();
    }

    /**
     * Supprime une ligne d'une table SQL
     * @param $tableName string Nom de la table
     * @param $where [] conditions de suppressions
     * @return false|PDOStatement
     */
    public static function delete($tableName, $where){
        $req = "DELETE FROM $tableName";
        if(!empty($where)) {
            $whereClause = [];
            foreach ($where as $key => $value) {
                if(is_numeric($value)){
                    $whereClause[] = $key . "=" . $value;
                } else {
                    $whereClause[] = $key . "=" . "'" . $value . "'";
                }
            }
            $req .= " WHERE " . implode(" AND ", $whereClause);
        }
        return self::db()->query($req);;
    }

    /**
     * Met à jour une ligne SQL
     * @param $tableName string Nom de la table
     * @param $data [] Nouvelles données
     * @param $where [] conditions d'update
     * @return false|PDOStatement
     */
    public static function update($tableName, $data, $where){
        $req = "UPDATE $tableName SET ";
        $setClause = [];
        foreach ($data as $col => $value){
            if(is_numeric($value)){
                $setClause[] = $col . "=" . $value;
            } else {
                $setClause[] = $col . "=" . "'" . $value . "'";
            }
        }
        $req .= implode(",",$setClause);
        if(!empty($where)) {
            $whereClause = [];
            foreach ($where as $key => $value) {
                if(is_numeric($value)){
                    $whereClause[] = $key . "=" . $value;
                } else {
                    $whereClause[] = $key . "=" . "'" . $value . "'";
                }
            }
            $req .= " WHERE " . implode(" AND ", $whereClause);
        }
        return self::db()->query($req);
    }

    /**
     * Truncate une table
     * @param $tableName string Nom de la table
     * @return false|PDOStatement
     */
    public static function truncate($tableName){
        $req = "TRUNCATE $tableName";
        return self::db()->query($req);
    }

    public static function selectMax($tableName, $maxColumn){
        $req = "SELECT * FROM $tableName WHERE $maxColumn = (SELECT MAX($maxColumn) FROM $tableName)";
        return self::db()->query($req);
    }

    /***
     * Convertit un résultat SQL en un objet associé
     * @param $pdoStatement PDOStatement
     * @param $objectClass string Classe de l'objet à créer
     * @return stdClass|null
     */
    public static function instantiate($pdoStatement, $objectClass){
        return new $objectClass($pdoStatement->fetch());
    }

    /**
     * Convertit une liste de résultats SQL en une liste d'objet associé
     * @param $pdoStatement PDOStatement
     * @param $objectClass string Classe de l'objet à créer
     * @return array
     */
    public static function instantiateAll($pdoStatement, $objectClass){
        $return = [];
        while($data = $pdoStatement->fetch()){
            $return[] = new $objectClass($data);
        }
        return $return;
    }



}