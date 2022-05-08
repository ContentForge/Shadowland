<?php

namespace apocalypse\world\biome;

use apocalypse\immersive\radiation\RadiationManager;
use pocketmine\data\bedrock\BiomeIds;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;

class DuskBiome extends ApocalypseBiome {

    public function __construct() {
        parent::__construct(BiomeIds::SOULSAND_VALLEY);
    }

    public function getMinRadiationLevel(): int {
        return 15;
    }

    public function getMaxRadiationLevel(): int {
        return 70;
    }

    public function handleSunEffect(Player $player, float $dose, bool $isUnderSky): void {
        if (!$isUnderSky) return;

        if ($dose < 0.2) {
            $rad = 10;
        } elseif ($dose < 0.4) {
            $effect = new EffectInstance(VanillaEffects::WITHER(), 4 * 20, 1);
            $rad = 20;
        } elseif ($dose < 0.6) {
            $rad = 30;
            $effect = new EffectInstance(VanillaEffects::WITHER(), 4 * 20, 2);
        } elseif ($dose < 0.8) {
            $rad = 40;
            $effect = new EffectInstance(VanillaEffects::WITHER(), 5 * 20, 3);
        } else {
            $rad = 50;
            $effect = new EffectInstance(VanillaEffects::WITHER(), 6 * 20, 4);
        }

        RadiationManager::getInstance()->addDose($player, $rad);
        if (isset($effect)) $player->getEffects()->add($effect);
    }
}