<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 19:18
 */

namespace houseorm\gateway\connection;

use houseorm\config\ConfigInterface;
use houseorm\gateway\datatable\query\delete\DeleteQuery;
use houseorm\gateway\datatable\query\delete\DeleteQueryInterface;
use houseorm\gateway\datatable\query\insert\InsertQuery;
use houseorm\gateway\datatable\query\insert\InsertQueryInterface;
use houseorm\gateway\datatable\query\QueryInterface;
use houseorm\gateway\datatable\query\select\SelectQuery;
use houseorm\gateway\datatable\query\select\SelectQueryInterface;
use houseorm\gateway\datatable\query\traits\BindingsEnum;
use houseorm\gateway\datatable\query\traits\CriteriaQueryTrait;
use houseorm\gateway\datatable\query\update\UpdateQuery;
use houseorm\gateway\datatable\query\update\UpdateQueryInterface;
use houseorm\gateway\datatable\request\QueryRequestInterface;

/**
 * Class PdoConnection
 * @package houseorm\gateway\connection
 */
class PdoConnection implements ConnectionInterface
{

    /**
     * @var ConfigInterface|null
     */
    private $config;

    /**
     * @var \PDO
     */
    private $conn;

    /**
     * PdoConnection constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config = null)
    {
        $this->config = $config;
    }

    /**
     * @return void
     */
    private function establishConnection()
    {
        $host = $this->config->getHost();
        $db   = $this->config->getDatabase();
        $user = $this->config->getUser();
        $pass = $this->config->getPassword();
        $charset = $this->config->getCharset();
        if (!$charset) {
            $charset = 'utf8';
        }
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new \PDO($dsn, $user, $pass, $opt);
        $this->conn = $pdo;
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    public function execute(QueryRequestInterface $queryRequest)
    {
        if (!$this->conn) {
            $this->establishConnection();
        }
        $query = $queryRequest->getQuery();
        $res = [];
        if ($query instanceof SelectQueryInterface) {
            $res = $this->executeSelectQuery($queryRequest);
        }
        if ($query instanceof InsertQueryInterface) {
            $res = $this->executeInsertQuery($queryRequest);
        }
        if ($query instanceof DeleteQueryInterface) {
            $res = $this->executeDeleteQuery($queryRequest);
        }
        if ($query instanceof UpdateQueryInterface) {
            $res = $this->executeUpdateQuery($queryRequest);
        }
        return $res;
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    private function executeSelectQuery(QueryRequestInterface $queryRequest)
    {
        /**
         * @var SelectQueryInterface|SelectQuery| $query
         */
        $query = $queryRequest->getQuery();
        $stmt = $this->conn->prepare($query->getPreparedStatement());
        $stmt = $this->bindWhere($stmt, $query);
        $stmt = $this->bindLimit($stmt, $query);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return [
            'result' => $data,
            'rows' => count($data)
        ];
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    private function executeInsertQuery(QueryRequestInterface $queryRequest)
    {
        /**
         * @var InsertQueryInterface $query
         */
        $query = $queryRequest->getQuery();
        $stmt = $this->conn->prepare($query->getPreparedStatement());
        $res = $stmt->execute($query->getFieldsPart());
        return [
            'result' => $res,
            'rows' => ($res) ? 1 : 0
        ];
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    private function executeDeleteQuery(QueryRequestInterface $queryRequest)
    {
        /**
         * @var DeleteQueryInterface $query
         */
        $query = $queryRequest->getQuery();
        $stmt = $this->conn->prepare($query->getPreparedStatement());
        $stmt = $this->bindWhere($stmt, $query);
        $res = $stmt->execute();
        return [
            'result' => $res,
            'rows' => $stmt->rowCount()
        ];
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    private function executeUpdateQuery(QueryRequestInterface $queryRequest)
    {
        /**
         * @var UpdateQueryInterface|UpdateQuery $query
         */
        $query = $queryRequest->getQuery();
        $stmt = $this->conn->prepare($query->getPreparedStatement());
        $stmt = $this->bindSet($stmt, $query);
        $stmt = $this->bindWhere($stmt, $query);
        $res = $stmt->execute();
        return [
            'result' => $res,
            'rows' => (int)$res
        ];
    }



    /**
     * @return int|null
     */
    public function getLastInsertId()
    {
        if (!$this->conn) {
            $this->establishConnection();
        }
        return $this->conn->lastInsertId();
    }

    /**
     * @return ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param \PDOStatement $stmt
     * @param $query
     * @return \PDOStatement
     */
    private function bindWhere(\PDOStatement $stmt, $query)
    {
        /**
         * @var SelectQuery|InsertQuery|DeleteQuery|UpdateQuery $query
         */
        $where = $query->getWherePart();
        if ($where) {
            foreach ($where as $key => $value) {
                $stmt->bindValue(BindingsEnum::CRITERIA_BINDING . $key, $value, $this->getBindingType($value));
            }
        }
        return $stmt;
    }

    /**
     * @param \PDOStatement $stmt
     * @param $query
     * @return \PDOStatement
     */
    private function bindLimit(\PDOStatement $stmt, $query)
    {
        /**
         * @var SelectQuery|InsertQuery|DeleteQuery|UpdateQuery $query
         */
        $limit = $query->getLimitPart();
        if ($limit) {
            $stmt->bindValue(BindingsEnum::LIMIT_BINDING, $limit, \PDO::PARAM_INT);
        }
        return $stmt;
    }

    /**
     * @param \PDOStatement $stmt
     * @param $query
     * @return \PDOStatement
     */
    private function bindSet(\PDOStatement $stmt, $query)
    {
        /**
         * @var SelectQuery|InsertQuery|DeleteQuery|UpdateQuery $query
         */
        $set = $query->getSetPart();
        if ($set) {
            foreach ($set as $key => $value) {
                $stmt->bindParam(BindingsEnum::CRITERIA_BINDING . $key, $value);
            }
        }
        return $stmt;
    }

    /**
     * @param $value
     * @return int
     */
    private function getBindingType($value)
    {
        if (is_string($value)) {
            return \PDO::PARAM_STR;
        }
        if (is_bool($value)) {
            return \PDO::PARAM_BOOL;
        }
        if (is_null($value)) {
            return \PDO::PARAM_NULL;
        }
        if (is_integer($value) || is_float($value)) {
            return \PDO::PARAM_INT;
        }
        return \PDO::PARAM_STR;
    }
}
