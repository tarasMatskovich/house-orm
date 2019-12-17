<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 17.12.2019
 * Time: 14:44
 */

namespace tests\entities\Comment;

/**
 * Interface CommentInterface
 * @package tests\entities\Comment
 */
interface CommentInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param $content
     * @return void
     */
    public function setContent($content);

    /**
     * @return int
     */
    public function getUserId();

    /**
     * @param $userId
     * @return void
     */
    public function setUserId($userId);

}
