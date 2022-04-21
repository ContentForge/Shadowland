<?php

namespace apocalypse\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerListener implements Listener {

    public function __construct(private PlayerManager $manager) {

    }

    public function onLogin(PlayerLoginEvent $event){
        $this->manager->join($event->getPlayer());
    }

    public function onQuit(PlayerQuitEvent $event){
        $this->manager->quit($event->getPlayer());
    }

    public function onDeath(PlayerDeathEvent $event){
        $this->manager->getPlayer($event->getPlayer())->onDeath();
    }
}