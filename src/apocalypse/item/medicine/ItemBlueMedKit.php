<?php

namespace apocalypse\item\medicine;

use apocalypse\player\PlayerManager;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;

class ItemBlueMedKit extends MedKit {

    public function __construct(ItemIdentifier $identifier, string $name = "BlueMedKit") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§2+ Восстановление здоровья",
            "§r§2+ Вывод радионуклидов (-1Р)",
            "§r§4- Слабость",
            "§r§4- Медлительность",
            "§r§4- Утомление",
            "",
            "§r§7Производство НИИ Пророк"
        ]);
    }

    public function getRuName(): string {
        return "Синяя аптечка";
    }

    public function getTexturePath(): string {
        return "textures/items/medicine/blue_medkit";
    }

    public function getFullId(): string {
        return "apocalypse:blue_medkit";
    }

    public function onUse(Player $player): void {
        $effects = [];

        $effects[] = new EffectInstance(VanillaEffects::REGENERATION(), 20 * 20);
        PlayerManager::getInstance()->getPlayer($player)->updateRadLevel(-1000);

        foreach ($effects as $effect) $player->getEffects()->add($effect);
    }
}