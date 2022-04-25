<?php

namespace apocalypse\world\biome;

use pocketmine\utils\Random;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\generator\noise\Simplex;
use pocketmine\world\Position;

class ApocalypseBiomeManager {
    use SingletonTrait;

    private array $biomes = [];
    private int $seed = 0;
    private Random $random;
    private Simplex $simplex;

    private function __construct() {
        $this->random = new Random(0);
        $this->simplex = new Simplex($this->random, 1, 0.4, 1/32.0);

        $this->registerBiome(new AshBiome());
        $this->registerBiome(new FireBiome());
        $this->registerBiome(new NormalBiome());
    }

    private function registerBiome(ApocalypseBiome $biome): void {
        $this->biomes[$biome->getBiomeId()] = $biome;
    }

    public function setSeed(int $seed): void {
        $this->seed = $seed;
        $this->random->setSeed($seed);
        $this->simplex = new Simplex($this->random, 1, 0.4, 1/32.0);
    }

    public function getBiomeMap(int $chunkX, int $chunkZ): array {
        $cx = $chunkX >> 4;
        $cz = $chunkZ >> 4;

        $map = [];
        for ($i = -1; $i < 2; $i++) {
            $tcx = $cx + $i;
            for ($j = -1; $j < 2; $j++) {
                $tcz = $cz + $j;
                $this->random->setSeed($tcx * 9901 + $tcz);

                $map[] = [
                    'x' => ($tcx << 8) + $this->random->nextRange(0, 256),
                    'z' => ($tcz << 8) + $this->random->nextRange(0, 256),
                    'biome' => array_values($this->biomes)[$this->random->nextRange(0, count($this->biomes) - 1)],
                ];
            }
        }

        return $map;
    }

    public function pickBiome(int $x, int $z, array $biomeMap): ApocalypseBiome {
        $hash = $x * 2345803 ^ $z * 9236449 ^ $this->seed;
        $hash *= $hash + 223;
        $xNoise = $hash >> 20 & 3;
        $zNoise = $hash >> 22 & 3;
        if($xNoise == 3){
            $xNoise = 1;
        }
        if($zNoise == 3){
            $zNoise = 1;
        }
        $nx = $x + $xNoise - 1;
        $nz = $z + $zNoise - 1;

        $nx = $nx + $nx * $this->simplex->getNoise2D($x / 256, $z / 256) * 0.05;
        $nz = $nz + $nz * $this->simplex->getNoise2D($z / -256, $x / -256) * 0.05;

        $nearestBiome = 0;
        $d = PHP_INT_MAX;
        foreach ($biomeMap as $obj) {
            $td = ($nx - $obj['x']) ** 2 + ($nz - $obj['z']) ** 2;
            if ($td < $d) {
                $d = $td;
                $nearestBiome = $obj['biome'];
            }
        }

        return $nearestBiome;
    }

    public function getBiome(Position $pos): ?ApocalypseBiome {
        $x = (int) $pos->x;
        $z = (int) $pos->z;
        $chunk = $pos->getWorld()->getChunk($x >> 4, $z >> 4);

        if ($chunk === null) return null;

        return $this->biomes[$chunk->getBiomeId($x & 0xF, $z & 0xF)] ?? null;
    }
}