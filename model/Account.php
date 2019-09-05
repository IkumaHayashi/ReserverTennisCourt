<?php

Class Account
{
    public $id;
    public $password;

    public function __construct(string $_id, string $_password)
    {
        $this->id = $_id;
        $this->password = $_password;
    }
}