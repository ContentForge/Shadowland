<?php

namespace apocalypse\immersive;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

class ImmersiveMechanicsTask extends Task implements Listener {

    /** @var ImmersiveManager[] */
    private array $managers;

    public function __construct(PluginBase $plugin) {
        $this->managers = [

        ];

        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }

    public function onRun(): void {
        foreach ($this->managers as $manager) $manager->tick();
    }

    public function onJoin(PlayerJoinEvent $event){
        foreach ($this->managers as $manager) $manager->onJoin($event->getPlayer());
    }

    public function onQuit(PlayerQuitEvent $event){
        foreach ($this->managers as $manager) $manager->onQuit($event->getPlayer());
    }
}