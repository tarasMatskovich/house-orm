<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 11:06
 */

namespace tests\repositories\UserRepository;

use houseorm\mapper\DomainMapperInterface;
use tests\entities\User\UserInterface;

/**
 * Interface UserRepositoryInterface
 * @package tests\repositories\UserRepository
 */
interface UserRepositoryInterface extends DomainMapperInterface
{

    /**
     * @param $id
     * @return UserInterface|null
     */
    public function find($id);

}
