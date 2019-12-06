<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 06.12.2019
 * Time: 16:36
 */

namespace houseorm\gateway\builder;

use Throwable;

/**
 * Class QueryBuilderException
 * @package houseorm\gateway\builder
 */
class QueryBuilderException extends \Exception
{

    /**
     * QueryBuilderException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = 'QueryBuilderException: Query syntax error in: ' . $message;
        parent::__construct($message, $code, $previous);
    }

}
