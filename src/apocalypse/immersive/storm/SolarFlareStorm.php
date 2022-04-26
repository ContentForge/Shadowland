<?php

namespace apocalypse\immersive\storm;

use apocalypse\immersive\radiation\RadiationManager;
use pocketmine\Server;

class SolarFlareStorm extends Storm {

    public const RADIATION = 500;

    public function __construct() {
        parent::__construct("Геомагнитный шторм");
    }

    public function getAverageDuration(): int {
        return 120;
    }

    public function tick(int $left, int $passed): void {
        $rad = self::RADIATION;
        if ($passed < 10) {
            $rad *= $passed / 10;
        } elseif ($left < 10) {
            $rad *= $left / 10;
        }

        $radManager = RadiationManager::getInstance();
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            //TODO: Проверка мира

            $pos = $player->getPosition();
            $pRad = (int) ($rad * ($pos->y / 60));

            $radManager->addDose($player, $pRad);
        }
    }
}