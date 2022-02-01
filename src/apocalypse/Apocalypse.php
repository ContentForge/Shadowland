<?php

namespace apocalypse;

use apocalypse\world\ApocalypseGenerator;
use pocketmine\plugin\PluginBase;
use pocketmine\world\generator\GeneratorManager;

class Apocalypse extends PluginBase {
    protected function onLoad(): void {
        GeneratorManager::getInstance()->addGenerator(ApocalypseGenerator::class, "apocalypse", fn() => null);
    }

    protected function onEnable(): void {

    }

    protected function onDisable(): void {

    }
}