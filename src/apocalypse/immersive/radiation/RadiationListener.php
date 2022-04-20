<?php

namespace apocalypse\immersive\radiation;

use apocalypse\world\biome\ApocalypseBiomeManager;
use pocketmine\event\Listener;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\ChunkUnloadEvent;
use pocketmine\world\Position;

class RadiationListener implements Listener {

    private RadiationManager $radiationManager;

    public function __construct(RadiationManager $radiationManager) {
        $this->radiationManager = $radiationManager;
    }

    public function onChunkLoad(ChunkLoadEvent $event) {
        //TODO: Распознование мира
        $cx = $event->getChunkX();
        $cz = $event->getChunkZ();

        $this->radiationManager->chunks[$cx][$cz] = [];
        for($scx = 0; $scx < 4; $scx++) {
            for($scz = 0; $scz < 4; $scz++) {
                $rad = $this->radiationManager->simplex->getNoise2D(
                        ($cx * 4 + $scx) / 25,
                        ($cz * 4 + $scz) / 25,
                    ) + 1;
                $rad = ($rad + 1) / 2;

                $biome = ApocalypseBiomeManager::getInstance()->getBiome(new Position(
                    ($cx << 4) + ($scx << 2),
                    0,
                    ($cz << 4) + ($scz << 2),
                    $event->getWorld()
                    ));

                if ($biome === null) $rad = 0;
                else $rad = $biome->getMinRadiationLevel() + ($biome->getMaxRadiationLevel() - $biome->getMinRadiationLevel()) * $rad;
                $this->radiationManager->chunks[$cx][$cz][$scx][$scz] = (int) $rad;
            }
        }
    }

    public function onChunkUnload(ChunkUnloadEvent $event) {
        $cx = $event->getChunkX();
        $cz = $event->getChunkZ();

        unset($this->radiationManager->chunks[$cx][$cz]);
    }
}