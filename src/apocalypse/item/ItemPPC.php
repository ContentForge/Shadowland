<?php

namespace apocalypse\item;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class ItemPPC extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "PPC") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§7Карманный персональный компьютер в котором уже",
            "§r§7установлены все необходимые утилиты для",
            "§r§7выживания в постапокалиптическом мире.",
            "",
            "§r§7Продукция JustConsole Inc."
        ]);
    }

    public function getRuName(): string {
        return "КПК";
    }

    public function getTexturePath(): string {
        return "textures/items/ppc";
    }

    public function getFullId(): string {
        return "apocalypse:ppc";
    }

    public function getMaxStackSize(): int {
        return 1;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        //TODO
        return ItemUseResult::SUCCESS();
    }
}