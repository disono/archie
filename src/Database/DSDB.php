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

    public function select($columns)
    {
        $this->selects = $columns;
    }

    public function where()
    {
        $args = func_get_args();

        if (count($args) === 2) {
            $this->where[] = ['left' => $args[0], 'right' => $args[1], 'middle' => '='];
        } else if (count($args) === 3) {
            $this->where[] = ['left' => $args[0], 'right' => $args[1], 'middle' => $args[2]];
        }

        return $this;
    }

    public function orWhere()
    {

    }

    public function query()
    {
        return $this->connection->query("SELECT * FROM " . $this->name . " WHERE id = %s", $type);
    }
}