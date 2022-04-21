<?php

namespace apocalypse\world\biome;

use pocketmine\data\bedrock\BiomeIds;

class FireBiome extends ApocalypseBiome {

    public function __construct() {
        parent::__construct(BiomeIds::CRIMSON_FOREST);
    }

    public function getMinRadiationLevel(): int {
        return 20;
    }

    public function getMaxRadiationLevel(): int {
        return 200;
    }
}