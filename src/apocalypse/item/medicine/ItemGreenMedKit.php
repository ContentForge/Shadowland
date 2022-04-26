<?php

namespace apocalypse\item\medicine;

use apocalypse\player\PlayerManager;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
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
        $effects = [];

        $player->setHealth($player->getMaxHealth());
        $effects[] = new EffectInstance(VanillaEffects::BLINDNESS(), 20 * 7);
        $effects[] = new EffectInstance(VanillaEffects::SLOWNESS(), 20 * 20);
        $effects[] = new EffectInstance(VanillaEffects::WEAKNESS(), 20 * 60);
        PlayerManager::getInstance()->getPlayer($player)->updateRadLevel(-2000);

        foreach ($effects as $effect) $player->getEffects()->add($effect);
    }
}