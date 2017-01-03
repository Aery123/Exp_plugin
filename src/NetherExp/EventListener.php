<?php

namespace NetherExp;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\ItemBlock;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use ALLVIP\main;
use onebone\economyapi\EconomyAPI;

class EventListener implements Listener {
    protected $plugin;

    private $config;

    public function onPlayerLogin(PlayerLoginEvent $event) {
        $cc = $this->plugin->getPlayerConfigCache($event->getPlayer()->getName());
        $event->getPlayer()->setNameTag(TextFormat::GREEN . "LV." . $cc->level . "  " . $this->plugin->getKnightString($cc->level) . "  " . TextFormat::WHITE . $event->getPlayer()->getName());
    }

    public function onPlayerJoin(PlayerJoinEvent $event) {
        $event->setJoinMessage(null);
        $cc = $this->plugin->getPlayerConfigCache($event->getPlayer()->getName());
        $this->plugin->getServer()->broadcastMessage(TextFormat::YELLOW . "Player: " . $this->plugin->getKnightString($cc->level) . TextFormat::YELLOW . " " . $event->getPlayer()->getName() . " join the game");
        $event->getPlayer()->setNameTag(TextFormat::GREEN . "LV." . $cc->level .
            "  " . $this->plugin->getKnightString($cc->level) . "  " . TextFormat::WHITE . $event->getPlayer()->getName());
        $player = $event->getPlayer();
        $cc = $this->plugin->getPlayerConfigCache($player->getName());
        $player->setMaxHealth($cc->max_health);
        $player->setHealth($cc->max_health);
    }

    public function onKill(EntityDamageEvent $event) {
        if ($event->isCancelled()) {
            return;
        }
        if ($event instanceof EntityDamageByEntityEvent) {
            $killer = $event->getDamager();
            $bkiller = $event->getEntity();
            if ($killer instanceof Player and $bkiller instanceof Player) {
                if ($bkiller->getHealth() - $event->getDamage() <= 0) {
                    $this->plugin->addExp($killer->getName(), 50);
                    $kcc = $this->plugin->getPlayerConfigCache($killer->getName());
                    $bkcc = $this->plugin->getPlayerConfigCache($bkiller->getName());
                    if ($kcc->level < $bkcc->level) {
                        $this->plugin->getServer()->broadcastMessage(TextFormat::YELLOW . "Player！" . TextFormat::GREEN . "LV." . $kcc->level . " " . $killer->getName() . " Kill LV." . $bkcc->level . " " . $bkiller->getName() . " get extra Exp");
                        $this->plugin->addExp($killer->getName(), 50);
                    }
                }
            }else{
            if ($killer instanceof Player and $bkiller instanceof Entity) {
                if ($bkiller->getHealth() - $event->getDamage() <= 0) {
                    $this->plugin->addExp($killer->getName(), 50);
                    )
                )
            )
        }
    }

    public function onChat(PlayerChatEvent $event) {
        $event->setCancelled();
        $cc = $this->plugin->getPlayerConfigCache($event->getPlayer()->getName());
        $msg = $event->getMessage();

		$player = $event->getPlayer();
		$qvanxian = TextFormat::GREEN . "[Player]";
		$clor = TextFormat::WHITE;
		$z = ":";
			if ($player->isOp()) {
                    $qvanxian = TextFormat::YELLOW . "[OP]";
					$clor = TextFormat::GREEN;
					$z = "§l";
				$level = $player->getLevel();
        $this->plugin->getServer()->broadcastMessage(TextFormat::GOLD . "§e[Server] "
            . TextFormat::GREEN . "[LV." . $cc->level . " " . $this->plugin->getKnightString($cc->level) . TextFormat::GREEN . "]" . " $qvanxian "
            . TextFormat::WHITE . "<" . $event->getPlayer()->getName() . "> " . $clor . $z .$msg);
    }

    public function onDead(PlayerDeathEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $cc = $this->plugin->getPlayerConfigCache($player->getName());
            $cc->max_health = $player->getMaxHealth();
        }
    }

    public function onRespawn(PlayerRespawnEvent $event) {
        $player = $event->getPlayer();
        $cc = $this->plugin->getPlayerConfigCache($player->getName());
        $player->setMaxHealth($cc->max_health);
        $player->setHealth($cc->max_health);
    }

}
