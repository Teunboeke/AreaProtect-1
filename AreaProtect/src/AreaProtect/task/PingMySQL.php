<?php

namespace AreaProtect\task;

use pocketmine\scheduler\PluginTask;
use AreaProtect\Main;

class PingMySQL extends PluginTask{
    private $database;
    public function __construct(Main $owner, \mysqli $database){
        parent::__construct();
        $this->database = $database;
    }
    
    public function onRun($currentTick){
        $this->database->ping();
    }
}
