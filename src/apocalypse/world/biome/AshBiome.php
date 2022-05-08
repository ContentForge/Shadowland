<?php

namespace apocalypse\world\biome;

use pocketmine\data\bedrock\BiomeIds;
use pocketmine\player\Player;

class AshBiome extends ApocalypseBiome {

    public function __construct() {
        parent::__construct(BiomeIds::BASALT_DELTAS);
    }

    public function getMinRadiationLevel(): int {
        return 100;
    }

    public function getMaxRadiationLevel(): int {
        return 2000;
    }

    public function getSunMultiplier(Player $player): float {
        return 0;
    }

    public function handleSunEffect(Player $player, float $dose, bool $isUnderSky): void {

    }
}