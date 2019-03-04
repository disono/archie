<?php

namespace Archie\Database;

class DSDB
{
    private $connection = null;
    private $table = null;
    private $selects = [];
    private $where = [];

    public function __construct()
    {
        $this->connection = new \MeekroDB($host, $user, $pass, $dbName, $port, $encoding);
    }

    public function table($name)
    {
        $this->table = $name;
        return $this;
    }

    public function where($columnLeft, $middle, $columnRight)
    {
        $this->where[] = [$columnLeft, $middle, $columnRight];
        return $this;
    }

    public function query()
    {
        $this->connection->query("SELECT * FROM " . $this->name . " WHERE id = %s AND age > %i", $type, 15);
    }
}