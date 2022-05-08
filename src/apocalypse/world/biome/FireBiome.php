<?php

namespace apocalypse\world\biome;

use apocalypse\immersive\radiation\RadiationManager;
use pocketmine\data\bedrock\BiomeIds;
use pocketmine\player\Player;

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

    public function getSunMultiplier(Player $player): float {
        return parent::getSunMultiplier($player) * 2.5;
    }

    public function handleSunEffect(Player $player, float $dose, bool $isUnderSky): void {
        if (!$isUnderSky || $dose < 0.3) return;

        if ($dose < 0.5) {
            $fire = 2;
            $rad = 25;
        } elseif ($dose < 0.8) {
            $fire = 5;
            $rad = 50;
        } else {
            $fire = 8;
            $rad = 100;
        }

        $fire *= 20;

        if ($player->getFireTicks() < $fire) $player->setOnFire($fire);
        RadiationManager::getInstance()->addDose($player, $rad);
    }
}