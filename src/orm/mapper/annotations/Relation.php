<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 17.12.2019
 * Time: 14:39
 */

namespace houseorm\mapper\annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Relation
 * @package houseorm\mapper\annotations
 * @Annotation
 */
final class Relation
{

    /**
     * @var string
     */
    public $entity;

    /**
     * @var string
     */
    public $key;

}
