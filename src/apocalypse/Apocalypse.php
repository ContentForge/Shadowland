<?php

namespace apocalypse;

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

    }

    protected function onDisable(): void {

    }
}