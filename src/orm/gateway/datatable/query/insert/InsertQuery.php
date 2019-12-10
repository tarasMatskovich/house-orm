<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:07
 */

namespace houseorm\gateway\datatable\query\insert;

use houseorm\gateway\datatable\query\traits\BindingsEnum;

/**
 * Class InsertQuery
 * @package houseorm\gateway\datatable\query\insert
 */
class InsertQuery implements InsertQueryInterface
{

    /**
     * @var array
     */
    private $into;

    /**
     * @var array
     */
    private $fields;

    /**
     * @return InsertQueryInterface
     */
    public function insert()
    {
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $into
     * @return InsertQueryInterface
     */
    public function into(array $into)
    {
        $this->into = $into;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $fields
     * @return InsertQueryInterface
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;
        $query = clone $this;
        return $query;
    }

    /**
     * @return string
     */
    private function getInto()
    {
        return $this->into[0] ?? '';
    }

    /**
     * @return string
     */
    private function getFields()
    {
        $fields = $this->fields;
        $fieldsStatement = '';
        foreach ($fields as $field => $value) {
            $fieldsStatement .= $field . ',';
        }
        $fieldsStatement = $fieldsStatement !== '' ? substr($fieldsStatement, 0, -1) : '';
        return $fieldsStatement;
    }

    /**
     * @return string
     */
    private function getValues()
    {
        $values = $this->fields;
        $valuesStatement = '';
        foreach ($values as $field => $value) {
            $valuesStatement .= $value . ',';
        }
        $valuesStatement = $valuesStatement !== '' ? substr($valuesStatement, 0, -1) : '';
        return $valuesStatement;
    }

    /**
     * @return string
     */
    private function getPreparedValues()
    {
        $values = $this->fields;
        $valuesStatement = '';
        foreach ($values as $field => $value) {
            $valuesStatement .= BindingsEnum::CRITERIA_BINDING . $field . ',';
        }
        $valuesStatement = $valuesStatement !== '' ? substr($valuesStatement, 0, -1) : '';
        return $valuesStatement;
    }


    /**
     * @return string
     */
    public function getStatement()
    {
        $into = $this->getInto();
        $fields = $this->getFields();
        $values = $this->getValues();
        return "INSERT INTO {$into} ({$fields}) VALUES ({$values})";
    }

    /**
     * @return string
     */
    public function getPreparedStatement()
    {
        $into = $this->getInto();
        $fields = $this->getFields();
        $values = $this->getPreparedValues();
        return "INSERT INTO {$into} ({$fields}) VALUES ({$values})";
    }
}
