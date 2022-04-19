<?php

namespace apocalypse\item;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class ItemTransistor extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Transistor") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§7Продукция JustConsole Inc."
        ]);
    }

    public function getRuName(): string {
        return "Транзистор";
    }

    public function getTexturePath(): string {
        return "textures/items/transistor";
    }

    public function getFullId(): string {
        return "apocalypse:transistor";
    }
}