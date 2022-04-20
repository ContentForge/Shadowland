<?php

namespace apocalypse\item;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
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
        $f = ItemFactory::getInstance();
        switch (mt_rand(0, mt_rand(0, 10) == 0? 5 : 4)) {
            case 0:
                $item = $f->get(ApocalypseItemsIds::CAPACITOR, 0);
                $item->setCount(mt_rand(1, 5));
                break;

            case 1:
                $item = $f->get(ApocalypseItemsIds::CHIP, 0);
                $item->setCount(mt_rand(1, 3));
                break;

            case 2:
                $item = $f->get(ApocalypseItemsIds::CIRCUIT, 0);
                $item->setCount(mt_rand(1, 3));
                break;

            case 3:
                $item = $f->get(ApocalypseItemsIds::COPPER_WIRE, 0);
                $item->setCount(mt_rand(1, 3));
                break;

            case 4:
                $item = $f->get(ApocalypseItemsIds::TRANSISTOR, 0);
                $item->setCount(mt_rand(1, 5));
                break;

            default:
                $item = $f->get(ApocalypseItemsIds::RADIO_BIOS, 0);
        }

        $inv = $player->getInventory();
        if ($inv->canAddItem($item)) $inv->addItem($item);
        else $player->getWorld()->dropItem($player->getPosition(), $item);

        $pk = new PlaySoundPacket();
        $pos = $player->getPosition();
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $pk->volume = 1;
        $pk->pitch = 1;
        $pk->soundName = "apocalypse.box.radio";
        $player->getNetworkSession()->sendDataPacket($pk);

        $this->pop();
        return ItemUseResult::SUCCESS();
    }
}