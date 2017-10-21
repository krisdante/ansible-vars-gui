<?php

namespace AppBundle\Yaml;


use Symfony\Component\Yaml\Yaml;

class YamlReader implements \Iterator
{
    /**
     * @var array
     */
    private $yamlData;

    private $position = 0;

    public function __construct($yamlData)
    {
        if(!is_array($yamlData)) {
            $yamlData=explode("\n", $yamlData);
        }
        $this->yamlData = $yamlData;
    }



    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $line = $this->getCurrentYamlLine();
        if($this->isComment($line)) {
            return $this->parseComment($line);
        }

        return $this->parseVariable($line);
    }



    private function getCurrentYamlLine($offset = 0) {
        if ($offset && !isset($this->yamlData[$this->position + $offset])) {
            return false;
        }
        return $this->yamlData[$this->position + $offset];
    }

    private function isComment($line) {
        return isset($line[0]) && $line[0] == '#';
    }

    private function isHeadline($line) {
        return strpos($line, '##') === 0;
//        return isset($line[0]) && $line[0] == '#' && $line[1] == '#';
    }

    private function isCommentBlock($line) {
        $line = ltrim($line);
        return isset($line[0]) && isset($line[1]) && $line[0] == '#' && $line[1] == ' ';
    }


    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->yamlData[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->position=0;
    }

    /**
     * @param $line
     * @return CommentBlock|CommentHeadline
     */
    private function parseComment($line)
    {
        if ($this->isHeadline($line)) {
            return new CommentHeadline($line);
        } else {
            $comment = $line;
            while ($this->isCommentBlock($this->getCurrentYamlLine(1))) {
                $this->position++;
                $comment .= "\n" . $this->getCurrentYamlLine();
            }
            return new CommentBlock($comment);
        }
    }

    /**
     * @param $line
     * @return mixed
     */
    private function parseVariable($line)
    {
        $variableData = $line;

        $nextLine = $this->getCurrentYamlLine(1);
        while ($nextLine !== false) {

            if ($this->isComment($nextLine))
                break;

            $newVariableData = $variableData . PHP_EOL . $nextLine;
            $variable = Yaml::parse($newVariableData);
            if (count($variable) == 1) {
                $this->position++;
                $variableData = $newVariableData;
            } else
                break;
            $nextLine = $this->getCurrentYamlLine(1);
        }

        $variable = Yaml::parse($variableData);
        return $variable;
    }
}