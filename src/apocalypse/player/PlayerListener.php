<?php

namespace apocalypse\player;

use pocketmine\event\Listener;
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
}