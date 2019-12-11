<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 16:03
 */

namespace houseorm\mapper;

use Throwable;

/**
 * Class DomainMapperException
 * @package houseorm\mapper
 */
class DomainMapperException extends \Exception
{

    /**
     * DomainMapperException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $code, Throwable $previous = null)
    {
        $message = 'Cannot create mapping to entity: ' . $message;
        parent::__construct($message, $code, $previous);
    }

}
