<?php
namespace bulutbulutsun\CloudEssentials\listener;

use bulutbulutsun\CloudEssentials\database\mysql\MysqlAPI;
use bulutbulutsun\CloudEssentials\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Listeners implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        if (MysqlAPI::checkPlayer($player->getName())) {
            Loader::getInstance()->getLogger()->warning("This player is already registered");
        } else {
            Loader::getInstance()->getLogger()->critical("This player is not registered, player registering to database");
            $ip_adress = $player->getNetworkSession()->getIp();
            $login = date("Y-m-d H:i:s", time());
            $queries = [
                'INSERT INTO ' . Loader::$players_table . ' (username, password, email, money, ip_adress, lastlogin, firstlogin) VALUES ("' . $player->getName() . '", "null", "null", "0", "' . $ip_adress . '", "' . $login . '", "' . $login . '")'
            ];
            MysqlAPI::execute($queries);
        }
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $username = $player->getName();
        $last_login = date("Y-m-d H:i:s", time());
        $queries = [
            "UPDATE " . Loader::$players_table . " SET lastlogin = '" . $last_login . "' WHERE username = '" . $username . "'"
        ];
        MysqlAPI::execute($queries);
    }
}