<?php

/**
 * Class QueriesRepository
 * базовый класс запросов к базе данных
 */

abstract class QueriesRepository
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}