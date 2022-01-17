<?php

namespace apocalypse\world\object;

use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;

class Road {

    public const FORWARD = 0b1;
    public const RIGHT = 0b10;
    public const BACK = 0b100;
    public const LEFT = 0b1000;

    public function __construct(private int $sides, private float $cityLevel){

    }

    public function getBlockTransaction(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): BlockTransaction{
        $x = $chunkX << 4; $z = $chunkZ << 4;
        $transaction = new BlockTransaction($world);
        $chunk = $world->getChunk($chunkX, $chunkZ);

        $forwardSide = self::FORWARD & $this->sides;
        $rightSide = self::RIGHT & $this->sides;
        $backSide = self::BACK & $this->sides;
        $leftSide = self::LEFT & $this->sides;

        for($dx = 0; $dx < 16; $dx++){
            for($dz = 0; $dz < 16; $dz++){
                $y = $chunk->getHighestBlockAt($dx, $dz);

                $forward = $dx > 12; $back = $dx < 3; $right = $dz > 12; $left = $dz < 3;
                $sx = $back || $forward; $sz = $left || $right;
                if($sx || $sz){
                    if($sx && $sz){
                        $this->placeSidewalk($x + $dx, $y, $z + $dz, $random, $transaction);
                        continue;
                    }

                    if($forward){
                        if($forwardSide) $this->placeRoad($x + $dx, $y, $z + $dz, $random, $transaction);
                        else $this->placeSidewalk($x + $dx, $y, $z + $dz, $random, $transaction);
                        continue;
                    }

                    if($right){
                        if($rightSide) $this->placeRoad($x + $dx, $y, $z + $dz, $random, $transaction);
                        else $this->placeSidewalk($x + $dx, $y, $z + $dz, $random, $transaction);
                        continue;
                    }

                    if($back){
                        if($backSide) $this->placeRoad($x + $dx, $y, $z + $dz, $random, $transaction);
                        else $this->placeSidewalk($x + $dx, $y, $z + $dz, $random, $transaction);
                        continue;
                    }

                    if($left){
                        if($leftSide) $this->placeRoad($x + $dx, $y, $z + $dz, $random, $transaction);
                        else $this->placeSidewalk($x + $dx, $y, $z + $dz, $random, $transaction);
                        continue;
                    }
                } else $this->placeRoad($x + $dx, $y, $z + $dz, $random, $transaction);
            }
        }

        return $transaction;
    }

    private function placeSidewalk(int $x, int $y, int $z, Random $random, BlockTransaction $transaction){
        $transaction->addBlockAt($x, $y + 1, $z, match ($random->nextRange(0, 5)){
            1 => VanillaBlocks::STONE_BRICK_SLAB(),
            2 => VanillaBlocks::COBBLESTONE_SLAB(),
            default => VanillaBlocks::SMOOTH_STONE_SLAB(),
        });
        for($dy = $y - 2; $dy < $y + 1; $dy++) $transaction->addBlockAt($x, $dy, $z, VanillaBlocks::STONE_BRICKS());
    }

    private function placeRoad(int $x, int $y, int $z, Random $random, BlockTransaction $transaction){
        $transaction->addBlockAt($x, $y, $z, match ($random->nextRange(0, 6)){
            1 => VanillaBlocks::COBBLESTONE(),
            2 => VanillaBlocks::COBBLESTONE_SLAB(),
            default => VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::GRAY()),
        });
        for($dy = $y - 3; $dy < $y; $dy++) $transaction->addBlockAt($x, $dy, $z, VanillaBlocks::GRAVEL());
    }

}