<?php

namespace AreaProtect;

use pocketmine\plugin\PluginBase;
//use pocketmine\player\Position; ?
use pocketmine\level\Level;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;

clas Main extends PluginBase implements Listener, CommandExecutor{
    public function onEnable(){
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
                    //TODO Create protected area
                }elseif($args[0] == "delete"){
                    //TODO Remove protected area
                }elseif($args[0] == "flag"){
                    //TODO Flags
                }else{
                    $sender->sendMessage("Usage: /areaprotect <pos1|pos2|protect|flag|delete> [name]");
                }
            break;
        }
    }
    
    public function onDisable(){
        $this->getLogger()->log("[AreaProtect] AreaProtect Unloaded!");
    }
}
