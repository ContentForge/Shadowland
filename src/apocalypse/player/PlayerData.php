<?php

namespace apocalypse\player;

use apocalypse\chat\IChatSender;
use pocketmine\player\Player;
use pocketmine\world\Position;

class PlayerData implements IChatSender {

    private string $saveFilePath;

    private int $radLevel = 0;

    public function __construct(private Player $player, private PlayerManager $manager) {
        $this->saveFilePath = $this->manager->getSaveFilePath($this);
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getName(): string {
        return $this->player->getName();
    }

    public function getPosition(): Position {
        return $this->player->getPosition();
    }

    public function save(): void {
        $data = [
            "rad" => $this->radLevel,
        ];

        file_put_contents($this->saveFilePath, json_encode($data));
    }

    public function load(): void {
        if (file_exists($this->saveFilePath)) {
            $data = json_decode(file_get_contents($this->saveFilePath), true);
        } else $data = [];

        $this->radLevel = $data["rad"] ?? 0;
    }

    public function updateRadLevel(int $value): void {
        $this->radLevel = max(0, $this->radLevel + $value);
    }

    public function getRadLevel(): int {
        return $this->radLevel;
    }

    public function onDeath(): void {
        $this->radLevel = 0;
    }
}