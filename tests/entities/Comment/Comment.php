<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 17.12.2019
 * Time: 14:46
 */

namespace tests\entities\Comment;

use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;
use houseorm\mapper\annotations\Relation;

/**
 * Class Comment
 * @package tests\entities\Comment
 * @Gateway(type="datatable.comments")
 */
class Comment implements CommentInterface
{

    /**
     * @var int
     * @Field(map="id")
     */
    private $id;

    /**
     * @var string
     * @Field(map="content")
     */
    private $content;

    /**
     * @var int
     * @Field(map="user_id")
     * @Relation(entity="User", key="id")
     */
    private $userId;

    /**
     * Comment constructor.
     * @param null $content
     * @param null $userId
     */
    public function __construct($content = null, $userId = null)
    {
        $this->content = $content;
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param $userId
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}
