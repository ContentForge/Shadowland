<?php

namespace apocalypse\world\biome;

class ApocalypseBiome {

    private int $biomeId;

    public function __construct(int $biomeId) {
        $this->biomeId = $biomeId;
    }

    public function getBiomeId(): int {
        return $this->biomeId;
    }

    public function getMinRadiationLevel(): int {
        return 10;
    }

    public function getMaxRadiationLevel(): int {
        return 20;
    }
}