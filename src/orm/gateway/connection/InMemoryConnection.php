<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 12:09
 */

namespace houseorm\gateway\connection;

use houseorm\gateway\datatable\query\delete\DeleteQueryInterface;
use houseorm\gateway\datatable\query\insert\InsertQueryInterface;
use houseorm\gateway\datatable\query\select\SelectQueryInterface;
use houseorm\gateway\datatable\query\update\UpdateQueryInterface;
use houseorm\gateway\datatable\request\QueryRequest;
use houseorm\gateway\datatable\request\QueryRequestInterface;

/**
 * Class InMemoryConnection
 * @package houseorm\gateway\connection
 */
class InMemoryConnection implements ConnectionInterface
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var int|null
     */
    private $lastInsertId;

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     * TODO Add Executor strategy ??
     */
    public function execute(QueryRequestInterface $queryRequest)
    {
        $query = $queryRequest->getQuery();
        if ($query instanceof SelectQueryInterface) {
            return $this->executeSelectQuery($queryRequest);
        }
        if ($query instanceof InsertQueryInterface) {
            return $this->executeInsertQuery($queryRequest);
        }
        if ($query instanceof DeleteQueryInterface) {
            return $this->executeDeleteQuery($queryRequest);
        }
        if ($query instanceof UpdateQueryInterface) {
            return $this->executeUpdateQuery($queryRequest);
        }
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    private function executeSelectQuery(QueryRequestInterface $queryRequest)
    {
        /**
         * @var $query SelectQueryInterface
         */
        $query = $queryRequest->getQuery();
        $fromPart = $query->getFromPart();
        $target = $fromPart[0] ?? null;
        $rows = [];
        if ($target) {
            $where = $query->getWherePart();
            if (array_key_exists($queryRequest->getPrimaryKey(), $where)) {
                $pk = $where[$queryRequest->getPrimaryKey()];
                if ($this->isTargetNotEmpty($target)) {
                    foreach ($this->data[$target]['data'] as $row) {
                        if (isset($row[$queryRequest->getPrimaryKey()]) && $row[$queryRequest->getPrimaryKey()] == $pk) {
                            $rows[] = $row;
                        }
                    }
                }
            }
        }
        return [
            'result' => (!empty($rows)) ? $rows : null,
            'rows' => count($rows)
        ];
    }

    /**
     * @param $target
     * @return bool
     */
    private function isTargetNotEmpty($target): bool
    {
        return isset($this->data[$target]) && isset($this->data[$target]['data']);
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    private function executeInsertQuery(QueryRequestInterface $queryRequest)
    {
        /**
         * @var $query InsertQueryInterface
         */
        $query = $queryRequest->getQuery();
        $intoPart = $query->getIntoPart();
        $target = $intoPart[0] ?? null;
        if ($target) {
            if (isset($this->data[$target])) {
                $this->data[$target]['count']++;
            } else {
                $this->data[$target]['count'] = 1;
            }
            $this->setLastInsertId($this->data[$target]['count']);
            $fields = $query->getFieldsPart();
            $this->data[$target]['data'][] = $fields;
            $count = count($this->data[$target]['data']);
            if (array_key_exists($queryRequest->getPrimaryKey(), $fields) && isset($this->data[$target]['data'][$count-1])) {
                $this->data[$target]['data'][$count-1][$queryRequest->getPrimaryKey()] = $this->data[$target]['count'];
            }
            return [
                'result' => true,
                'rows' => 1
            ];
        }
        return [
            'result' => true,
            'rows' => 0
        ];
    }

    /**
     * @param QueryRequest $queryRequest
     * @return array
     */
    private function executeDeleteQuery(QueryRequestInterface $queryRequest)
    {
        /**
         * @var $query DeleteQueryInterface
         */
        $query = $queryRequest->getQuery();
        $fromPart = $query->getFromPart();
        $target = $fromPart[0] ?? null;
        if ($target) {
            $where = $query->getWherePart();
            if (array_key_exists($queryRequest->getPrimaryKey(), $where)) {
                $pk = $where[$queryRequest->getPrimaryKey()];
                if ($this->isTargetNotEmpty($target)) {
                    $key = null;
                    foreach ($this->data[$target]['data'] as $i => $row) {
                        if (isset($row[$queryRequest->getPrimaryKey()]) && $row[$queryRequest->getPrimaryKey()] == $pk) {
                            $key = $i;
                            break;
                        }
                    }
                    if (null !== $key) {
                        unset($this->data[$target]['data'][$key]);
                        return [
                            'result' => true,
                            'rows' => 1
                        ];
                    }
                }
            }
        }
        return [
            'result' => false,
            'rows' => 0
        ];
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    private function executeUpdateQuery(QueryRequestInterface $queryRequest)
    {
        /**
         * @var $query UpdateQueryInterface
         */
        $query = $queryRequest->getQuery();
        $updatePart = $query->getUpdatePart();
        $target = $updatePart[0] ?? null;
        if ($target) {
            $fields = $query->getSetPart();
            $pk = $queryRequest->getPrimaryKey();
            $key = null;
            if (isset($this->data[$target]) && isset($this->data[$target]['data'])) {
                foreach ($this->data[$target]['data'] as $i => $row) {
                    if (isset($row[$pk])) {
                        $key = $i;
                        break;
                    }
                }
                if (null !== $key) {
                    $this->data[$target]['data'][$key] = $fields;
                }
            }
            return [
                'result' => true,
                'rows' => 1
            ];
        }
        return [
            'result' => false,
            'rows' => 0
        ];
    }

    /**
     * @return int|null
     */
    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

    /**
     * @param $id
     */
    private function setLastInsertId($id)
    {
        $this->lastInsertId = $id;
    }
}
