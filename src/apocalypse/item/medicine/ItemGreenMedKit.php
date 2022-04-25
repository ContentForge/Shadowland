<?php

namespace apocalypse\item\medicine;

use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;

class ItemGreenMedKit extends MedKit {

    public function __construct(ItemIdentifier $identifier, string $name = "GreenMedKit") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§2+ Мгновенное восстановление здоровья",
            "§r§2+ Вывод радионуклидов (-2Р)",
            "§r§4- Кратковременная слепота",
            "§r§4- Медлительность",
            "§r§4- Слабость",
            "",
            "§r§7Производство НИИ Пророк"
        ]);
    }

    public function getRuName(): string {
        return "Зеленая аптечка";
    }

    public function getTexturePath(): string {
        return "textures/items/medicine/green_medkit";
    }

    public function getFullId(): string {
        return "apocalypse:green_medkit";
    }

    public function onUse(Player $player): void {

    }
}