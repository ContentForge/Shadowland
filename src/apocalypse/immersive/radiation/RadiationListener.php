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
        for($dx = 0; $dx < 16; $dx++) {
            for($dz = 0; $dz < 16; $dz++) {
                $rad = $this->radiationManager->simplex->getNoise2D(
                        ($dx + ($cx << 4)) / 250,
                        ($dz + ($cz << 4)) / 250,
                    ) + 1;
                $rad /= 2.0;

                $biome = ApocalypseBiomeManager::getInstance()->getBiome(new Position(
                        $dx + ($cx << 4),
                        0,
                        $dz + ($cz << 4),
                        $event->getWorld()
                    ));

                if ($biome === null) $rad = 0;
                else $rad = $biome->getMinRadiationLevel() + ($biome->getMaxRadiationLevel() - $biome->getMinRadiationLevel()) * $rad;
                $this->radiationManager->chunks[$cx][$cz][$dx][$dz] = (int) $rad;
            }
        }
    }

    public function onChunkUnload(ChunkUnloadEvent $event) {
        $cx = $event->getChunkX();
        $cz = $event->getChunkZ();

        unset($this->radiationManager->chunks[$cx][$cz]);
    }
}