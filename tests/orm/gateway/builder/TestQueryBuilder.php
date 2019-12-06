<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:22
 */

namespace tests\orm\gateway\builder;

use houseorm\gateway\builder\QueryBuilder;

/**
 * Class TestQueryBuilder
 * @package tests\orm\gateway\builder
 */
class TestQueryBuilder extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSelectQuery()
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
        $actualQueryStatement = $query->getStatement();
        $expectedQueryStatement = 'SELECT u.name AS `userName`,u.email AS `userEmail` FROM users AS u WHERE u.id = 10 AND u.email LIKE %taras@gmail.com% ORDER BY u.id DESC LIMIT 3 OFFSET 2';
        $this->assertEquals($expectedQueryStatement, $actualQueryStatement);
    }

    /**
     * @return void
     */
    public function testDeleteQuery()
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
        $actualQueryStatement = $query->getStatement();
        $expectedQueryStatement = 'DELETE FROM users AS u WHERE u.id = 10 AND u.email LIKE %taras@gmail.com% ORDER BY u.id ASC LIMIT 3 OFFSET 2';
        $this->assertEquals($expectedQueryStatement, $actualQueryStatement);
    }

    /**
     * @return void
     */
    public function testInsertQuery()
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
        $actualQueryStatement = $query->getStatement();
        $expectedQueryStatement = 'INSERT INTO users (name,email) VALUES (\'Taras\',\'taras@gmail.com\')';
        $this->assertEquals($expectedQueryStatement, $actualQueryStatement);
    }

    /**
     * @return void
     */
    public function testUpdateQuery()
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
        $actualQueryStatement = $query->getStatement();
        $expectedQueryStatement = 'UPDATE users SET name=\'Bohdan\',email=\'bohdan@gmail.com\' WHERE id = 10';
        $this->assertEquals($expectedQueryStatement, $actualQueryStatement);
    }

}
