<?php

namespace apocalypse\item;

use apocalypse\chat\ChatManager;
use apocalypse\chat\radio\FormsRadio;
use apocalypse\chat\radio\IRadio;
use expo\item\CustomItem;
use expo\item\data\BasicComponentDataTrait;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class ItemRadio extends Item implements CustomItem, IRadio {
    use BasicComponentDataTrait;

    private int $serialNumber = 0;
    private int $channel = 1000;
    private float $power = 0.5;
    private int $distance = 1000;
    private int $maxDistance = 1000;
    private int $charge = 50;
    private int $maxCharge = 50;

    public function __construct(ItemIdentifier $identifier, string $name = "Radio") {
        parent::__construct($identifier, $name);

        $this->updateDescription();
    }

    public function initSerialNumber(): void {
        $this->serialNumber = ChatManager::getInstance()->takeFreeSerialId();
    }

    public function updateDescription(): void {
        $this->setLore([
            "§r§7Серийный номер: §3§l". strtoupper(dechex($this->getSerialNumber())) ."§r",
            "§r§7Канал: §3§l". self::intToHz($this->getChannel()) ."§r",
            "§r§7Мощность приема: §3§l". ((int) ($this->getPower() * 100)) ."%§r",
            "§r§7Дальность отправки: §3§l". self::metersToKm($this->getDistance()) ."/". self::metersToKm($this->getMaxDistance()) ."§r",
            "§r§7Батарея: §3§l". $this->getCharge() ."EU/". $this->getMaxCharge() ."EU§r",
            "",
            "§r§7Продукция JustConsole Inc.",
        ]);
    }

    public function updateItem(Player $player): void {
        $this->updateDescription();
        $player->getInventory()->setItemInHand($this);
    }

    public function getRuName(): string {
        return "Радиостанция";
    }

    public function getTexturePath(): string {
        return "textures/items/radio";
    }

    public function getFullId(): string {
        return "apocalypse:radio";
    }

    public function getMaxStackSize(): int {
        return 1;
    }

    public function getChannel(): int {
        return $this->channel;
    }

    public function setChannel(int $channel): void {
        $this->channel = $channel;
    }

    public function getPower(): float {
        return $this->power;
    }

    public function addPower(float $power): void {
        $this->power += $power;
    }

    public function getDistance(): int {
        return $this->distance;
    }

    public function setDistance(int $distance): void {
        $this->distance = $distance;
    }

    public function getMaxDistance(): int {
        return $this->maxDistance;
    }

    public function addMaxDistance(int $distance): void {
        $this->maxDistance += $distance;
    }

    public function getSerialNumber(): string {
        return strtoupper(dechex($this->serialNumber));
    }

    public function getCharge(): int {
        return $this->charge;
    }

    public function updateCharge(int $delta): void {
        $this->charge += $delta;
    }

    public function getMaxCharge(): int {
        return $this->maxCharge;
    }

    public function addMaxCharge(int $charge): void {
        $this->maxCharge += $charge;
    }

    public static function intToHz(int $channel): string {
        return ($channel / 10) ."МГц";
    }

    public static function metersToKm(int $meters): string {
        return ($meters / 1000) ."км";
    }

    public function fuelRadio(Player $player, int $value): void {
        $prevCharge = $this->getCharge();
        if ($prevCharge === 0) {
            $pk = new PlaySoundPacket();
            $pk->pitch = 1;
            $pk->volume = 1;
            $pk->soundName = "apocalypse.radio.found";
            $pos = $player->getPosition();
            $pk->x = $pos->x;
            $pk->y = $pos->y;
            $pk->z = $pos->z;
            $player->getNetworkSession()->sendDataPacket($pk);
        }

        $this->updateCharge($value);
        $this->charge = min($this->maxCharge, $this->charge);

        if ($prevCharge === 0) {
            $player->sendMessage("§eРадио было заряжено и теперь оно сново работает!");
        } else {
            $player->sendMessage("§eПодзарядка радио прошло успешно!");
        }
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        FormsRadio::sendMainForm($player, $this);
        return ItemUseResult::SUCCESS();
    }

    protected function serializeCompoundTag(CompoundTag $tag): void {
        parent::serializeCompoundTag($tag);

        $tag->setShort("r_serial", $this->serialNumber);
        $tag->setShort("r_channel", $this->channel);
        $tag->setFloat("r_power", $this->power);
        $tag->setShort("r_distance", $this->distance);
        $tag->setShort("r_max_distance", $this->maxDistance);
        $tag->setShort("r_charge", $this->charge);
        $tag->setShort("r_max_charge", $this->maxCharge);
    }

    protected function deserializeCompoundTag(CompoundTag $tag): void {
        parent::deserializeCompoundTag($tag);

        $this->serialNumber = $tag->getShort("r_serial", 0);
        $this->channel = $tag->getShort("r_channel", 1000);
        $this->power = $tag->getFloat("r_power", 0.5);
        $this->distance = $tag->getShort("r_distance", 1000);
        $this->maxDistance = $tag->getShort("r_max_distance", 1000);
        $this->charge = $tag->getShort("r_charge", 50);
        $this->maxCharge = $tag->getShort("r_max_charge", 50);
    }
}