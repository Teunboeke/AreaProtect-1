<?php

namespace AreaProtect;

use pocketmine\plugin\PluginBase;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;

use AreaProtect\provider\MySQL;

class Main extends PluginBase implements Listener, CommandExecutor{
    public function onEnable(){
    	$this->saveDefaultConfig();
        $this->getResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->MySQL = new MySQL($this);
        $this->getLogger()->info("AreaProtect Loaded!");
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        switch($cmd->getName()){
            case "areaprotect":
                if($args[0] == "pos1"){
                    $x1 = $sender->getX();
                    $y1 = $sender->getY();
                    $z1 = $sender->getZ();
                    $sender->sendMessage("[AreaProtect] Position 1 set!");
                    return true;
                }elseif($args[0] == "pos2"){
                    $x2 = $sender->getX();
                    $y2 = $sender->getY();
                    $z2 = $sender->getZ();
                    $sender->sendMessage("[AreaProtect] Position 2 set!");
                    return true;
                }elseif($args[0] == "protect"){
                    if(!isset($args[1])){
                        $sender->sendMessage("[AreaProtect] You must specify an area name!");
                        return true;
                    }elseif($this->database->query("SELECT * FROM areaprotect_areas WHERE name=" . $args[1])){
                        $sender->sendMessage("[AreaProtect] An area with that name already exists!");
                        return true;
                    }else{
                    	if(isset($x1) and isset($z2)){
		            $owner = $sender->getName();
		            $name = $args[1];
		            if($this->getConfig()->get("pvp") === true){
		                $pvp = "1";
		            }else{
		                $pvp = "0";
		            }
		            if($this->getConfig()->get("build") === true){
		                $build = "1";
		            }else{
		                $build = "0";
		            }
		            if($this->getConfig()->get("destroy") === true){
		                $destroy = "1";
		            }else{
		                $destroy = "0";
		            }
		            $this->MySQL->createArea($owner, $name, $x1, $y1, $z1, $x2, $y2, $z2, $pvp, $build, $destroy);
		            $sender->sendMessage("[AreaProtect] Your area has been created!");
		            return true;
                    	}elseif(isset($z2)){
                    	    $sender->sendMessage("[AreaProtect] Position 1 not set!");
                    	    return true;
                    	}elseif(isset($x1)){
                    	    $sender->sendMessage("[AreaProtect] Position 2 not set!");
                    	    return true;
                    	}else{
                    	    $sender->sendMessage("[AreaProtect] Position 1 and 2 not set!");
                    	    return true;
                    	}
                    }
                }elseif($args[0] == "delete"){
                    if(!isset($args[1])){
                        $sender->sendMessage("[AreaProtect] You must specify an area name!");
                        return true;
                    }else{
			$area = $args[1];
			if($this->MySQL->checkExists($area) === false){
			    $sender->sendMessage("[AreaProtect] Unable to find the area" . $area . "!");
			    return true;
			}else{
			    $player = $sender->getName();
			    $area = $args[1];
			    $owner = $this->MySQL->checkOwner($area);
			    if($owner == $player){
			        $area = $args[1];
				$this->MySQL->deleteArea($area);
				$sender->sendMessage("[AreaProtect] Your area has been deleted!");
				return true;
			    }else{
				$sender->sendMessage("[AreaProtect] You do not own the area " . $area . "!");
				return true;
			    }
		        }
		    }
                }elseif($args[0] == "flag"){
                    if($args[1] == "pvp"){
                        if($args[2] == "enable"){
                            if(!isset($args[3])){
				$sender->sendMessage("[AreaProtect] You must specify an area name!");
				return true;
                    	    }else{
				$area = $args[3];
				if($this->MySQL->checkExists($area) === false){
				    $sender->sendMessage("[AreaProtect] Unable to find the area" . $area . "!");
				    return true;
				}else{
				    $player = $sender->getName();
				    $area = $args[3];
				    $owner = $this->MySQL->checkOwner($area);
				    if($owner == $player){
					$pvp = $this->MySQL->checkPVP($area);
					if($pvp === true){
					    $sender->sendMessage("[AreaProtect] PvP is already enabled in " . $area . "!");
					    return true;
					}else{
					    $this->MySQL->enablePVP($area);
					    $sender->sendMessage("[AreaProtect] PvP is now enabled in " . $area . "!");
					    return true;
					}
				    }else{
					$sender->sendMessage("[AreaProtect] You do not own the area " . $area . "!");
					return true;
				    }
				}
			    }
                        }elseif($args[2] == "disable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                        	return true;
                    	    }else{
				if($this->MySQL->checkExists($args[3]) === false){
				    $sender->sendMessage("[AreaProtect] Unable to find the area" . $area . "!");
				    return true;
				}else{
				    $player = $sender->getName();
				    $area = $args[3];
				    $owner = $this->MySQL->checkOwner($area);
				    if($owner == $player){
				        $pvp = $this->MySQL->checkPVP($area);
					if($pvp === false){
					    $sender->sendMessage("[AreaProtect] PvP is already disabled in " . $args[3] . "!");
					    return true;
					}else{
					    $this->MySQL->disablePVP($area);
					    $sender->sendMessage("[AreaProtect] PvP is now disabled in " . $args[3] . "!");
					    return true;
					}
				    }else{
					$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
					return true;
				    }
				}
			    }
                        }else{
                            $sender->sendMessage("Usage: /areaprotect flag pvp <enable|disable> [name]");
                            return true;
                        }
                    }elseif($args[1] == "build"){
                        if($args[2] == "enable"){
                            if(!isset($args[3])){
				$sender->sendMessage("[AreaProtect] You must specify an area name!");
				return true;
                    	    }else{
				if($this->MySQL->checkExists($args[3]) === false){
				    $sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
				    return true;
				}else{
				    $player = $sender->getName();
				    $area = $args[3];
				    $owner = $this->MySQL->checkOwner($area);
				    if($owner == $sender->getName()){
					$build = $this->MySQL->checkBuild($area);
				    if($build === true){
					$sender->sendMessage("[AreaProtect] Public building is already enabled in " . $args[3] . "!");
					return true;
				    }else{
					$this->MySQL->enableBuild($area);
					$sender->sendMessage("[AreaProtect] Public building is now enabled in " . $args[3] . "!");
				        return true;
				    }
				    }else{
				        $sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
				        return true;
				    }
			        }
			    }
                        }elseif($args[2] == "disable"){
                            if(!isset($args[3])){
				$sender->sendMessage("[AreaProtect] You must specify an area name!");
				return true;
                    	    }else{
				if($this->MySQL->checkExists($args[3]) === false){
				    $sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
				    return true;
				}else{
				    $player = $sender->getName();
				    $area = $args[3];
				    $owner = $this->MySQL->checkOwner($area);
				    if($owner == $player){
					$build = $this->MySQL->checkBuild($area);
					if($build === false){
					    $sender->sendMessage("[AreaProtect] Public building is already disabled in " . $args[3] . "!");
					    return true;
					}else{
					    $this->MySQL->disableBuild($area);
					    $sender->sendMessage("[AreaProtect] Public building is now disabled in " . $args[3] . "!");
					    return true;
					}
				    }else{
				        $sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
				        return true;
				    }
				}
			    }
                        }else{
                            $sender->sendMessage("Usage: /areaprotect flag build <enable|disable> [name]");
                            return true;
                        }
                    }elseif($args[1] == "destroy"){
                        if($args[2] == "enable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                        	return true;
                    	    }else{
				if($this->MySQL->checkExists($args[3]) === false){
				    $sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
				    return true;
				}else{
				    $player = $semder->getName();
				    $area = $args[3];
				    $owner = $this->MySQL->checkOwner($area);
				    if($owner == $player){
					$destroy = $this->MySQL->checkDestroy($area);
					if($destroy === true){
					    $sender->sendMessage("[AreaProtect] Public destruction is already enabled in " . $args[3] . "!");
					    return true;
					}else{
					    $this->MySQL->enableDestroy($area);
					    $sender->sendMessage("[AreaProtect] Public destruction is now enabled in " . $args[3] . "!");
					    return true;
					}
				    }else{
					$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
					return true;
				    }
				}
                    	    }
                        }elseif($args[2] == "disable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                        	return true;
                    	    }else{
				if($this->MySQL->checkExists($args[3]) === false){
				    $sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
				    return true;
				}else{
				    $player = $sender->getName();
				    $area = $args[3];
				    $owner = $this->MySQL->checkOwner($area);
				    if($owner == $sender->getName()){
					$destroy = $this->MySQL->checkDestroy($area);
					if($destroy === false){
					    $sender->sendMessage("[AreaProtect] Public destruction is already disabled in " . $args[3] . "!");
					}else{
					    $this->MySQL->disableDestroy($area);
					    $sender->sendMessage("[AreaProtect] Public destruction is now disabled in " . $args[3] . "!");
					    return true;
					}
				    }else{
					$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
					return true;
				    }
				}
                    	    }
                        }else{
                            $sender->sendMessage("Usage: /areaprotect flag destroy <enable|disable> [name]");
                            return true;
                        }
                    }else{
                        $sender->sendMessage("Usage: /areaprotect flag <pvp|build|destroy>");
                        return true;
                    }
                }else{
                    $sender->sendMessage("Usage: /areaprotect <pos1|pos2|protect|flag|delete> [name]");
                    return true;
                }
            break;
        }
    }
    
    /**
     * @param PlayerAttackEvent $event
     *
     * @priority       NORMAL
     * @ignoreCanceled false
     */
    public function onAttack(){
    	//Player vs Player actions are not yet implemented on PocketMine-MP Alpha_1.4.0
    }
    
    /**
     * @param BlockPlaceEvent $event
     *
     * @priority       NORMAL
     * @ignoreCanceled false
     */
    public function onBuild(BlockPlaceEvent $event){
    	$player = $event->getPlayer();
    	foreach($this->MySQL->getAllBuild() as $area){
    	    $owner = $this->MySQL->checkOwner($area);
    	    $this->MySQL->getPositions($area);
    	    if($player->getX() >= $x1 and $player->getX() <= $x2 and $player->getY() >= $y1 and $player->getY() <= $y2 and $player->getZ() >= $z1 and $player->getZ() <= $z2 and $owner != $player->getName()){
    	        $player->sendMessage("[AreaProtect] You do not have permission to do that here!");
    	        $event->setCancelled(true);
    	    }
    	}
    }
    
    /**
     * @param BlockBreakEvent $event
     *
     * @priority       NORMAL
     * @ignoreCanceled false
     */
    public function onDestroy(BlockBreakEvent $event){
    	$player = $event->getPlayer();
    	foreach($this->MySQL->getAllDestroy() as $area){
    	    $owner = $this->MySQL->checkOwner($area);
    	    $this->MySQL->getPositions($area);
    	    if($player->getX() >= $x1 and $player->getX() <= $x2 and $player->getY() >= $y1 and $player->getY() <= $y2 and $player->getZ() >= $z1 and $player->getZ() <= $z2 and $owner != $player->getName()){
    	    	$player->sendMessage("[AreaProtect] You do not have permission to do that here!");
    	    	$event->setCancelled(true);
    	    }
    	}
    }
    
    /**
     * @param PlayerInteractEvent $event
     *
     * @priority       NORMAL
     * @ignoreCanceled false
     */
    public function onInteract(PlayerInteractEvent $event){
    	$player = $event->getPlayer();
    	foreach($this->MySQL->getAllInteract() as $area){
    	    $owner = $this->MySQL->checkOwner($area);
    	    $this->MySQL->getPositions($area);
    	    if($player->getX() >= $x1 and $player->getX() <= $x2 and $player->getY() >= $y1 and $player->getY() <= $y2 and $player->getZ() >= $z1 and $player->getZ() <= $z2 and $owner != $player->getName()){
    	    	$player->sendMessage("[AreaProtect] You do not have permission to do that here!");
    	    	$event->setCancelled(true);
    	    }
    	}
    }
    
    public function onDisable(){
    	$this->MySQL->close();
        $this->getLogger()->info("AreaProtect Unloaded!");
    }
}

?>
