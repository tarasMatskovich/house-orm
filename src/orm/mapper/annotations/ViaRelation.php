<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 21.12.2019
 * Time: 14:17
 */

namespace houseorm\mapper\annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class ViaRelation
 * @package houseorm\mapper\annotations
 * @Annotation
 */
final class ViaRelation
{

    /**
     * @var string
     */
    public $entity;

    /**
     * @var string
     */
    public $via;

    /**
     * @var string
     */
    public $firstLocalKey;

    /**
     * @var string
     */
    public $firstForeignKey;

    /**
     * @var string
     */
    public $secondLocalKey;

    /**
     * @var string
     */
    public $secondForeignKey;

}
