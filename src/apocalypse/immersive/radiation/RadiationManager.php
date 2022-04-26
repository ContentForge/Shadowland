<?php

namespace apocalypse\immersive\radiation;

use apocalypse\immersive\ImmersiveManager;
use apocalypse\player\PlayerManager;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Random;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\generator\noise\Simplex;
use pocketmine\world\Position;

class RadiationManager implements ImmersiveManager {
    use SingletonTrait;

    public array $chunks = [];
    private Random $random;
    public Simplex $simplex;
    private array $players = [];
    private array $lastDose = [];

    private function __construct() {
        $this->random = new Random(0xdeadbeef);
        $this->simplex = new Simplex($this->random, 1, 0.25, 1/64.0);
    }

    public function init(PluginBase $plugin): void {
        $plugin->getServer()->getPluginManager()->registerEvents(new RadiationListener($this), $plugin);
        $plugin->getScheduler()->scheduleRepeatingTask(new RadIllnessTask(), 20);
    }

    public function onQuit(Player $player) {
        $xuid = $player->getXuid();

        unset($this->lastDose[$xuid]);
        unset($this->players[$xuid]);
    }

    public function getRadiation(Position $pos): int {
        //TODO: Распознование мира

        $cx = $pos->getFloorX() >> 4;
        $cz = $pos->getFloorZ() >> 4;

        return $this->chunks[$cx][$cz][((int) $pos->x) & 0xF][((int) $pos->z) & 0xF] ?? 0;
    }

    public function getRadiationGround(Position $pos): int {
        $rad = $this->getRadiation($pos);
        if ($rad === 0) return 0;

        //TODO: Проверка высоты в мире
        return $rad + (int) ((mt_rand(-7, 7) / 100.0) * $rad);
    }

    public function addDose(Player $player, int $rad): void {
        $this->players[$player->getXuid()] = ($this->players[$player->getXuid()] ?? 0) + $rad;
    }

    public function handleRadiation(Player $player): void {
        $rad = $this->players[$player->getXuid()] ?? 0;
        $rad += $this->getRadiationGround($player->getPosition());

        PlayerManager::getInstance()->getPlayer($player)->updateRadLevel($rad);
        $this->lastDose[$player->getXuid()] = $rad;
        $this->players[$player->getXuid()] = 0;
    }

    public function getLastDose(Player $player): int {
        return $this->lastDose[$player->getXuid()];
    }

    public function tick(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player->isCreative() || $player->isSpectator()) continue;

            $this->handleRadiation($player);
        }
    }

    public function onJoin(Player $player) {
        $this->players[$player->getXuid()] = 0;
        $this->lastDose[$player->getXuid()] = 0;
    }
}