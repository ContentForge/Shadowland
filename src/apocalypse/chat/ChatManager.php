<?php

namespace apocalypse\chat;

use apocalypse\chat\radio\IRadio;
use apocalypse\immersive\storm\SolarFlareStorm;
use apocalypse\immersive\storm\StormManager;
use apocalypse\item\ItemRadio;
use apocalypse\player\PlayerData;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;

class ChatManager {
    use SingletonTrait;

    public const CHAT_NONE_VISIBLE_DISTANCE = 50;
    public const CHAT_LOW_VISIBLE_DISTANCE = 40;
    public const CHAT_NORMAL_VISIBLE_DISTANCE = 30;
    public const CHAT_GOOD_VISIBLE_DISTANCE = 20;

    private array $noiseChars;

    private int $freeSerialNumber = 0;
    private string $serialStorePath;

    public function init(PluginBase $plugin): void {
        $plugin->getServer()->getPluginManager()->registerEvents(new ChatListener(), $plugin);

        $this->noiseChars = ['@', '#', '$', '^', '*', '~', 'Ω', 'Σ', 'ζ', 'λ', 'ψ', '∀', '∂', 'ℶ'];

        $this->serialStorePath = $plugin->getDataFolder() . ".freeSerialNumber";
        if (!file_exists($this->serialStorePath)) {
            $this->freeSerialNumber = 228;
        } else {
            $this->freeSerialNumber = (int) file_get_contents($this->serialStorePath);
        }
    }

    public function takeFreeSerialId(): int {
        $val = $this->freeSerialNumber++;
        file_put_contents($this->serialStorePath, $this->freeSerialNumber);
        return $val;
    }

    public function hasRadio(Player $player): bool {
        foreach ($player->getInventory()->getContents() as $item) {
            if ($item instanceof ItemRadio) return true;
        }
        return false;
    }

    public function getRadio(Player $player): array {
        $radio = [];
        foreach ($player->getInventory()->getContents() as $item) {
            if (!$item instanceof ItemRadio) continue;

            $radio[] = $item;
        }
        return $radio;
    }

    public function getRadioInHand(Player $player): ?ItemRadio {
        $item = $player->getInventory()->getItemInHand();

        return $item instanceof ItemRadio? $item : null;
    }

    public function receiveLocalMessage(IChatSender $sender, Player $receiver, string $message): void {
        $d = $sender->getPosition()->distance($receiver->getPosition());
        if ($d > self::CHAT_NONE_VISIBLE_DISTANCE ||
            $sender->getPosition()->getWorld() !== $receiver->getPosition()->getWorld()) return;

        if ($d > self::CHAT_LOW_VISIBLE_DISTANCE) {
            $prefixColor = TextFormat::DARK_GRAY;
            $messageColor = TextFormat::BLACK;
        } else if ($d > self::CHAT_NORMAL_VISIBLE_DISTANCE) {
            $prefixColor = TextFormat::GRAY;
            $messageColor = TextFormat::DARK_GRAY;
        } else if ($d > self::CHAT_GOOD_VISIBLE_DISTANCE) {
            $prefixColor = TextFormat::WHITE;
            $messageColor = TextFormat::GRAY;
        } else {
            $prefixColor = TextFormat::WHITE;
            $messageColor = TextFormat::WHITE;
        }

        $receiver->sendMessage("{$prefixColor}{$receiver->getName()} > {$messageColor}{$message}");
    }

    public function sendLocalMessage(IChatSender $sender, string $message): void {
        foreach ($sender->getPosition()->getWorld()->getPlayers() as $player) {
            $this->receiveLocalMessage($sender, $player, $message);
        }
    }

    public function receiveRadioMessage(IChatSender $sender, IRadio $senderRadio, Player $receiver, IRadio $receiverRadio, string $message): void {
        if ($senderRadio->getChannel() !== $receiverRadio->getChannel()) return;

        if ($receiverRadio instanceof ItemRadio) {
            if ($receiverRadio->getCharge() < 1) return;
        }

        $d = $sender->getPosition()->distance($receiver->getPosition());
        $r = $senderRadio->getDistance() * $receiverRadio->getPower();
        if ($d < $r) $noise = 0;
        else {
            if ($r * 1.4 < $d) return;
            $noise = ($d - $r) / ($r * 0.4);
        }

        $msg = $message;
        if ($noise !== 0.0) {
            for ($i = 0; $i < strlen($message); $i++) {
                if (mt_rand(1, 100) > $noise * 100) continue;

                $msg[$i] = $this->noiseChars[array_rand($this->noiseChars)];
            }
        }
        $receiver->sendMessage("§6§l[". ItemRadio::intToHz($senderRadio->getChannel()) ."]§c[". $senderRadio->getSerialNumber() ."]§r§7 {$msg}");

        $pk = new PlaySoundPacket();
        $pk->pitch = 1 - $noise;
        $pk->volume = 1;
        $pk->soundName = "apocalypse.radio.message";
        $pos = $receiver->getPosition();
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $receiver->getNetworkSession()->sendDataPacket($pk);
    }

    public function sendRadioMessage(IChatSender $sender, IRadio $senderRadio, string $message): void {
        foreach ($sender->getPosition()->getWorld()->getPlayers() as $player) {
            foreach ($this->getRadio($player) as $radio) {
                $this->receiveRadioMessage($sender, $senderRadio, $player, $radio, $message);
            }
        }
    }

    public function trySendRadioMessage(PlayerData $playerData, ItemRadio $radio, string $message) {
        if ($radio->getCharge() < 1) {
            $playerData->getPlayer()->sendMessage("§cРадио разряжено. Зарядите его чтобы использовать.");
            return;
        }

        if (StormManager::getInstance()->storm instanceof SolarFlareStorm) {
            $playerData->getPlayer()->sendMessage("§cОтказ оборудования: идет Геомагнитный шторм.");
            return;
        }

        $this->sendRadioMessage($playerData, $radio, $message);
        $radio->updateCharge(-1);
        $radio->updateItem($playerData->getPlayer());

        if ($radio->getCharge() > 0) return;

        $playerData->getPlayer()->sendMessage("§cРадио разрядилось.");

        $pk = new PlaySoundPacket();
        $pk->pitch = 1;
        $pk->volume = 1;
        $pk->soundName = "apocalypse.radio.lost";
        $pos = $playerData->getPlayer()->getPosition();
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $playerData->getPlayer()->getNetworkSession()->sendDataPacket($pk);
    }
}