<?php

namespace apocalypse\immersive\storm;

use apocalypse\immersive\ImmersiveManager;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class StormManager implements ImmersiveManager {
    use SingletonTrait;

    public int $timeLeft = 0;
    public int $passedTime = 0;
    public ?Storm $storm = null;
    public ?Storm $next = null;
    public array $storms;

    public function __construct() {
        $this->storms = [
            new SolarFlareStorm(),
        ];

        $this->pickStorm();
    }

    private function pickStorm(): void {
        $this->storm = $this->next;
        $this->next = $this->storms[array_rand($this->storms)];
        $this->passedTime = 0;

        if ($this->storm === null) $this->timeLeft = mt_rand(420, 1200);
        else $this->timeLeft = mt_rand((int) ($this->storm->getAverageDuration() * 0.95), (int) ($this->storm->getAverageDuration() * 1.05));
    }

    public function tick(): void {
        $this->storm?->tick($this->timeLeft, $this->passedTime++);

        if ($this->timeLeft-- > 1) {
            return;
        }

        $this->pickStorm();
    }

    public function onJoin(Player $player) {

    }

    public function onQuit(Player $player) {

    }
}