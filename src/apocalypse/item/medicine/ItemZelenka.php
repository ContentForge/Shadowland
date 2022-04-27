<?php

namespace apocalypse\item\medicine;

use apocalypse\player\PlayerManager;
use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class ItemZelenka extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Zelenka") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§2+ Вывод радионуклидов (-500Р)",
            "§r§4- Силное чувство голода",
            "",
            "§r§7Производство НИИ Пророк"
        ]);
    }


    public function getRuName(): string {
        return "Радиопротектор Зеленка";
    }

    public function getTexturePath(): string {
        return "textures/items/medicine/zelenka";
    }

    public function getFullId(): string {
        return "apocalypse:zelenka";
    }

    public function getMaxStackSize(): int {
        return 8;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        $pos = $player->getPosition();
        $pk = new PlaySoundPacket();
        $pk->volume = 1;
        $pk->pitch = 1;
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $pk->soundName = "apocalypse.medicine.zelenka";
        $player->getNetworkSession()->sendDataPacket($pk);

        $effects = [];

        //TODO: Эффекты

        PlayerManager::getInstance()->getPlayer($player)->updateRadLevel(-500000);
        foreach ($effects as $effect) $player->getEffects()->add($effect);

        $this->pop();
        return ItemUseResult::SUCCESS();
    }


}