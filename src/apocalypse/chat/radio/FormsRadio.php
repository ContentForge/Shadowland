<?php

namespace apocalypse\chat\radio;

use apocalypse\item\ItemRadio;
use apocalypse\util\item\battery\BatteryTransaction;
use form\CustomForm;
use form\SimpleForm;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class FormsRadio {

    private function __construct() {

    }

    public static function sendMainForm(Player $player, ItemRadio $radio): void {
        $form = new SimpleForm();
        $form->setTitle("Радиостанция");
        $form->setContent(
            "Серийный номер:§g§l ". $radio->getSerialNumber() ."§r\n".
            "Канал:§2§l ". ItemRadio::intToHz($radio->getChannel()) ."§r\n".
            "Дальность передачи:§2§l ". ItemRadio::metersToKm($radio->getDistance()) ."§f/§2". ItemRadio::metersToKm($radio->getMaxDistance()) ."§r\n".
            "Мощность приема:§e§l ". ((int) ($radio->getPower() * 100)) ." процентов§r\n".
            "Заряд батареи:§2§l ". $radio->getCharge() ."EU§f/§2". $radio->getMaxCharge() ."EU§r\n"
        );

        $form->addButton("Переключиться на другой канал", SimpleForm::IMAGE_TYPE_PATH, "", function(Player $player) use ($radio) {
            self::changeChannel($player, $radio);
        });

        if ($radio->getCharge() < $radio->getMaxCharge()) {
            $form->addButton("Зарядить радио", SimpleForm::IMAGE_TYPE_PATH, "", function(Player $player) use ($radio) {
                self::fuelRadio($player, $radio);
            });
        }

        $form->addButton("Улучшить батарею", SimpleForm::IMAGE_TYPE_PATH, "", function(Player $player) use ($radio) {

        });

        $form->addButton("Увеличить дальность передачи сигнала", SimpleForm::IMAGE_TYPE_PATH, "", function(Player $player) use ($radio) {

        });

        $form->addButton("Усилить мощность приема", SimpleForm::IMAGE_TYPE_PATH, "", function(Player $player) use ($radio) {

        });

        $form->sendToPlayer($player);
    }

    public static function changeChannel(Player $player, ItemRadio $radio): void {
        $form = new CustomForm(function(Player $player, array $data) use ($radio) {
            $value = (int) trim($data['value']);

            if ($value < 500 || $value > 2000) {
                $player->sendMessage("§cВведено неверное значение.");
                return;
            }

            $radio->setChannel($value);
            $radio->updateItem($player);

            if ($radio->getCharge() < 1) return;

            $pk = new PlaySoundPacket();
            $pos = $player->getPosition();
            $pk->x = $pos->x;
            $pk->y = $pos->y;
            $pk->z = $pos->z;
            $pk->pitch = 1;
            $pk->volume = 1;
            $pk->soundName = "apocalypse.radio.found";
            $player->getNetworkSession()->sendDataPacket($pk);
        });

        $form->setTitle("Выбор канала");
        $form->addLabel(
            "Выберите волну на которую вы хотите переключить данную радиостанцию. \n\n".
            "Диапазон значений: §l§g500§f(§250Мгц§f)-§g2000§f(§2200Мгц§f)§r\n".
            "Текущая частота: §2§l". ItemRadio::intToHz($radio->getChannel()) ."§r(§g{$radio->getChannel()}§f)§r\n\n".
            "Список информационных каналов:\n".
            "- §lГринмонд-1 §r-§2 §l100.0МГц§f(§g1000§f)§r\n".
            "- §lПоследний День §r-§2 §l102.4МГц§f(§g1024§f)§r\n"
        );
        $form->addInput("Канал", "§8". $radio->getChannel(), $radio->getChannel(), key: "value");
        $form->addLabel("Подсказка: \n§o§7100МГц=1000, 100.1МГц=1001, 99.9МГц=999");

        $form->sendToPlayer($player);
    }

    public static function fuelRadio(Player $player, ItemRadio $radio): void {
        $transaction = new BatteryTransaction($player, "Выберите батарейку, котой вы хотите зарядить радио", function (Player $player, int $energy) use ($radio) {
            $radio->fuelRadio($player, $energy);
            $radio->updateItem($player);
        });

        $transaction->sendForm();
    }
}