<?php

namespace apocalypse\item\battery;

class ItemBattery extends Battery {

    public function getMaxCharge(): int {
        return 25;
    }

    public function getRuName(): string {
        return "Батарейка";
    }

    public function getFullId(): string {
        return "apocalypse:battery";
    }
}