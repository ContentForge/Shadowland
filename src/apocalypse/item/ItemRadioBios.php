<?php

namespace apocalypse\item;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class ItemRadioBios extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "RadioBios") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§7Чип, с помощю которого можно подменить",
            "§r§7серийный номер радиостанции",
            "",
            "§r§7Продукция JustConsole Inc."
        ]);
    }

    public function getRuName(): string {
        return "Контроллер радиостанции JustConsole";
    }

    public function getTexturePath(): string {
        return "textures/items/radio_bios";
    }

    public function getFullId(): string {
        return "apocalypse:radio_bios";
    }
}