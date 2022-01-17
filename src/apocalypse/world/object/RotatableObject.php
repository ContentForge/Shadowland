<?php

namespace apocalypse\world\object;

use apocalypse\util\RotatableObjectAdapter;
use pocketmine\block\Block;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;

abstract class RotatableObject {

    public const FORWARD = 0;
    public const RIGHT = 1;
    public const BACK = 2;
    public const LEFT = 3;

    private BlockTransaction $transaction;

    public function __construct(private ChunkManager $world, private int $x, private int $y, private int $z, protected Random $random, protected int $side){

    }

    public final function placeObject($transaction) {
        $this->transaction = $transaction;
        $this->makeObject();
    }

    protected abstract function makeObject();

    protected function setBlock(int $dx, int $dy, int $dz, Block $block){
        list($x, $y, $z) = $this->convert($dx, $dy, $dz);
        $this->transaction->addBlockAt($x, $y, $z, $block);
    }

    public function getBlock(int $dx, int $dy, int $dz): Block {
        list($x, $y, $z) = $this->convert($dx, $dy, $dz);
        return $this->world->getBlockAt($x, $y, $z);
    }

    public function getHighestBlock(int $dx, int $dz): ?int {
        list($x, $ignore, $z) = $this->convert($dx, 0, $dz);
        $chunk = $this->world->getChunk($x >> 4,$z >> 4);
        return $chunk->getHighestBlockAt($x & 0b1111, $z & 0b1111);
    }

    public function convert(int $x, int $y, int $z): array {
        switch ($this->side % 4){
            default:
            case self::FORWARD:
                return [$x + $this->x, $y + $this->y, $z + $this->z];

            case self::RIGHT:
                return [$z + $this->x, $y + $this->y, -$x + $this->z];

            case self::BACK:
                return [-$x + $this->x, $y + $this->y, -$z + $this->z];

            case self::LEFT:
                return [-$z + $this->x, $y + $this->y, $x + $this->z];
        }
    }

}