<?php
/**
 * Created by PhpStorm.
 * User: kris
 * Date: 21.10.17
 * Time: 10:03
 */

namespace AppBundle\Yaml;

use PHPUnit\Framework\TestCase;

class CommentBlockTest extends TestCase
{
    public function testCanBeReadWithoutHashes() {
        $input = <<< EOF
# This is comment
#  with two lines
EOF;
        $expect = <<< EOF
This is comment
 with two lines
EOF;

        $comment = new CommentBlock($input);
        $this->assertEquals($expect, $comment->getText());
    }

    public function testCanBeReadWithHashes() {
        $input = <<< EOF
# This is comment
#  with two lines
EOF;
        $expect = $input;
        $comment = new CommentBlock($input);
        $this->assertEquals($expect, $comment->getComment());
    }
}
