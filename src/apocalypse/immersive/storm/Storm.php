<?php

namespace apocalypse\immersive\storm;

use pocketmine\player\Player;

abstract class Storm {

    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public abstract function getAverageDuration(): int;

    public abstract function tick(int $left, int $passed): void;

    public static function isUnderSky(Player $player): bool {
        $world = $player->getWorld();
        $pos = $player->getPosition();
        $h = $world->getHighestBlockAt((int) $pos->x + 0.5, (int) $pos->z + 0.5);

        return $pos->y + 1 <= $h;
    }
}