<?php

namespace apocalypse\world\object;

use pocketmine\block\VanillaBlocks;


class RoadLamp extends RotatableObject {

    protected function makeObject() {
        for($i = 0; $i < 2; $i++) $this->setBlock(0, $i, 0, VanillaBlocks::SMOOTH_STONE());
        for($i = 2; $i < 8; $i++) $this->setBlock(0, $i, 0, VanillaBlocks::ANDESITE_WALL());

        for($i = 0; $i < 2; $i++) $this->setBlock($i, 8, 0, VanillaBlocks::STONE_SLAB());
        for($i = 2; $i < 4; $i++) $this->setBlock($i, 8, 0, VanillaBlocks::STONE());
        for($i = 2; $i < 4; $i++) $this->setBlock($i, 7, 0, VanillaBlocks::IRON_TRAPDOOR()->setTop(true));
    }

}