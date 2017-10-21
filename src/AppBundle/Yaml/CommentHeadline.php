<?php
/**
 * Created by PhpStorm.
 * User: kris
 * Date: 21.10.17
 * Time: 09:42
 */

namespace AppBundle\Yaml;


class CommentHeadline extends Comment
{
    /**
     * CommentHeadline constructor.
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = ltrim($text, '# ');
    }

    public function getComment() {
        return '## '.$this->text;
    }
}