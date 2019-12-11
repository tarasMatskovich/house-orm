<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 15:57
 */

namespace houseorm\mapper\annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Gateway
 * @package houseorm\mapper\annotations
 * @Annotation
 */
final class Gateway
{

    /**
     * @var string
     */
    public $type;

}
