<?php

namespace bulutbulutsun\CloudEssentials;

use bulutbulutsun\CloudEssentials\database\mysql\MysqlAPI;
use bulutbulutsun\CloudEssentials\listener\Listeners;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase
{

    public static $instance;
    public static $players_table= 'players';

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @return mixed
     */
    protected function onEnable(): void
    {
        self::$instance = $this;
        $config = $this->getConfig();
        $mysql = new MysqlAPI();
        $this->getServer()->getPluginManager()->registerEvents(new Listeners(), $this);
        $mysql->connectToMySQL($config->get("hostname"), $config->get("username"), $config->get("password"), $config->get("database"));
        $mysql->createTable("players", [
            'username' => 'VARCHAR(12)',
            'password' => 'VARCHAR(32)',
            "email" => "VARCHAR(32)",
            "money" => "INT(255)",
            "ip_adress" => "VARCHAR(16)",
            "lastlogin" => "DATETIME",
            "firstlogin" => "DATETIME"
        ]);
        $this->getLogger()->notice("Activated");
    }
    protected function onDisable(): void
    {
        self::$instance = null;
        $this->getLogger()->critical("De-activated");
    }
}
