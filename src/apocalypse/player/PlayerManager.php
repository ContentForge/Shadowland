<?php

namespace apocalypse\player;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class PlayerManager {
    use SingletonTrait;

    private array $players = [];

    public function init(PluginBase $plugin): PlayerManager {
        $plugin->getServer()->getPluginManager()->registerEvents(new PlayerListener($this), $plugin);

        return $this;
    }

    public function getPlayer(Player $player): PlayerData {
        return $this->players[$player->getXuid()];
    }

    public function join(Player $player){
        //TODO: Загрузка данных
        $this->players[$player->getXuid()] = new PlayerData($player, $this);
    }

    public function quit(Player $player){
        //TODO: Сохранение данных
        unset($this->players[$player->getXuid()]);
    }
}