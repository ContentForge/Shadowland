<?php

namespace apocalypse\world\biome;

use pocketmine\data\bedrock\BiomeIds;

class DuskBiome extends ApocalypseBiome {

    public function __construct() {
        parent::__construct(BiomeIds::SOULSAND_VALLEY);
    }

    public function getMinRadiationLevel(): int {
        return 15;
    }

    public function getMaxRadiationLevel(): int {
        return 50;
    }
}