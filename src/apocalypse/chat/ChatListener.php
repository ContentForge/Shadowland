<?php

namespace apocalypse\chat;

use apocalypse\player\PlayerManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class ChatListener implements Listener {

    /**
     * @priority HIGH
     */
    public function onChat(PlayerChatEvent $event) {
        $event->cancel();

        $player = $event->getPlayer();
        $playerData = PlayerManager::getInstance()->getPlayer($player);
        $chatManager = ChatManager::getInstance();
        $message = preg_replace("/§./", "", $event->getMessage());
        $radio = $chatManager->getRadioInHand($player);

        if ($radio === null) $chatManager->sendLocalMessage($playerData, $message);
        else $chatManager->trySendRadioMessage($playerData, $radio, $message);
    }
}