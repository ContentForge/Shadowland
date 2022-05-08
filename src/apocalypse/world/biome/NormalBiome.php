<?php

namespace apocalypse\world\biome;

use apocalypse\immersive\radiation\RadiationManager;
use pocketmine\data\bedrock\BiomeIds;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;

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

    public function handleSunEffect(Player $player, float $dose, bool $isUnderSky): void {
        if (!$isUnderSky) return;

        if ($dose < 0.2) {
            $rad = 5;
        } elseif ($dose < 0.4) {
            $rad = 10;
            $effect = new EffectInstance(VanillaEffects::WITHER(), 3 * 20, 1);
        } elseif ($dose < 0.6) {
            $rad = 20;
            $effect = new EffectInstance(VanillaEffects::WITHER(), 3 * 20, 2);
        } elseif ($dose < 0.8) {
            $rad = 30;
            $effect = new EffectInstance(VanillaEffects::WITHER(), 4 * 20, 3);
        } else {
            $rad = 40;
            $effect = new EffectInstance(VanillaEffects::WITHER(), 5 * 20, 4);
        }

        RadiationManager::getInstance()->addDose($player, $rad);
        if (isset($effect)) $player->getEffects()->add($effect);
    }
}