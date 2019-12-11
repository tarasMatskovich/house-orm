<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 15:58
 */

namespace houseorm\mapper\annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Field
 * @package houseorm\mapper\annotations
 * @Annotation
 */
final class Field
{
    /**
     * @var string
     */
    public $map;

    /**
     * @var string
     */
    public $type = 'string';

}
