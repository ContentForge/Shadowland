<?php

namespace apocalypse;

use apocalypse\immersive\ImmersiveMechanicsTask;
use apocalypse\world\ApocalypseGenerator;
use apocalypse\world\VoidGenerator;
use pocketmine\plugin\PluginBase;
use pocketmine\world\generator\GeneratorManager;

class Apocalypse extends PluginBase {

    protected function onLoad(): void {
        GeneratorManager::getInstance()->addGenerator(ApocalypseGenerator::class, "apocalypse", fn() => null);
        GeneratorManager::getInstance()->addGenerator(VoidGenerator::class, "void", fn() => null);
    }

    protected function onEnable(): void {
        $this->getScheduler()->scheduleRepeatingTask(new ImmersiveMechanicsTask($this), 10);
    }

    protected function onDisable(): void {

    }
}