<?php

namespace apocalypse\world\biome;

use pocketmine\player\Player;

class ApocalypseBiome {

    private int $biomeId;

    public function __construct(int $biomeId) {
        $this->biomeId = $biomeId;
    }

    public function getBiomeId(): int {
        return $this->biomeId;
    }

    public function getMinRadiationLevel(): int {
        return 10;
    }

    public function getMaxRadiationLevel(): int {
        return 20;
    }

    public function getSunMultiplier(Player $player): float {
        return 1;
    }

    public function handleSunEffect(Player $player, float $dose): void {
        //TODO: Эффекты для каждой полученной дозы игроком
    }
}