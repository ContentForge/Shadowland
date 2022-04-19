<?php

namespace apocalypse\item\battery;

class ItemBigBattery extends Battery {

    public function getMaxCharge(): int {
        return 50;
    }

    public function getRuName(): string {
        return "Большая батарейка";
    }

    public function getFullId(): string {
        return "apocalypse:big_battery";
    }
}