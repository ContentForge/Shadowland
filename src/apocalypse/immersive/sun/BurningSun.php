<?php

namespace apocalypse\immersive\sun;

use apocalypse\immersive\ImmersiveManager;
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

    private array $ignoredBlocks;

    public function __construct() {
        $this->ignoredBlocks = [
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
        $server = Server::getInstance();

        foreach ($server->getOnlinePlayers() as $player){
            if(!$this->checkPosition($player)) {
                $this->players[$player->getId()] = max($this->players[$player->getId()] - 1, 0);
                continue;
            }
            $dose = $this->players[$player->getId()];

            //TODO: Эффекты
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
            $block = $world->getBlockAt((int) ($pos->getX() + 0.5), $y, (int) ($pos->getZ() + 0.5));
            if($block->isTransparent() && !in_array($block::class, $this->ignoredBlocks)) return false;
        }

        self::$onClearSky[] = $player;
        return true;
    }

    public static function isUnderClearSky(Player $player): bool {
        return in_array($player, self::$onClearSky);
    }
}