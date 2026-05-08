<?php

namespace app;

class Command {
    /**
     * Array of arguments passed by command line.
     */
    private $_args = [];

    public function setArgs($args)
    {
        $this->_args = $args;
    }

    /**
     * Runs the command and calls an action.
     */
    public function run()
    {


    }

    /**
     * Simple text output to stdout.
     */
    public function write($text)
    {
        echo $text;
    }

    /**
     * Prints text and insert new line.
     */
    public function writeln($text)
    {
        $this->write("{$text}\n");
    }

    /**
     * Prints text and update current line.
     */
    public function writern($text)
    {
        $this->write("{$text}\r");
    }
}