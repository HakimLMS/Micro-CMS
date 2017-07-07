<?php

namespace MicroCMS\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO{
    private $db; 
    
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
    
     protected function getDb() {
        return $this->db;
    }
    
    /**
     * Builds a domain object from a DB row.
     * Must be overridden by child classes.
     */
    protected abstract function buildDomainObject(array $row);
}