<?php

namespace apocalypse\item\battery;

class ItemSmallBattery extends Battery {

    public function getMaxCharge(): int {
        return 15;
    }

    public function getRuName(): string {
        return "Маленькая батарейка";
    }

    public function getFullId(): string {
        return "apocalypse:small_battery";
    }
}