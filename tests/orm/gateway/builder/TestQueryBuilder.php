<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:22
 */

namespace tests\orm\gateway\builder;

use houseorm\gateway\builder\QueryBuilder;
use houseorm\gateway\datatable\query\delete\DeleteQueryInterface;
use houseorm\gateway\datatable\query\insert\InsertQueryInterface;
use houseorm\gateway\datatable\query\select\SelectQueryInterface;
use houseorm\gateway\datatable\query\traits\BindingsEnum;
use houseorm\gateway\datatable\query\update\UpdateQueryInterface;

/**
 * Class TestQueryBuilder
 * @package tests\orm\gateway\builder
 */
class TestQueryBuilder extends \PHPUnit_Framework_TestCase
{

    /**
     * @return SelectQueryInterface
     */
    private function makeSelectQuery()
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->getSelectQuery();
        $query->select([
            'u.name AS `userName`',
            'u.email AS `userEmail`'
        ]);
        $query->from(['users AS u']);
        $query->where([
            'u.id' => 10,
            ['u.email', 'LIKE', '%taras@gmail.com%']
        ]);
        $query->order([
            'u.id' => 'DESC'
        ]);
        $query->limit(3);
        $query->offset(2);
        return $query;
    }

    /**
     * @return void
     */
    public function testSelectQuery()
    {
        $query = $this->makeSelectQuery();
        $actualQueryStatement = $query->getStatement();
        $expectedQueryStatement = 'SELECT u.name AS `userName`,u.email AS `userEmail` FROM users AS u WHERE u.id = 10 AND u.email LIKE %taras@gmail.com% ORDER BY u.id DESC LIMIT 3 OFFSET 2';
        $this->assertEquals($expectedQueryStatement, $actualQueryStatement);
    }

    /**
     * @return void
     */
    public function testPreparedSelectQuery()
    {
        $query = $this->makeSelectQuery();
        $criteriaBinding = BindingsEnum::CRITERIA_BINDING;
        $orderBinding = BindingsEnum::ORDER_BINDING;
        $limitBinding = BindingsEnum::LIMIT_BINDING;
        $offsetBinding = BindingsEnum::OFFSET_BINDING;
        $expectedPreparedQueryStatement = "SELECT u.name AS `userName`,u.email AS `userEmail` FROM users AS u WHERE u.id = {$criteriaBinding}u.id AND u.email LIKE {$criteriaBinding}u.email ORDER BY {$orderBinding} DESC LIMIT {$limitBinding} OFFSET {$offsetBinding}";
        $actualPreparedQueryStatement = $query->getPreparedStatement();
        $this->assertEquals($expectedPreparedQueryStatement, $actualPreparedQueryStatement);
    }

    /**
     * @return DeleteQueryInterface
     */
    private function makeDeleteQuery()
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->getDeleteQuery();
        $query->delete();
        $query->from([
            'users AS u'
        ]);
        $query->where([
            'u.id' => 10,
            ['u.email', 'LIKE', '%taras@gmail.com%']
        ]);
        $query->order([
            'u.id' => 'ASC'
        ]);
        $query->limit(3);
        $query->offset(2);
        return $query;
    }

    /**
     * @return void
     */
    public function testDeleteQuery()
    {
        $query = $this->makeDeleteQuery();
        $actualQueryStatement = $query->getStatement();
        $expectedQueryStatement = 'DELETE FROM users AS u WHERE u.id = 10 AND u.email LIKE %taras@gmail.com% ORDER BY u.id ASC LIMIT 3 OFFSET 2';
        $this->assertEquals($expectedQueryStatement, $actualQueryStatement);
    }

    /**
     * @return void
     */
    public function testPreparedDeleteQuery()
    {
        $query = $this->makeDeleteQuery();
        $criteriaBinding = BindingsEnum::CRITERIA_BINDING;
        $orderBinding = BindingsEnum::ORDER_BINDING;
        $limitBinding = BindingsEnum::LIMIT_BINDING;
        $offsetBinding = BindingsEnum::OFFSET_BINDING;
        $actualPreparedQueryStatement = $query->getPreparedStatement();
        $expectedPreparedQueryStatement = "DELETE FROM users AS u WHERE u.id = {$criteriaBinding}u.id AND u.email LIKE {$criteriaBinding}u.email ORDER BY {$orderBinding} ASC LIMIT {$limitBinding} OFFSET {$offsetBinding}";
        $this->assertEquals($expectedPreparedQueryStatement, $actualPreparedQueryStatement);
    }

    /**
     * @return InsertQueryInterface
     */
    private function makeInsertQuery()
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->getInsertQuery();
        $query->insert();
        $query->into([
            'users'
        ]);
        $query->fields([
            'name' => '\'Taras\'',
            'email' => '\'taras@gmail.com\''
        ]);
        return $query;
    }

    /**
     * @return void
     */
    public function testInsertQuery()
    {
        $query = $this->makeInsertQuery();
        $actualQueryStatement = $query->getStatement();
        $expectedQueryStatement = 'INSERT INTO users (name,email) VALUES (\'Taras\',\'taras@gmail.com\')';
        $this->assertEquals($expectedQueryStatement, $actualQueryStatement);
    }

    /**
     * @return void
     */
    public function testPreparedInsertQuery()
    {
        $query = $this->makeInsertQuery();
        $criteriaBinding = BindingsEnum::CRITERIA_BINDING;
        $actualPreparedQueryStatement = $query->getPreparedStatement();
        $expectedPreparedQueryStatement = "INSERT INTO users (name,email) VALUES ({$criteriaBinding}name,{$criteriaBinding}email)";
        $this->assertEquals($expectedPreparedQueryStatement, $actualPreparedQueryStatement);
    }

    /**
     * @return UpdateQueryInterface
     */
    private function makeUpdateQuery()
    {
        $queryBuilder = new QueryBuilder();
        $query = $queryBuilder->getUpdateQuery();
        $query->update([
            'users'
        ]);
        $query->set([
            'name' => '\'Bohdan\'',
            'email' => '\'bohdan@gmail.com\''
        ]);
        $query->where([
            'id' => 10
        ]);
        return $query;
    }

    /**
     * @return void
     */
    public function testUpdateQuery()
    {
        $query = $this->makeUpdateQuery();
        $actualQueryStatement = $query->getStatement();
        $expectedQueryStatement = 'UPDATE users SET name=\'Bohdan\',email=\'bohdan@gmail.com\' WHERE id = 10';
        $this->assertEquals($expectedQueryStatement, $actualQueryStatement);
    }

    /**
     * @return void
     */
    public function testPreparedUpdateQuery()
    {
        $query = $this->makeUpdateQuery();
        $criteriaBinding = BindingsEnum::CRITERIA_BINDING;
        $actualPreparedQueryStatement = $query->getPreparedStatement();
        $expectedPreparedQueryStatement = "UPDATE users SET name={$criteriaBinding}name,email={$criteriaBinding}email WHERE id = {$criteriaBinding}id";
        $this->assertEquals($expectedPreparedQueryStatement, $actualPreparedQueryStatement);
    }

}
