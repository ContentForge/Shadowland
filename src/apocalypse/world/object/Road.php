<?php

namespace apocalypse\world\object;

use apocalypse\world\ApocalypseGenerator;
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

        $sides = 0;
        if($forwardSide) $sides++;
        if($rightSide) $sides++;
        if($backSide) $sides++;
        if($leftSide) $sides++;

        for($dx = 0; $dx < 16; $dx++){
            for($dz = 0; $dz < 16; $dz++){
                $y = $chunk->getHighestBlockAt($dx, $dz);

                $forward = $dx > 12; $back = $dx < 3; $right = $dz > 12; $left = $dz < 3;
                $sx = $back || $forward; $sz = $left || $right;
                if($sx || $sz){
                    if($sx && $sz){
                        $this->placeSidewalk($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction);
                        continue;
                    }

                    if($forward){
                        if($forwardSide) $this->placeRoad($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction, $sides > 2 && $dz%2 === 0);
                        else $this->placeSidewalk($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction);
                        continue;
                    }

                    if($right){
                        if($rightSide) $this->placeRoad($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction, $sides > 2 && $dx%2 === 0);
                        else $this->placeSidewalk($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction);
                        continue;
                    }

                    if($back){
                        if($backSide) $this->placeRoad($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction, $sides > 2 && $dz%2 === 0);
                        else $this->placeSidewalk($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction);
                        continue;
                    }

                    if($left){
                        if($leftSide) $this->placeRoad($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction, $sides > 2 && $dx%2 === 0);
                        else $this->placeSidewalk($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction);
                        continue;
                    }
                } else $this->placeRoad($x + $dx, ApocalypseGenerator::HEIGHT, $z + $dz, $random, $transaction);
            }
        }

        if(!$forwardSide) (new RoadLamp($world, $x + 14, ApocalypseGenerator::HEIGHT + 1, $z + 7, $random, RotatableObject::BACK))->placeObject($transaction);
        if(!$backSide) (new RoadLamp($world, $x + 1, ApocalypseGenerator::HEIGHT + 1, $z + 7, $random, RotatableObject::FORWARD))->placeObject($transaction);
        if(!$rightSide) (new RoadLamp($world, $x + 7, ApocalypseGenerator::HEIGHT + 1, $z + 14, $random, RotatableObject::LEFT))->placeObject($transaction);
        if(!$leftSide) (new RoadLamp($world, $x + 7, ApocalypseGenerator::HEIGHT + 1, $z + 1, $random, RotatableObject::RIGHT))->placeObject($transaction);

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

    private function placeRoad(int $x, int $y, int $z, Random $random, BlockTransaction $transaction, bool $print = false){
        $transaction->addBlockAt($x, $y, $z, match ($random->nextRange(0, 6)){
            1 => VanillaBlocks::COBBLESTONE(),
            2 => VanillaBlocks::COBBLESTONE_SLAB(),
            default => $print? VanillaBlocks::DIORITE() : VanillaBlocks::CONCRETE_POWDER()->setColor(DyeColor::GRAY()),
        });
        for($dy = $y - 3; $dy < $y; $dy++) $transaction->addBlockAt($x, $dy, $z, VanillaBlocks::GRAVEL());
    }

}