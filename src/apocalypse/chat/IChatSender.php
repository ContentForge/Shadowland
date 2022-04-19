<?php

namespace apocalypse\chat;

use pocketmine\world\Position;

interface IChatSender {

    public function getName(): string;

    public function getPosition(): Position;
}