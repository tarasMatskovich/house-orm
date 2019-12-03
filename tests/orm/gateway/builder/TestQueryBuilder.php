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

}
