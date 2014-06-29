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
        $this->owner = $owner;
        $this->name = $name;
        $this->x1 = $x1;
        $this->y1 = $y1;
        $this->z1 = $z1;
        $this->x2 = $x2;
        $this->y2 = $y2;
        $this->z2 = $z2;
        $this->pvp = $pvp;
        $this->build = $build;
        $this->destroy = $destroy;
        $this->database->query("INSERT INTO areaprotect_areas (owner, name, x1, y1, z1, x2, y2, z2, pvp, build, destroy) VALUES ('".$this->owner."', ".$this->name.", ".$this->x1.", "$this->y1", ".$this->z1.", ".$this->x2.", ".$this->y2.", ".$this->z2.", ".$this->pvp.", ".$this->build.", ".$this->destroy.")");
    }
    
    public function checkExists($args[1], $args[3]){ //I think Ill need to change this a little but hopefully not
        $this->area1 = $args[1];
        $this->area2 = $args[3];
        if(isset($this->area1)){
            $area = $this->area1;
        }elseif(isset($this->area2)){
            $area = $this->area2;
        }
        $this->database->query("SELECT * FROM areaprotect_areas WHERE name=".$area);
    }
    
    public function checkOwner($area){
        $this->area = $area;
        $this->database->query("SELECT :owner FROM areaprotect_areas WHERE name=".$this->area); //Not 100% sure how to do this
    }
    
    public function deleteArea($area){
        $this->area = $area;
        $this->database->query("DELETE FROM areaprotect_areas WHERE name=".$this->area);
    }
    
    public function checkPVP($area){
        $this->area = $area;
        $this->database->query("SELECT pvp FROM areaprotect_areas WHERE name=".$this->area);
    }
    
    public function enablePVP($area){
        $this->area = $area;
        $this->database->query("UPDATE areaprotect_areas SET pvp=1 WHERE name=".$this->area);
    }
    
    public function disablePVP($area){
        $this->area = $area;
        $this->database->query("UPDATE areaprotect_areas SET pvp=0 WHERE name=".$this->area);
    }
    
    public function checkBuild($area){
        $this->area = $area;
        $this->database->query("SELECT build FROM areaprotect_areas WHERE name=".$this->area);
    }
    
    public function enableBuild($area){
        $this->area = $area;
        $this->database->query("UPDATE areaprotect_areas SET build=1 WHERE name=".$this->area);
    }
    
    public function disableBuild($area){
        $this->area = $area;
        $this->database->query("UPDATE areaprotect_areas SET build=0 WHERE name=".$this->area);
    }
    
    public function checkDestroy($area){
        $this->area = $area;
        $this->database->query("SELECT destroy FROM areaprotect_areas WHERE name=".$this->area);
    }
    
    public function enableDestroy($area){
        $this->area = $area;
        $this->database->query("UPDATE areaprotect_areas SET destroy=1 WHERE name=".$this->area);
    }
    
    public function disableDestroy($area){
        $this->area = $area;
        $this->database->query("UPDATE areaprotect_areas SET destroy=0 WHERE name=".$this->area);
    }
    
    public function getAllPVP(){
        $this->database->query("SELECT * FROM areaprotect_areas WHERE pvp=0");
    }
    
    public function getAllBuild(){
        $this->database->query("SELECT * FROM areaprotect_areas WHERE build=0");
    }
    
    public function getAllDestroy(){
        $this->database->query("SELECT * FROM areaprotect_areas WHERE destroy=0");
    }
    
    public function close(){
        $this->database->close();
    }
}
