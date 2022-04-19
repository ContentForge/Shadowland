<?php

namespace apocalypse\util\item\battery;

use apocalypse\item\battery\Battery;
use form\CustomForm;
use pocketmine\player\Player;

class BatteryTransaction extends BatterySelection {

    private \Closure $func;

    public function __construct(Player $player, string $text, \Closure $callback) {
        $this->func = $callback;

        parent::__construct($player, $text, function(Player $player, int $slot, Battery $battery) {
            if ($battery->isEmpty()) {
                $player->sendMessage("§cВыбранная батарея разряжена.");
                return;
            }

            $this->sendTransactionForm($player, $slot, $battery);
        });
    }

    protected function sendTransactionForm(Player $player, int $slot, Battery $battery): void {
        $form = new CustomForm(function(Player $player, array $data) use ($slot, $battery) {
            $value = (int) (trim($data['value']));

            if ($value < 1 || $value > $battery->getCharge()) {
                $player->sendMessage("§cВведено неверное значение.");
                return;
            }

            $battery->updateCharge(-1 * $value);
            $battery->updateItem($player, $slot);

            ($this->func)($player, $value);
        });

        $form->setTitle("Выбор батарейки");
        $form->addLabel(
            "Выберите кол-во энергии, которое хотите использовать.\n".
            "Заряд батареи: §3§l". $battery->getCharge() ."EU§r"
        );
        $form->addInput("Кол-во энергии", "§8". $battery->getCharge(), $battery->getCharge(), key: "value");

        $form->sendToPlayer($player);
    }
}