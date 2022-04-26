<?php

namespace apocalypse\item\medicine;

use apocalypse\player\PlayerManager;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;

class ItemYellowMedKit extends MedKit {

    public function __construct(ItemIdentifier $identifier, string $name = "YellowMedKit") {
        parent::__construct($identifier, $name);

        $this->setLore([
            "§r§2+ Мгновенное восстановление здоровья",
            "§r§2+ Вывод радионуклидов (-50Р)",
            "§r§4- Слабость",
            "§r§4- Утомление",
            "§r§4- Кратковременная слепота",
            "",
            "§r§7Производство НИИ Пророк"
        ]);
    }

    public function getRuName(): string {
        return "Желтая аптечка";
    }

    public function getTexturePath(): string {
        return "textures/items/medicine/yellow_medkit";
    }

    public function getFullId(): string {
        return "apocalypse:yellow_medkit";
    }

    public function onUse(Player $player): void {
        $effects = [];

        $effects[] = new EffectInstance(VanillaEffects::INSTANT_HEALTH(), 1, 1);
        $effects[] = new EffectInstance(VanillaEffects::WEAKNESS(), 20 * 60 * 5);
        $effects[] = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * 20);
        $effects[] = new EffectInstance(VanillaEffects::BLINDNESS(), 20 * 5);


        PlayerManager::getInstance()->getPlayer($player)->updateRadLevel(-50000);

        foreach ($effects as $effect) $player->getEffects()->add($effect);
    }
}