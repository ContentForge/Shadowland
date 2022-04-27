<?php

namespace apocalypse\item;

use apocalypse\item\battery\ItemBattery;
use apocalypse\item\battery\ItemBigBattery;
use apocalypse\item\battery\ItemSmallBattery;
use apocalypse\item\medicine\ItemBlueMedKit;
use apocalypse\item\medicine\ItemGreenMedKit;
use apocalypse\item\medicine\ItemOrangeMedKit;
use apocalypse\item\medicine\ItemYellowMedKit;
use apocalypse\item\medicine\ItemYodadulin;
use expo\item\ItemManager;
use pocketmine\item\ItemIdentifier;

class ApocalypseItemsIds {

    public const RADIO = 10000;
    public const SMALL_BATTERY = 10001;
    public const NORMAL_BATTERY = 10002;
    public const BIG_BATTERY = 10003;
    public const CAPACITOR = 10004;
    public const CHIP = 10005;
    public const CIRCUIT = 10006;
    public const COPPER_WIRE = 10007;
    public const DUCT_TAPE = 10008;
    public const RADIO_BIOS = 10009;
    public const RADIO_BOX = 10010;
    public const TRANSISTOR = 10011;
    public const HAND_GENERATOR = 10012;
    public const BLUE_MEDKIT = 10013;
    public const GREEN_MEDKIT = 10014;
    public const ORANGE_MEDKIT = 10015;
    public const YELLOW_MEDKIT = 10016;
    public const YODADULIN = 10017;

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
        $m->registerItem(new ItemCapacitor(new ItemIdentifier(self::CAPACITOR, 0)));
        $m->registerItem(new ItemChip(new ItemIdentifier(self::CHIP, 0)));
        $m->registerItem(new ItemCircuit(new ItemIdentifier(self::CIRCUIT, 0)));
        $m->registerItem(new ItemCopperWire(new ItemIdentifier(self::COPPER_WIRE, 0)));
        $m->registerItem(new ItemDuctTape(new ItemIdentifier(self::DUCT_TAPE, 0)));
        $m->registerItem(new ItemRadioBios(new ItemIdentifier(self::RADIO_BIOS, 0)));
        $m->registerItem(new ItemRadioBox(new ItemIdentifier(self::RADIO_BOX, 0)));
        $m->registerItem(new ItemTransistor(new ItemIdentifier(self::TRANSISTOR, 0)));
        $m->registerItem(new ItemHandGenerator(new ItemIdentifier(self::HAND_GENERATOR, 0)));
        $m->registerItem(new ItemBlueMedKit(new ItemIdentifier(self::BLUE_MEDKIT, 0)));
        $m->registerItem(new ItemGreenMedKit(new ItemIdentifier(self::GREEN_MEDKIT, 0)));
        $m->registerItem(new ItemOrangeMedKit(new ItemIdentifier(self::ORANGE_MEDKIT, 0)));
        $m->registerItem(new ItemYellowMedKit(new ItemIdentifier(self::YELLOW_MEDKIT, 0)));
        $m->registerItem(new ItemYodadulin(new ItemIdentifier(self::YODADULIN, 0)));
    }
}