<?php

namespace apocalypse\world;

use apocalypse\world\biome\ApocalypseBiomeManager;
use apocalypse\world\populator\CityPopulator;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\BiomeIds;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\generator\Generator;

class ApocalypseGenerator extends Generator {

    /* ———————————No glitches?————————— *
        ⠀⣞⢽⢪⢣⢣⢣⢫⡺⡵⣝⡮⣗⢷⢽⢽⢽⣮⡷⡽⣜⣜⢮⢺⣜⢷⢽⢝⡽⣝
        ⠸⡸⠜⠕⠕⠁⢁⢇⢏⢽⢺⣪⡳⡝⣎⣏⢯⢞⡿⣟⣷⣳⢯⡷⣽⢽⢯⣳⣫⠇
        ⠀⠀⢀⢀⢄⢬⢪⡪⡎⣆⡈⠚⠜⠕⠇⠗⠝⢕⢯⢫⣞⣯⣿⣻⡽⣏⢗⣗⠏⠀
        ⠀⠪⡪⡪⣪⢪⢺⢸⢢⢓⢆⢤⢀⠀⠀⠀⠀⠈⢊⢞⡾⣿⡯⣏⢮⠷⠁⠀⠀
        ⠀⠀⠀⠈⠊⠆⡃⠕⢕⢇⢇⢇⢇⢇⢏⢎⢎⢆⢄⠀⢑⣽⣿⢝⠲⠉⠀⠀⠀⠀
        ⠀⠀⠀⠀⠀⡿⠂⠠⠀⡇⢇⠕⢈⣀⠀⠁⠡⠣⡣⡫⣂⣿⠯⢪⠰⠂⠀⠀⠀⠀
        ⠀⠀⠀⠀⡦⡙⡂⢀⢤⢣⠣⡈⣾⡃⠠⠄⠀⡄⢱⣌⣶⢏⢊⠂⠀⠀⠀⠀⠀⠀
        ⠀⠀⠀⠀⢝⡲⣜⡮⡏⢎⢌⢂⠙⠢⠐⢀⢘⢵⣽⣿⡿⠁⠁⠀⠀⠀⠀⠀⠀⠀
        ⠀⠀⠀⠀⠨⣺⡺⡕⡕⡱⡑⡆⡕⡅⡕⡜⡼⢽⡻⠏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
        ⠀⠀⠀⠀⣼⣳⣫⣾⣵⣗⡵⡱⡡⢣⢑⢕⢜⢕⡝⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
        ⠀⠀⠀⣴⣿⣾⣿⣿⣿⡿⡽⡑⢌⠪⡢⡣⣣⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
        ⠀⠀⠀⡟⡾⣿⢿⢿⢵⣽⣾⣼⣘⢸⢸⣞⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
        ⠀⠀⠀⠀⠁⠇⠡⠩⡫⢿⣝⡻⡮⣒⢽⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
     * ———————————————————————————————— */

    private const BIOME_NOISE = 100;
    public const HEIGHT = 60;
    private const BIOMES = [BiomeIds::HELL, BiomeIds::CRIMSON_FOREST, BiomeIds::WARPED_FOREST, BiomeIds::SOULSAND_VALLEY, BiomeIds::BASALT_DELTAS];

    private CityPopulator $city;

    public function __construct(int $seed, string $preset) {
        parent::__construct(0xdeadbeef, $preset);

        ApocalypseBiomeManager::getInstance()->setSeed($seed);
        $this->city = new CityPopulator($this->random);
    }

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        $chunk = $world->getChunk($chunkX, $chunkZ);

        $x = $chunkX << Chunk::COORD_BIT_SIZE;
        $z = $chunkZ << Chunk::COORD_BIT_SIZE;

        $biomeMap = ApocalypseBiomeManager::getInstance()->getBiomeMap($chunkX, $chunkZ);

        for($dx = 0; $dx < Chunk::EDGE_LENGTH; $dx++){
            for($dz = 0; $dz < Chunk::EDGE_LENGTH; $dz++){
                $biome = ApocalypseBiomeManager::getInstance()->pickBiome($x + $dx, $z + $dz, $biomeMap);
                $chunk = $world->getChunk($chunkX, $chunkZ);

                $chunk->setBiomeId($dx, $dz, $biome->getBiomeId());

                for($y = 0; $y <= self::HEIGHT; $y++){
                    $world->setBlockAt($x + $dx, $y, $z + $dz, VanillaBlocks::DIRT()->setCoarse(true));
                }
            }
        }
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        $chunk = $world->getChunk($chunkX, $chunkZ);

        $this->city->populate($world, $chunkX, $chunkZ, $this->random);
    }

}