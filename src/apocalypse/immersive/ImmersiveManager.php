<?php

namespace apocalypse\immersive;

use pocketmine\player\Player;

interface ImmersiveManager {

    public function tick(): void;

    public function onJoin(Player $player);

    public function onQuit(Player $player);
}