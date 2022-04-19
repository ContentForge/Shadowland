<?php

namespace apocalypse\item;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class ItemChip extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Chip") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§7Продукция JustConsole Inc."
        ]);
    }

    public function getRuName(): string {
        return "Микросхема";
    }

    public function getTexturePath(): string {
        return "textures/items/chip";
    }

    public function getFullId(): string {
        return "apocalypse:chip";
    }
}