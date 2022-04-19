<?php

namespace apocalypse\item;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class ItemCopperWire extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "CopperWire") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§7Продукция JustConsole Inc."
        ]);
    }

    public function getRuName(): string {
        return "Катушка медной проволоки";
    }

    public function getTexturePath(): string {
        return "textures/items/copper_wire";
    }

    public function getFullId(): string {
        return "apocalypse:copper_wire";
    }
}