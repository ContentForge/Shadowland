<?php

namespace apocalypse\world\biome;

use pocketmine\data\bedrock\BiomeIds;

class NormalBiome extends ApocalypseBiome {

    public function __construct() {
        parent::__construct(BiomeIds::DESERT);
    }

    public function getMinRadiationLevel(): int {
        return 0;
    }

    public function getMaxRadiationLevel(): int {
        return 40;
    }
}