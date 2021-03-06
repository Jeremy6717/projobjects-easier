<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class City extends DbObject {

    /** @var string */
    protected $name;

    /** @var country */ //car clé étrangère dans la tbale city
    protected $country;

    public function __construct($id = 0, $country = null, $name = '',  $inserted ='') {
        $this->name = $name;
        if (empty($country)) {
			$this->country = new Country();
		}
		else {
			$this->country = $country;
		}

        parent::__construct($id, $inserted);
    }

    /**
     * @param int $id
     * @return DbObject
     */
    public static function get($id) {
        $sql = '
			SELECT cit_name, country_cou_id, cit_id, cit_inserted
			FROM city
			WHERE cit_id = :id
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
                     $row['cit_id'], $row['cit_name'], new country($row['country_cou_id']), $row['cit_inserted']
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
			SELECT cit_name, country_cou_id, cit_id, cit_inserted
			FROM city
			WHERE cit_id > 0
			ORDER BY cit_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $currentObject = new City(
                    $row['cit_name'], $row['country_cou_id'], $row['cit_id'], $row['cit_inserted']
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
			SELECT cit_name, country_cou_id, cit_id, cit_inserted
			FROM city
			WHERE cit_id > 0
			ORDER BY cit_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList['cit_name'] = $row['cit_id'];
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
				UPDATE city
				SET cit_name = :name,
                                country_cou_id = :couId,
                                cit_id = :id
                                cit_inserted = :inserted
				WHERE cit_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':name', $this->cit_name);
            $stmt->bindValue(':countryId', $this->country_cou_id);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
				INSERT INTO city (cit_name, country_cou_id, cit_id, cit_inserted)
				VALUES (:name, :couId, :id, :inserted)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':name', $this->name);
            $stmt->bindValue(':couId', $this->name);
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
			DELETE FROM city WHERE cit_id = :citId
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

    /**
     * @return cit_name
     */
    public function getName() {
        return $this->name;
    }
    
    
    function getCountry() {
        return $this->country;
    }


   
}
