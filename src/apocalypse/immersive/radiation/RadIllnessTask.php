<?php

namespace apocalypse\immersive\radiation;

use apocalypse\player\PlayerManager;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class RadIllnessTask extends Task {

    public function onRun(): void {
        $playerManager = PlayerManager::getInstance();

        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player->isCreative() || $player->isSpectator()) continue;

            $playerData = $playerManager->getPlayer($player);
            $dose = $playerData->getRadLevel();

            if ($dose < 100_000) continue;
            if ($dose < 200_000) $stage = 1;
            elseif ($dose < 400_000) $stage = 2;
            elseif ($dose < 700_000) $stage = 3;
            else $stage = 4;

            if ($stage === 4 || mt_rand(0, 120 - ($stage * 20)) !== 0) $this->giveSymptoms($player, $stage);
        }
    }

    private function giveSymptoms(Player $player, int $stage): void {
        $effects = [];

        switch ($stage) {
            case 1:
                $effects[] = new EffectInstance(VanillaEffects::WEAKNESS(), 30 * 20);
                $effects[] = new EffectInstance(VanillaEffects::NAUSEA(), 5 * 20);
                $effects[] = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 30 * 20);
                break;

            case 2:
                $effects[] = new EffectInstance(VanillaEffects::WEAKNESS(), 60 * 20, 1);
                $effects[] = new EffectInstance(VanillaEffects::NAUSEA(), 6 * 20);
                $effects[] = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 60 * 20, 1);
                $effects[] = new EffectInstance(VanillaEffects::POISON(), 10 * 20);
                break;

            case 3:
                $effects[] = new EffectInstance(VanillaEffects::WEAKNESS(), 60 * 20, 2);
                $effects[] = new EffectInstance(VanillaEffects::NAUSEA(), 10 * 20);
                $effects[] = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 60 * 20, 2);
                $effects[] = new EffectInstance(VanillaEffects::WITHER(), 10 * 20, 1);
                break;

            case 4:
                $effects[] = new EffectInstance(VanillaEffects::WEAKNESS(), 60 * 20, 3);
                $effects[] = new EffectInstance(VanillaEffects::NAUSEA(), 20 * 20);
                $effects[] = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 60 * 20, 3);
                $effects[] = new EffectInstance(VanillaEffects::WITHER(), 15 * 20, 2);
                break;
        }

        foreach ($effects as $effect) {
            $player->getEffects()->add($effect);
        }
    }
}