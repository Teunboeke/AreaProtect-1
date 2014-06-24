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

clas Main extends PluginBase implements Listener, CommandExecutor{
    public function onEnable(){
    	$this->saveDefaultConfig();
        $this->getResource("config.yml");
        if(!file_exists($this->plugin->getDataFolder() . "Areas/")){
		@mkdir($this->plugin->getDataFolder() . "Areas/");
	}
        $this->getLogger()->log("[AreaProtect] AreaProtect Loaded!");
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        switch($cmd->getName()){
            case "areaprotect":
                if($args[0] == "pos1"){
                    //TODO Get senders position
                }elseif($args[0] == "pos2"){
                    //TODO Get players position
                }elseif($args[0] == "protect"){
                    if($args[1] == null){
                        $sender->sendMessage("[AreaProtect] You must specify an area name!");
                    }elseif(file_exists($this->plugin->getDataFolder() . "Areas/" . $args[1] . ".yml")){
                        $sender->sendMessage("[AreaProtect] An area with that name already exists!");
                    }else{
		        $data = new Config($this->plugin->getDataFolder() . "Areas/" . $args[1] . ".yml", Config::YAML);
		        $name = $player->getName();
		        $data->set("owner", $name);
		        $data->set("members", null);
		        $data->set("x1", $x1);
		        $data->set("y1", $y1);
		        $data->set("z1", $z1);
		        $data->set("x2", $x2);
		        $data->set("y2", $y2);
		        $data->set("z2", $z2);
		        $data->set("pvp", null);
		        $data->set("build", null);
		        $data->set("destroy", null);
		        $data->save();
		        $sender->sendMessage("[AreaProtect] Your area has been created!");
                    }
                }elseif($args[0] == "delete"){
                    if($args[1] == null){
                        $sender->sendMessage("[AreaProtect] You must specify an area name!");
                    }elseif(!file_exists($this->plugin->getDataFolder() . "Areas/" . $args[1] . ".yml")){
                        $sender->sendMessage("[AreaProtect] Unable to find the area" . $args[1] . "!");
                    }else{
                    	$path = $this->plugin->getDataFolder() . "Areas/" . $args[1] . ".yml";
                    	$protection_data = new Config($path, Config::YAML);
                    	if($protection_data->get("owner") == $sender->getName()){
                    		@unlink($this->plugin->getDataFolder() . "Areas/" . $args[1] . ".yml");
                        	$sender->sendMessage("[AreaProtect] Your area has been deleted!");
                    	}else{
                    		$sender->sendMessage("[AreaProtect] You do not own the area " . $args[1] . "!");
                    	}
                    }
                }elseif($args[0] == "flag"){
                    if($args[1] == "pvp"){
                        if($args[2] == "enable"){
                            if($args[3] == null){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(!file_exists($this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml")){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$path = $this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml";
                    		$protection_data = new Config($path, Config::YAML);
                    		if($protection_data->get("owner") == $sender->getName()){
                    			if($protection_data->get("pvp") === true){
                    				$sender->sendMessage("[AreaProtect] PvP is already enabled in " . $args[3] . "!");
                    			}else{
                    				$protection_data->set("pvp", true);
                    				$sender->sendMessage("[AreaProtect] PvP is now enabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }elseif($args[2] == "disable"){
                            if($args[3] == null){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(!file_exists($this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml")){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$path = $this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml";
                    		$protection_data = new Config($path, Config::YAML);
                    		if($protection_data->get("owner") == $sender->getName()){
                    			if($protection_data->get("pvp") === false){
                    				$sender->sendMessage("[AreaProtect] PvP is already disabled in " . $args[3] . "!");
                    			}else{
                    				$protection_data->set("pvp", false);
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
                            if($args[3] == null){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(!file_exists($this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml")){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$path = $this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml";
                    		$protection_data = new Config($path, Config::YAML);
                    		if($protection_data->get("owner") == $sender->getName()){
                    			if($protection_data->get("build") === true){
                    				$sender->sendMessage("[AreaProtect] Public building is already enabled in " . $args[3] . "!");
                    			}else{
                    				$protection_data->set("build", true);
                    				$sender->sendMessage("[AreaProtect] Public building is now enabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }elseif($args[2] == "disable"){
                            if($args[3] == null){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(!file_exists($this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml")){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$path = $this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml";
                    		$protection_data = new Config($path, Config::YAML);
                    		if($protection_data->get("owner") == $sender->getName()){
                    			if($protection_data->get("build") === false){
                    				$sender->sendMessage("[AreaProtect] Public building is already disabled in " . $args[3] . "!");
                    			}else{
                    				$protection_data->set("build", false);
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
                            if($args[3] == null){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(!file_exists($this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml")){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$path = $this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml";
                    		$protection_data = new Config($path, Config::YAML);
                    		if($protection_data->get("owner") == $sender->getName()){
                    			if($protection_data->get("destroy") === true){
                    				$sender->sendMessage("[AreaProtect] Public destruction is already enabled in " . $args[3] . "!");
                    			}else{
                    				$protection_data->set("destroy", true);
                    				$sender->sendMessage("[AreaProtect] Public destruction is now enabled in " . $args[3] . "!");
                    			}
                    		}else{
                    			$sender->sendMessage("[AreaProtect] You do not own the area " . $args[3] . "!");
                    		}
                    	    }
                        }elseif($args[2] == "disable"){
                            if($args[3] == null){
                        	$sender->sendMessage("[AreaProtect] You must specify an area name!");
                    	    }elseif(!file_exists($this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml")){
                        	$sender->sendMessage("[AreaProtect] Unable to find the area" . $args[3] . "!");
                    	    }else{
                    	    	$path = $this->plugin->getDataFolder() . "Areas/" . $args[3] . ".yml";
                    		$protection_data = new Config($path, Config::YAML);
                    		if($protection_data->get("owner") == $sender->getName()){
                    			if($protection_data->get("destroy") === false){
                    				$sender->sendMessage("[AreaProtect] Public destruction is already disabled in " . $args[3] . "!");
                    			}else{
                    				$protection_data->set("destroy", false);
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
    
    public function onAttack(){
    	//Player vs Player actions are not yet implemented on PocketMine-MP Alpha_1.4.0
    }
    
    public function onBuild(){
    	//TODO check if in an area, if owner, flag status, etc.
    }
    
    public function onDestroy(){
    	//TODO check if in an area, if owner, flag status, etc.
    }
    
    public function onDisable(){
        $this->getLogger()->log("[AreaProtect] AreaProtect Unloaded!");
    }
}

?>
