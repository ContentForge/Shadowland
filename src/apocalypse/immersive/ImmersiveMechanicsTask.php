<?php

namespace apocalypse\immersive;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

class ImmersiveMechanicsTask extends Task {

    private PluginBase $plugin;
    private array $managers;

    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;

        $this->managers = [

        ];
    }

    public function onRun(): void {
        foreach ($this->managers as $manager) $manager->tick();
    }
}