<?php

namespace AreaProtect;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		@mkdir($this->getDataFolder());
        $this->worlds = (new Config($this->getDataFolder()."config.yml", Config::YAML, array(
            "Worlds" => array(
                "world"
            ))))->getAll();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("AreaProtect Loaded!");
	}
    
	/**
	 * @param BlockBreakEvent $event
	 *
	 * @priority       NORMAL
	 * @ignoreCanceled false
	 */
	public function onBlockBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		foreach($this->worlds["Worlds"] as $world){
			if($player->hasPermission("areaprotect.action.edit")){
				return true;
			}elseif($player->level->getLevel() == $world){
				$player->sendMessage("[AreaProtect] You do not have permission to do that here!");
				$event->setCancelled();
			}
		}
	}
	
	/**
	 * @param BlockPlaceEvent $event
	 *
	 * @priority       NORMAL
	 * @ignoreCanceled false
	 */
	public function onBlockPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		foreach($this->worlds["Worlds"] as $world){
			if($player->hasPermission("areaprotect.action.edit")){
				return true;
			}elseif($player->level->getLevel() == $world){
				$player->sendMessage("[AreaProtect] You do not have permission to do that here!");
				$event->setCancelled();
			}
		}
	}
	
	/**
	 * @param BlockBreakEvent $event
	 *
	 * @priority       NORMAL
	 * @ignoreCanceled false
	 */
	public function onPlayerInteract(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		foreach($this->worlds["Worlds"] as $world){
			if($player->hasPermission("areaprotect.action.edit")){
				return true;
			}elseif($player->level->getLevel() == $world){
				$player->sendMessage("[AreaProtect] You do not have permission to do that here!");
				$event->setCancelled();
			}
		}
	}
}
?>
