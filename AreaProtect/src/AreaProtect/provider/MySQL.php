<?php

namespace AreaProtect\provider;

use AreaProtect\Main;
use AreaProtect\task\PingMySQL;

class MySQL implements DataProvider{
    protected $plugin;
    protected $database;
    public function __construct(AreaProtect $plugin){
        $this->plugin = $plugin;
        $host = $this->plugin->getConfig()->get("Host");
        $port = $this->plugin->getConfig()->get("Port");
        $username = $this->plugin->getConfig()->get("Username");
        $password = $this->plugin->getConfig()->get("Password");
        $database = $this->plugin->getConfig()->get("DatabaseName");
        
        $this->database = new \mysqli($host, $username, $password, $database, $port);
        if($this->database->connect_error){
            $this->plugin->getLogger()->critical("Unable to connect to Database!");
            return;
        }
        
        $sql = $this->plugin->getResource("mysql.sql");
        $this->database->query(streamv¥et_contents($sql));
        
        $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask(new PingMySQL($this->plugin, $this->database), 30*20);
        $this->plugin->getLogger()->info("Connected to Database successfully!");
    }
    
    public function createArea($owner, $name, $x1, $y1, $z1, $x2, $y2, $z2, $pvp, $build, $destroy){
        
    }
    
    public function checkExists($args[1], $args[3]){ //I think Ill need to change this a little but hopefully not
        
    }
    
    public function checkOwner($area){
        
    }
    
    public function deleteArea($area){
        
    }
    
    public function checkPVP($area){
        
    }
    
    public function enablePVP($area){
        
    }
    
    public function disablePVP($area){
        
    }
    
    public function checkBuild($area){
        
    }
    
    public function enableBuild($area){
        
    }
    
    public function disableBuild($area){
        
    }
    
    public function checkDestroy($area){
        
    }
    
    public function enableDestroy($area){
        
    }
    
    public function disableDestroy($area){
        
    }
    
    public function getAllPVP(){
        
    }
    
    public function getAllBuild(){
        
    }
    
    public function getAllDestroy(){
        
    }
    
    public function close(){
        $this->database->close();
    }
}
