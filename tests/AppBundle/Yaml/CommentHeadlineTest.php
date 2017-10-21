<?php
/**
 * Created by PhpStorm.
 * User: kris
 * Date: 21.10.17
 * Time: 09:47
 */

namespace AppBundle\Yaml;

use PHPUnit\Framework\TestCase;

class CommentHeadlineTest extends TestCase
{
    public function testCanBeReadWithoutHashes() {
        $headline = new CommentHeadline('## Hello');
        $this->assertEquals('Hello', $headline->getText());
    }
    public function testCanBeReadWithHashes() {
        $input = '## Hello';
        $headline = new CommentHeadline($input);
        $this->assertEquals($input, $headline->getComment());
    }
}
