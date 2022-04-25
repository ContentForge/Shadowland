<?php

namespace apocalypse\item\medicine;

use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;

class ItemOrangeMedKit extends MedKit {

    public function __construct(ItemIdentifier $identifier, string $name = "OrangeMedKit") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§2+ Восстановление здоровья",
            "§r§4- Тошнота",
            "§r§4- Слабость",
            "§r§4- Утомление",
            "",
            "§r§7Производство НИИ Пророк"
        ]);
    }

    public function getRuName(): string {
        return "Оранжевая аптечка";
    }

    public function getTexturePath(): string {
        return "textures/items/medicine/orange_medkit";
    }

    public function getFullId(): string {
        return "apocalypse:orange_medkit";
    }

    public function onUse(Player $player): void {

    }
}