<?php

class Queries_repository
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}