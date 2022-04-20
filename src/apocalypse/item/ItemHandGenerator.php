<?php

namespace apocalypse\item;

use apocalypse\item\battery\Battery;
use apocalypse\util\item\battery\BatterySelection;
use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use form\SimpleForm;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class ItemHandGenerator extends Item implements CustomItem {
    use BasicComponentDataTrait;

    private int $step = 10;
    public int $selectedItemSlot = -1;

    public function __construct(ItemIdentifier $identifier, string $name = "HandGenerator") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§7Позволяет заряжать батареи и аккумуляторы",
            "",
            "§r§7Продукция JustConsole Inc."
        ]);
    }

    public function getMaxStackSize(): int {
        return 1;
    }

    public function getRuName(): string {
        return "Ручная динамо-машина";
    }

    public function getTexturePath(): string {
        return "textures/items/hand_generator";
    }

    public function getFullId(): string {
        return "apocalypse:hand_generator";
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        $this->sendMainForm($player);
        return ItemUseResult::SUCCESS();
    }

    public function sendMainForm(Player $player): void {
        if ($this->selectedItemSlot === -1) {

            $this->sendSelectionForm($player);
            return;
        }

        $selectedItem = $player->getInventory()->getItem($this->selectedItemSlot);
        if (!$selectedItem instanceof Battery) {

            $this->sendSelectionForm($player);
            return;
        }

        $form = new SimpleForm();
        $form->setTitle("Ручная динамо-машина");
        $form->setContent(
            "Заряд выбранной батареи: §6§l". $selectedItem->formatCharge() ."§r\n".
                    "Для подзарядки потребуется активно качать ручку динамо-машины."
        );

        $form->addButton("Использовать", SimpleForm::IMAGE_TYPE_PATH, "textures/items/hand_generator", function(Player $player) use ($selectedItem) {
            $pk = new PlaySoundPacket();
            $pk->soundName = "apocalypse.generator.hand";
            $pos = $player->getPosition();
            $pk->x = $pos->x;
            $pk->y = $pos->y;
            $pk->z = $pos->z;
            $pk->pitch = 1;
            $pk->volume = 1;
            $player->getNetworkSession()->sendDataPacket($pk);

            if ($this->step-- < 0) {
                $this->step = mt_rand(7, 15);
                $selectedItem->updateCharge(1);
                $selectedItem->updateItem($player, $this->selectedItemSlot);
            }

            $this->sendMainForm($player);
        });

        $form->addButton("Выбрать другую батарею", SimpleForm::IMAGE_TYPE_PATH, "textures/items/battery", function(Player $player) {
            $this->sendSelectionForm($player);
        });

        $form->sendToPlayer($player);
    }

    private function sendSelectionForm(Player $player): void {
        $selection = new BatterySelection($player, "Выберите батарейку или аккумулятор, который вы хотите зарядить", function(Player $player, int $slot, Battery $battery) {
            $this->selectedItemSlot = $slot;

            $this->sendMainForm($player);
        });
        $selection->sendForm();
    }

    protected function serializeCompoundTag(CompoundTag $tag): void {
        parent::serializeCompoundTag($tag);

        $tag->setShort("b_slot", $this->selectedItemSlot);
    }

    protected function deserializeCompoundTag(CompoundTag $tag): void {
        parent::deserializeCompoundTag($tag);

        $this->selectedItemSlot = $tag->getShort("b_slot", -1);
    }
}