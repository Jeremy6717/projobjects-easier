<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class Country extends DbObject{

    /** @var string */
    protected $name;
    
    function __construct($id=0, $name='', $inserted='') {
        $this->name = $name;
        parent::__construct($id, $inserted);
    }
        
   /**
     * @param int $id
     * @return DbObject
     */
    public static function get($id) {
        $sql = '
			SELECT cou_name, cou_id, cou_inserted
			FROM country
			WHERE cou_id = :id
			ORDER BY cit_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new City(
                    $row['cou_name'], $row['cou_id'],$row['cou_inserted']
                );
                return $currentObject;
            }
        }

        return false;
    }

    /**
     * @return DbObject[]
     */
    public static function getAll() {
        $returnList = array();

        $sql = '
			SELECT cou_name, cou_id, cou_inserted
			FROM country
			WHERE cou_id > 0
			ORDER BY cou_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $currentObject = new City(
                   $row['cou_name'], $row['cou_id'],$row['cou_inserted']
                );
                $returnList[] = $currentObject;
            }
        }

        return $returnList;
    }

    /**
     * @return array
     */
    public static function getAllForSelect() {
        $returnList = array();

        $sql = '
			SELECT cou_name, cou_id, cou_inserted
			FROM country
			WHERE cou_id > 0
			ORDER BY cou_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList['cou_name'] = $row['cou_id'];
            }
        }

        return $returnList;
    }

    /**
     * @return bool
     */
    public function saveDB() {
        if ($this->id > 0) {
            $sql = '
				UPDATE country
				SET cou_name = :name,
                                cou_id = :id,
                                cou_inserted = :inserted
				WHERE cou_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':name', $this->cou_name);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':inserted', $this->inserted);
            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
				INSERT INTO country(cou_name, cou_id, cou_inserted)
				VALUES (:name,:id, :inserted)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':name', $this->name);
            $stmt->bindValue(':id', $this->id->id, \PDO::PARAM_INT);
            $stmt->bindValue(':inserted', $this->inserted);


            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                $this->id = Config::getInstance()->getPDO()->lastInsertId();
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function deleteById($id) {
        $sql = '
			DELETE FROM country WHERE cou_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        } else {
            return true;
        }
        return false;
    }

  

}


