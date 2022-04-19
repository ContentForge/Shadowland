<?php

namespace apocalypse\util\item\battery;

use apocalypse\item\battery\Battery;
use form\SimpleForm;
use pocketmine\player\Player;

class BatterySelection {

    private Player $player;
    private string $text;
    private \Closure $callback;

    public function __construct(Player $player, string $text, \Closure $callback) {
        $this->player = $player;
        $this->text = $text;
        $this->callback = $callback;
    }

    public function sendForm(): void {
        $batteries = [];
        foreach ($this->player->getInventory()->getContents() as $slot => $item) {
            if ($item instanceof Battery) $batteries[$slot] = $item;
        }

        if (empty($batteries)) {
            $this->player->sendMessage("§cУ вас нет батареек в инвентаре.");
            return;
        }

        $form = new SimpleForm();
        $form->setTitle("Выбор батарейки");
        $form->setContent($this->text);

        foreach ($batteries as $slot => $battery) {
            $form->addButton("§l{$battery->getRuName()}§r\n". ($battery->isEmpty()? "§4" : "") . $battery->formatCharge(), SimpleForm::IMAGE_TYPE_PATH, $battery->getTexturePath(), function(Player $player) use ($slot, $battery) {
                ($this->callback)($player, $slot, $battery);
            });
        }

        $form->sendToPlayer($this->player);
    }
}