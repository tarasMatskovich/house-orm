<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 17.12.2019
 * Time: 14:48
 */

namespace tests\repositories\CommentRepository;

use houseorm\mapper\DomainMapperInterface;
use tests\entities\Comment\CommentInterface;

/**
 * Interface CommentRepositoryInterface
 * @package tests\repositories\CommentRepository
 */
interface CommentRepositoryInterface extends DomainMapperInterface
{

    /**
     * @param $id
     * @return CommentInterface|null
     */
    public function find($id);

}
