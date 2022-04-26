<?php

namespace apocalypse\item\medicine;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
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
        $effects = [];

        $effects[] = new EffectInstance(VanillaEffects::REGENERATION(), 20 * 20);
        $effects[] = new EffectInstance(VanillaEffects::NAUSEA(), 20 * 10);
        $effects[] = new EffectInstance(VanillaEffects::WEAKNESS(), 20 * 60);
        $effects[] = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * 30);

        foreach ($effects as $effect) $player->getEffects()->add($effect);
    }
}