<?php

namespace apocalypse\player;

use apocalypse\chat\IChatSender;
use pocketmine\player\Player;
use pocketmine\world\Position;

class PlayerData implements IChatSender {

    public function __construct(private Player $player, private PlayerManager $manager) {

    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getName(): string {
        return $this->player->getName();
    }

    public function getPosition(): Position {
        return $this->player->getPosition();
    }
}