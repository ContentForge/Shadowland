<?php

namespace apocalypse\util\item;

use expo\item\ItemManager;
use form\SimpleForm;
use pocketmine\item\Item;
use pocketmine\player\Player;

class ItemTransaction {

    private Player $player;
    private string $title;
    private string $text;
    private array $items;
    private \Closure $callback;

    public function __construct(Player $player, string $title, string $text, array $items, \Closure $callback) {
        $this->player = $player;
        $this->title = $title;
        $this->text = $text;
        $this->items = $items;
        $this->callback = $callback;
    }

    public function sendForm(): void {
        $canCraft = true;
        $need = "§rНеобходиме компоненты: ";
        $inv = $this->player->getInventory();
        foreach ($this->items as $item) {
            /** @var Item $item */
            $contain = $inv->contains($item);

            if (!$contain) $canCraft = false;

            $need .= "\n§f- §". ($contain? "2" : "4") . ItemManager::getInstance()->getItemDictionaryData($item)->getRuName() ."(". $item->getCount() ."шт)§f";
        }

        $form = new SimpleForm();
        $form->setTitle($this->title);
        $form->setContent("{$this->text}\n\n{$need}");

        if ($canCraft) {
            $form->addButton("Применить", SimpleForm::IMAGE_TYPE_PATH, "textures/ui/confirm", function(Player $player) {
                $inv = $player->getInventory();
                foreach ($this->items as $item) {
                    $inv->removeItem($item);
                }

                ($this->callback)($player);
            });
        } else {
            $form->addButton("§lПрименить§r\n§4Недстаточно компонентов", SimpleForm::IMAGE_TYPE_PATH, "textures/ui/lock_color", function (Player $player) {
                $player->sendMessage("§cНедостаточно компонентов.");
            });
        }

        $form->sendToPlayer($this->player);
    }
}