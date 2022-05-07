<?php

namespace apocalypse\immersive\sun;

use apocalypse\immersive\ImmersiveManager;
use apocalypse\world\biome\ApocalypseBiomeManager;
use pocketmine\block\Door;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\Glass;
use pocketmine\block\Thin;
use pocketmine\block\Torch;
use pocketmine\block\Wall;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;

class BurningSun implements ImmersiveManager {
    use SingletonTrait;

    private static array $onClearSky;
    private array $players = [];

    private array $transparentBlocks;

    public function __construct() {
        $this->transparentBlocks = [
            Glass::class,
            Thin::class,
            Wall::class,
            Door::class,
            FenceGate::class,
            Fence::class,
            Torch::class,
        ];
    }

    public function tick(): void {
        self::$onClearSky = [];

        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            $underSky = $this->checkPosition($player);
            $dose = $this->players[$player->getId()] ?? 0;
            $biome = ApocalypseBiomeManager::getInstance()->getBiome($player->getPosition());

            if ($underSky) {
                self::$onClearSky[] = $player->getId();
                $add = $this->getDoseByTime(0.1 * ($biome === null? 1 : $biome->getSunMultiplier($player)), $player->getWorld()->getTimeOfDay());
                $dose = min($dose + $add, 1);
                if ($add !== 0.0) $player->sendTip("§g§l⚠ §cСолнечная радиация §g⚠");
            } else {
                $dose = max($dose - 0.25, 0);
            }
            $this->players[$player->getId()] = $dose;
            if ($dose === 0.0) return;

            if ($player->isCreative() || $player->isSpectator()) continue;
            $biome?->handleSunEffect($player, $dose, $underSky);
        }
    }

    public function onJoin(Player $player) {
        $this->players[$player->getId()] = 0;
    }

    public function onQuit(Player $player) {
        unset($this->players[$player->getId()]);
    }

    private function checkPosition(Player $player): bool {
        $world = $player->getWorld();
        $pos = $player->getPosition();

        for($y = World::Y_MAX, $pY = $pos->getY() + 1; $y > $pY; $y--){
            $block = $world->getBlockAt((int) $pos->getX(), $y, (int) $pos->getZ());
            if(!in_array($block::class, $this->transparentBlocks)) return false;
        }

        self::$onClearSky[] = $player->getId();
        return true;
    }

    public static function isUnderClearSky(Player $player): bool {
        return in_array($player->getId(), self::$onClearSky);
    }

    public function getDoseByTime(float $originalDose, int $time): float {
        $multiplier = 1;

        //TODO: Указать для каждого промежутка времени свой множитель

        return $originalDose * $multiplier;
    }
}