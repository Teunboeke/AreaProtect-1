<?php

namespace AreaProtect;

use pocketmine\plugin\PluginBase;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;

class Main extends PluginBase implements Listener, CommandExecutor{
    public function onEnable(){
    	$this->saveDefaultConfig();
        $this->getResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Connecting to MySQL Database...");
        //TODO Ping Database
        if($this->database->connect_error){
            $this->getLogger()->critical("Couldn't connect to Database: " . $this->database->connect_error);
        }else{
            $sql = $this->getResource("mysql.sql");
            $this->database->query(stream_get_contents($sql));
            //TODO Schedule repeating MySQL ping
            $this->getLogger()->info("Connected To Database Successfully!");
        }
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
                }elseif($args[0] == "pos2"){
                    $x2 = $sender->getX();
                    $y2 = $sender->getY();
                    $z2 = $sender->getZ();
                    $sender->sendMessage("[AreaProtect] Position 2 set!");
                }elseif($args[0] == "protect"){
                    if($args[1] == null){
                        $sender->sendMessage("[AreaProtect] You must specify an area name!");
                    }elseif($this->database->query("SELECT * FROM areaprotect_areas WHERE name=" . $args[1])){
                        $sender->sendMessage("[AreaProtect] An area with that name already exists!");
                    }else{
                    	if(isset($x1) and isset($z2))
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
		        	$this->database->query("INSERT INTO areaprotect_areas (owner, name, x1, y1, z1, x2, y2, z2, pvp, build, destroy) VALUES ('" . $owner . "', '" . $name ."', " . intval($x1) . ", " . intval($y1) . ", " . intval($z1) . ", " . intval($x2) . ", " . intval($y2) . ", " . intval($z2) . ", " . $pvp . ", " . $build . ", " . $destroy . ")");
		        	$sender->sendMessage("[AreaProtect] Your area has been created!");
                    	}elseif(isset($z2)){
                    		$sender->sendMessage("[AreaProtect] Position 1 not set!");
                    	}elseif(isset($x1)){
                    		$sender->sendMessage("[AreaProtect] Position 2 not set!");
                    	}else{
                    		$sender->sendMessage("[AreaProtect] Position 1 and 2 not set!");
                    	}
                    }
                }elseif($args[0] == "delete"){
                    if(!isset($args[1])){
                        $sender->sendMessage("[AreaProtect] You must specify an area name!");
                    }elseif($this->database->query("SELECT FROM areaprotect_areas WHERE name=" . $args[1]) === null){
                        $sender->sendMessage("[AreaProtect] Unable to find the area" . $args[1] . "!");
                    }else{
                    	$owner = $this->database->query("SELECT " . $args[1] . " FROM areaprotect_areas WHERE owner=" . $sender->getName());
                    	if($owner === true){
                    		$this->database->query("DELETE FROM areaprotect_areas WHERE name=" $args[1]);
                        	$sender->sendMessage("[AreaProtect] Your area has been deleted!");
                    	}else{
                    		$sender->sendMessage("[AreaProtect] You do not own the area " . $args[1] . "!");
                    	}
                    }
                }elseif($args[0] == "flag"){
                    if($args[1] == "pvp"){
                        if($args[2] == "enable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(/* TODO MySQL Statements */){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    		$owner = ; //TODO MySQL Statements
                    		if($owner == $sender->getName()){
                    			$pvp = ; //TODO MySQL Statements
                    			if($pvp === true){
                    				$sender->sendMessage("[AreaProtect] PvP is already enabled in " . $args[3] . "!");
                    			}else{
                    				//TODO MySQL Statements
                    				$sender->sendMessage("[AreaProtect] PvP is now enabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }elseif($args[2] == "disable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(/* TODO MySQL Statements */){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$owner = ; //TODO MySQL Statements
                    		if($owner == $sender->getName()){
                    			$pvp = ; //TODO MySQL Statements
                    			if($pvp === false){
                    				$sender->sendMessage("[AreaProtect] PvP is already disabled in " . $args[3] . "!");
                    			}else{
                    				//TODO MySQL Statements
                    				$sender->sendMessage("[AreaProtect] PvP is now disabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }else{
                            $sender->sendMessage("Usage: /areaprotect flag pvp <enable|disable> [name]");
                        }
                    }elseif($args[1] == "build"){
                        if($args[2] == "enable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(/* TODO MySQL Statements */){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$owner = ; //TODO MySQL Statements
                    		if($owner == $sender->getName()){
                    			$build = ; //TODO MySQL Statements
                    			if($build === true){
                    				$sender->sendMessage("[AreaProtect] Public building is already enabled in " . $args[3] . "!");
                    			}else{
                    				//TODO MySQL Statements
                    				$sender->sendMessage("[AreaProtect] Public building is now enabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }elseif($args[2] == "disable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(/* TODO MySQL Statements */){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$owner = ; //TODO MySQL Statements
                    		if($owner == $sender->getName()){
                    			$build = ; //TODO MySQL Statements
                    			if($build === false){
                    				$sender->sendMessage("[AreaProtect] Public building is already disabled in " . $args[3] . "!");
                    			}else{
                    				//TODO MySQL Statements
                    				$sender->sendMessage("[AreaProtect] Public building is now disabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }else{
                            $sender->sendMessage("Usage: /areaprotect flag build <enable|disable> [name]");
                        }
                    }elseif($args[1] == "destroy"){
                        if($args[2] == "enable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(/* TODO MySQL Statements */){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$owner = ; //TODO MySQL Statements
                    		if($owner == $sender->getName()){
                    			$destroy = ; //TODO MySQL Statements
                    			if($destroy === true){
                    				$sender->sendMessage("[AreaProtect] Public destruction is already enabled in " . $args[3] . "!");
                    			}else{
                    				//TODO MySQL Statements
                    				$sender->sendMessage("[AreaProtect] Public destruction is now enabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }elseif($args[2] == "disable"){
                            if(!isset($args[3])){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(/* TODO MySQL Statements */){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$owner = ; //TODO MySQL Statements
                    		if($owner == $sender->getName()){
                    			$destroy = ; //TODO MySQL Statements
                    			if($destroy === false){
                    				$sender->sendMessage("[AreaProtect] Public destruction is already disabled in " . $args[3] . "!");
                    			}else{
                    				//TODO MySQL Statements
                    				$sender->sendMessage("[AreaProtect] Public destruction is now disabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }else{
                            $sender->sendMessage("Usage: /areaprotect flag destroy <enable|disable> [name]");
                        }
                    }else{
                        $sender->sendMessage("Usage: /areaprotect flag <pvp|build|destroy>");
                    }
                }else{
                    $sender->sendMessage("Usage: /areaprotect <pos1|pos2|protect|flag|delete> [name]");
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
     * @param PlayerInteractEvent $event
     *
     * @priority       NORMAL
     * @ignoreCanceled false
     */
    public function onBuild(PlayerInteractEvent $event){
    	//TODO check if in an area, if owner, flag status, etc.
    }
    
    /**
     * @param PlayerInteractEvent $event
     *
     * @priority       NORMAL
     * @ignoreCanceled false
     */
    public function onDestroy(PlayerInteractEvent $event){
    	//TODO check if in an area, if owner, flag status, etc.
    }
    
    public function onDisable(){
        $this->getLogger()->log("[AreaProtect] AreaProtect Unloaded!");
    }
}

?>
