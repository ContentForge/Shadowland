<?php

namespace apocalypse\world\biome;

use pocketmine\data\bedrock\BiomeIds;

class AshBiome extends ApocalypseBiome {

    public function __construct() {
        parent::__construct(BiomeIds::BASALT_DELTAS);
    }

    public function getMinRadiationLevel(): int {
        return 100;
    }

    public function getMaxRadiationLevel(): int {
        return 1000;
    }
}