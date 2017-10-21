<?php
/**
 * Created by PhpStorm.
 * User: kris
 * Date: 21.10.17
 * Time: 10:06
 */

namespace AppBundle\Yaml;


abstract class Comment
{
    /**
     * @var string
     */
    protected $text;

    public function getText() {
        return $this->text;
    }

    abstract public function getComment();

    public function __toString() {
        $this->getComment();
    }
}