<?php

namespace apocalypse\player;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class PlayerManager {
    use SingletonTrait;

    private array $players = [];
    private string $filePath;

    public function init(PluginBase $plugin): PlayerManager {
        $this->filePath = $plugin->getDataFolder() ."players/";
        @mkdir($this->filePath);

        $plugin->getServer()->getPluginManager()->registerEvents(new PlayerListener($this), $plugin);

        return $this;
    }

    public function getPlayer(Player $player): PlayerData {
        return $this->players[$player->getXuid()];
    }

    public function join(Player $player){
        $p = $this->players[$player->getXuid()] = new PlayerData($player, $this);
        $p->load();
    }

    public function quit(Player $player){
        $p = $this->players[$player->getXuid()];
        $p?->save();
        unset($this->players[$player->getXuid()]);
    }

    public function getSaveFilePath(PlayerData $playerData): string {
        return "{$this->filePath}{$playerData->getPlayer()->getXuid()}.json";
    }
}