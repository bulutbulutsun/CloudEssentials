<?php

namespace bulutbulutsun\CloudEssentials\database\mysql;

use bulutbulutsun\CloudEssentials\Loader;
use bulutbulutsun\CloudEssentials\database\mysql\thread\job\Connect;

class MysqlAPI{
    private static $mysql = null;
    public static $instance;
    public static function connectToMySQL(string $host, string $username, string $password, string $database) {

        $mysql = new Mysql();
        $mysql->bindTo(new Connect($host, $username, $password, $database));
        self::$mysql = $mysql;
    }
    public static function getInstance()
    {
        return self::$instance;
    }
    public static function checkPlayer($username) {
        $result = self::execute(['SELECT username FROM '.Loader::$players_table.' WHERE username="'.$username.'"']);
        return !empty($result[0]);
    }

    public static function execute(array $queries) {
        if( is_null(self::$mysql) )
            throw new \Exception("The SQL connection is not registered");
        else
            self::$mysql->setQuery($queries);
        return self::$mysql->recv();
    }

    public static function createTable(string $tablename, array $columns) {
        $query = "CREATE TABLE IF NOT EXISTS $tablename (";
        $columnList = [];

        foreach ($columns as $columnName => $columnType)
            $columnList[] = "`$columnName` $columnType";

        $query .= implode(', ', $columnList);
        $query .= ")";

        self::execute([$query]);

    }

    public static function createDB(string $dbname) : void {
        self::execute(["CREATE DATABASE IF NOT EXISTS ".$dbname]);
    }
}
