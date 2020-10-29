<?php

class Category
{
    private $_id;
    private $_name;

    function __construct($id, $name)
    {
        $this->_id = $id;
        $this->_name = $name;
    }

    public function id()
    {
        return $this->_id;
    }

    public function name()
    {
        return $this->_name;
    }

    public function html_name()
    {
        return htmlspecialchars($this->_name);
    }
}