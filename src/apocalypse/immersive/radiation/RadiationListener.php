<?php

namespace apocalypse\immersive\radiation;

use pocketmine\event\Listener;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\ChunkUnloadEvent;

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
                $rad *= 10;

                //TODO: Подбор уровня радиации в зависимости от биома
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