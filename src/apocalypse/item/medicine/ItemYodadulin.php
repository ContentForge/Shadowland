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

class ItemYodadulin extends Item implements CustomItem {
    use BasicComponentDataTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Yodadulin") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§2+ Мгновенное восстановление здоровья",
            "§r§2+ Вывод радионуклидов (-50Р)",
            "§r§4- Слабость",
            "§r§4- Чувство голода",
            "",
            "§r§7Производство НИИ Пророк"
        ]);
    }


    public function getRuName(): string {
        return "Йодадулин";
    }

    public function getTexturePath(): string {
        return "textures/items/medicine/yodadulin";
    }

    public function getFullId(): string {
        return "apocalypse:yodadulin";
    }

    public function getMaxStackSize(): int {
        return 8;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        $pos = $player->getPosition();
        $pk = new PlaySoundPacket();
        $pk->pitch = 1;
        $pk->volume = 1;
        $pk->soundName = "apocalypse.medicine.yodadulin";
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $player->getNetworkSession()->sendDataPacket($pk);

        $effects = [];

        //TODO: Эффекты

        PlayerManager::getInstance()->getPlayer($player)->updateRadLevel(-50000);
        foreach ($effects as $effect) $player->getEffects()->add($effect);

        $this->pop();
        return ItemUseResult::SUCCESS();
    }
}