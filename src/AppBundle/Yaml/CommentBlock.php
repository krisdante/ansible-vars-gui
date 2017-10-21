<?php
/**
 * Created by PhpStorm.
 * User: kris
 * Date: 21.10.17
 * Time: 10:03
 */

namespace AppBundle\Yaml;


class CommentBlock extends Comment
{
    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $this->commentToText($text);
    }

    private function commentToText($text) {
        return preg_replace('/^#\s/m', '', $text);
    }

    private function textToComment($text) {
        return preg_replace('/^/m', '# ', $text);
    }

    public function getComment()
    {
        return $this->textToComment($this->text);
    }
}