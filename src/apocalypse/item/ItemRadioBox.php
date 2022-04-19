<?php

namespace apocalypse\item;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class ItemRadioBox extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "RadioBox") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§7Содержит случайные радиоматериалы.",
            "",
            "§r§7Продукция JustConsole Inc."
        ]);
    }

    public function getRuName(): string {
        return "Коробка с продукцией JustConsole";
    }

    public function getTexturePath(): string {
        return "textures/items/radio_box";
    }

    public function getFullId(): string {
        return "apocalypse:radio_box";
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        //TODO
        $this->pop();
        return ItemUseResult::SUCCESS();
    }
}