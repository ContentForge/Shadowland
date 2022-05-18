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
use apocalypse\item\medicine\ItemZelenka;
use apocalypse\ppc\PPC;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\utils\CloningRegistryTrait;

/**
 * @method static ItemRadio RADIO()
 * @method static ItemSmallBattery SMALL_BATTERY()
 * @method static ItemBattery NORMAL_BATTERY()
 * @method static ItemBigBattery BIG_BATTERY()
 * @method static ItemCapacitor CAPACITOR()
 * @method static ItemChip CHIP
 * @method static ItemCircuit CIRCUIT()
 * @method static ItemCopperWire COPPER_WIRE()
 * @method static ItemDuctTape DUCT_TAPE()
 * @method static ItemRadioBios RADIO_BIOS()
 * @method static ItemRadioBox RADIO_BOX()
 * @method static ItemTransistor TRANSISTOR()
 * @method static ItemHandGenerator HAND_GENERATOR()
 * @method static ItemBlueMedKit BLUE_MEDKIT()
 * @method static ItemGreenMedKit GREEN_MEDKIT()
 * @method static ItemOrangeMedKit ORANGE_MEDKIT()
 * @method static ItemYellowMedKit YELLOW_MEDKIT()
 * @method static ItemYodadulin YODADULIN()
 * @method static ItemZelenka ZELENKA()
 * @method static PPC PPC()
 */
final class ApocalypseItems {
    use CloningRegistryTrait;

    private function __construct(){

    }

    protected static function register(string $name, Item $block) : void{
        self::_registryRegister($name, $block);
    }

    /**
     * @return Item[]
     */
    public static function getAll() : array{
        /** @var Item[] $result */
        $result = self::_registryGetAll();
        return $result;
    }
    protected static function setup() : void {
        $factory = ItemFactory::getInstance();
        self::register("radio", $factory->get(ApocalypseItemsIds::RADIO, 0));
        self::register("small_battery", $factory->get(ApocalypseItemsIds::SMALL_BATTERY, 0));
        self::register("normal_battery", $factory->get(ApocalypseItemsIds::NORMAL_BATTERY, 0));
        self::register("big_battery", $factory->get(ApocalypseItemsIds::BIG_BATTERY, 0));
        self::register("capacitor", $factory->get(ApocalypseItemsIds::CAPACITOR, 0));
        self::register("chip", $factory->get(ApocalypseItemsIds::CHIP, 0));
        self::register("circuit", $factory->get(ApocalypseItemsIds::CIRCUIT, 0));
        self::register("copper_wire", $factory->get(ApocalypseItemsIds::COPPER_WIRE, 0));
        self::register("duct_tape", $factory->get(ApocalypseItemsIds::DUCT_TAPE, 0));
        self::register("radio_bios", $factory->get(ApocalypseItemsIds::RADIO_BIOS, 0));
        self::register("radio_box", $factory->get(ApocalypseItemsIds::RADIO_BOX, 0));
        self::register("transistor", $factory->get(ApocalypseItemsIds::TRANSISTOR, 0));
        self::register("hand_generator", $factory->get(ApocalypseItemsIds::HAND_GENERATOR, 0));
        self::register("blue_medkit", $factory->get(ApocalypseItemsIds::BLUE_MEDKIT, 0));
        self::register("green_medkit", $factory->get(ApocalypseItemsIds::GREEN_MEDKIT, 0));
        self::register("orange_medkit", $factory->get(ApocalypseItemsIds::ORANGE_MEDKIT, 0));
        self::register("yellow_medkit", $factory->get(ApocalypseItemsIds::YELLOW_MEDKIT, 0));
        self::register("yodadulin", $factory->get(ApocalypseItemsIds::YODADULIN, 0));
        self::register("zelenka", $factory->get(ApocalypseItemsIds::ZELENKA, 0));
        self::register("ppc", $factory->get(ApocalypseItemsIds::PPC, 0));
    }

}