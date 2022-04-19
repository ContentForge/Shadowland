<?php

namespace apocalypse\item\battery;

use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

abstract class Battery extends Item implements CustomItem {
    use BasicComponentDataTrait;

    private int $charge;

    public function __construct(ItemIdentifier $identifier, string $name = "Battery") {
        parent::__construct($identifier, $name);

        $this->charge = $this->getMaxCharge();
        $this->updateDescription();
    }

    public function updateDescription() {
        $this->setLore([
            "§r§7Заряд батареи: §3§l". $this->formatCharge() ."EU§r",
            "",
            "§r§7Продукция JustConsole Inc.",
        ]);
    }

    public function updateItem(Player $player, int $slot): void {
        $this->updateDescription();
        $player->getInventory()->setItem($slot, $this);
    }

    public function getCharge(): int {
        return $this->charge;
    }

    public function updateCharge(int $value): void {
        $this->charge += $value;
        if ($this->charge > $this->getMaxCharge()) $this->charge = $this->getMaxCharge();
        if ($this->charge < 0) $this->charge = 0;
    }

    public function formatCharge(): string {
        return $this->charge ."EU/". $this->getMaxCharge();
    }

    public abstract function getMaxCharge(): int;

    public function isEmpty(): bool {
        return $this->charge < 1;
    }

    public function getMaxStackSize(): int {
        return 1;
    }

    protected function serializeCompoundTag(CompoundTag $tag): void {
        parent::serializeCompoundTag($tag);

        $tag->setShort("b_charge", $this->charge);
    }

    protected function deserializeCompoundTag(CompoundTag $tag): void {
        parent::deserializeCompoundTag($tag);

        $this->charge = $tag->getShort("b_charge", 0);
    }

    public function getTexturePath(): string {
        return "textures/items/battery";
    }

    public function getTextureId(): string {
        return "apocalypse:battery";
    }
}