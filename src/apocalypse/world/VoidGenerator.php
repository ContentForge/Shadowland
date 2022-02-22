<?php

namespace apocalypse\world;

use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\Generator;

class VoidGenerator extends Generator {

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        if($chunkX == 0 && $chunkZ == 0){
            for($x = 0; $x < 16; $x++){
                for($z = 0; $z < 16; $z++){
                    $world->setBlockAt($x, 30, $z, VanillaBlocks::STONE());
                }
            }
        }
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {

    }
}