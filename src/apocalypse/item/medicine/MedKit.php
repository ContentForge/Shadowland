<?php

namespace apocalypse\item\medicine;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

abstract class MedKit extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function getMaxStackSize(): int {
        return 4;
    }

    public abstract function onUse(Player $player): void;

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        $this->onUse($player);
        $this->pop();
        return ItemUseResult::SUCCESS();
    }
}