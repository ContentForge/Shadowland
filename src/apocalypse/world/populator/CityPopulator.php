<?php

namespace apocalypse\world\populator;

use apocalypse\world\object\Road;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\noise\Simplex;
use pocketmine\world\generator\populator\Populator;

class CityPopulator implements Populator {

    public const STREET_SIZE = 4;

    private Simplex $simplex;

    public function __construct(Random $random){
        $this->simplex = new Simplex($random, 1, 1/4, 1);
    }

    public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
        $cityLevel = $this->getCityLevel($chunkX, $chunkZ);
        if($cityLevel < 0) return;

        if($this->isRoad($chunkX, $chunkZ)){
            $this->placeRoad($world, $chunkX, $chunkZ, $random, $cityLevel);
            return;
        }

        //TODO: Генерация города
    }

    private function placeRoad(ChunkManager $world, int $chunkX, int $chunkZ, Random $random, float $cityLevel){
        $sides = 0;
        if($this->isRoad($chunkX + 1, $chunkZ)) $sides = $sides | Road::FORWARD;
        if($this->isRoad($chunkX, $chunkZ + 1)) $sides = $sides | Road::RIGHT;
        if($this->isRoad($chunkX - 1, $chunkZ)) $sides = $sides | Road::BACK;
        if($this->isRoad($chunkX, $chunkZ - 1)) $sides = $sides | Road::LEFT;

        $road = new Road($sides, $cityLevel);
        $transaction = $road->getBlockTransaction($world,  $chunkX, $chunkZ, $random);
        $transaction->apply();
    }

    public function isRoad(int $chunkX, int $chunkZ): bool {
        $px = $chunkX % self::STREET_SIZE == 0; $pz = $chunkZ % self::STREET_SIZE == 0;
        if(!$px && !$pz) return false;
        if($px && $pz) return $this->isRoad($chunkX + 1, $chunkZ) || $this->isRoad($chunkX - 1, $chunkZ) || $this->isRoad($chunkX, $chunkZ + 1) || $this->isRoad($chunkX, $chunkZ - 1);
        return abs($this->simplex->getNoise2D((int)($chunkX/self::STREET_SIZE) / 3.0, (int)($chunkZ/self::STREET_SIZE) / 3.0)) < 0.37;
    }

    public function getCityLevel(int $chunkX, int $chunkZ): float {
        return 1; //TODO: Уровень развитости зоны
    }

}