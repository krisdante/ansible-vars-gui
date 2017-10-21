<?php

namespace Tests\AppBundle\Yaml;


use AppBundle\Yaml\CommentBlock;
use AppBundle\Yaml\CommentHeadline;
use AppBundle\Yaml\YamlReader;
use PHPUnit\Framework\Assert;

class YamlReaderTest extends \PHPUnit\Framework\TestCase
{
    public function testCanBeCreatedFromArray() {
        $yaml = [
            'foo: bar',
            'baz: foo'
        ];
        $reader = new YamlReader($yaml);

        Assert::assertAttributeEquals($yaml, 'yamlData', $reader);
    }

    public function testCanBeCreatedFromString() {
        $yamlArray = [
            'foo: bar',
            'baz: foo'
        ];
        $yamlString = <<<EOF
foo: bar
baz: foo
EOF;
        $reader = new YamlReader($yamlString);
        Assert::assertAttributeEquals($yamlArray, 'yamlData', $reader);
    }

    public function testReturnsVariable() {
        $yamlArray = [ 'foo: bar '];
        $reader = new YamlReader($yamlArray);
        $var = $reader->current();
        $this->assertEquals([ 'foo' => 'bar' ], $var);
    }

    public function testReturnsHeadline() {
        $yaml ='## Headline';
        $reader = new YamlReader($yaml);
        $var = $reader->current();
        $ch = new CommentHeadline($yaml);
        $this->assertEquals($ch, $var);
    }

    public function testReturnsCommentBlock() {
        $yamls = [
            [ '# Comment block', "# Two lines" ],
            [ '# Comment block', "# Two lines", 'foo: bar' ],
        ];
        $out = <<< EOF
Comment block
Two lines
EOF;
        foreach ($yamls as $yaml) {
            $reader = new YamlReader($yaml);
            $var = $reader->current();
            $cb = new CommentBlock($out);
            $this->assertEquals($cb, $var);
        }
    }

    public function testReturnsArrayVariable() {
        $yaml = <<< EOF
foo:
  - bar
  - baz
name: john
# comment
EOF;

        $reader = new YamlReader($yaml);
        $var = $reader->current();
        $this->assertEquals([ 'foo' => [ 'bar', 'baz' ] ], $var);
        $reader->next();
        $this->assertTrue($reader->valid());
        $var = $reader->current();
        $this->assertEquals([ 'name' => 'john' ], $var);
    }

    public function testReturnsDictVariable() {
        $yaml = <<< EOF
foo:
  one: bar
  two: baz
# comment
EOF;
        $reader = new YamlReader($yaml);
        $var = $reader->current();
        $this->assertEquals([ 'foo' => [ 'one' => 'bar', 'two' => 'baz' ] ], $var);
    }



}
