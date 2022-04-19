<?php

namespace apocalypse\item;

use apocalypse\item\battery\ItemBattery;
use apocalypse\item\battery\ItemBigBattery;
use apocalypse\item\battery\ItemSmallBattery;
use expo\item\ItemManager;
use pocketmine\item\ItemIdentifier;

class ApocalypseItemsIds {

    public const RADIO = 10000;
    public const SMALL_BATTERY = 10001;
    public const NORMAL_BATTERY = 10002;
    public const BIG_BATTERY = 10003;

    private static bool $init = false;

    private function __construct() {

    }

    public static function initItems(): void {
        if (self::$init) return;
        self::$init = true;
        $m = ItemManager::getInstance();

        $m->registerItem(new ItemRadio(new ItemIdentifier(self::RADIO, 0)));
        $m->registerItem(new ItemSmallBattery(new ItemIdentifier(self::SMALL_BATTERY, 0), "SmallBattery"));
        $m->registerItem(new ItemBattery(new ItemIdentifier(self::NORMAL_BATTERY, 0), "NormalBattery"));
        $m->registerItem(new ItemBigBattery(new ItemIdentifier(self::BIG_BATTERY, 0), "BigBattery"));
    }
}