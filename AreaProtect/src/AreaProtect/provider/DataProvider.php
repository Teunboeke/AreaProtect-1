<?php

namespace AreaProtect\provider;

use pocketmine\utils\Config;
use AreaProtect\Main;

interface DataProvider{
	public function __construct(AreaProtect $plugin);

	public function createArea($owner, $name, $x1, $y1, $z1, $x2, $y2, $z2, $pvp, $build, $destroy);

	public function checkExists($area);

	public function checkOwner($area);

	public function deleteArea($area);

	public function checkPVP($area);

	public function enablePVP($area);
	
	public function disablePVP($area);
	
	public function checkBuild($area);
	
	public function enableBuild($area);
	
	public function disableBuild($area);
	
	public function checkDestroy($area);
	
	public function enableDestroy($area);
	
	public function disableDestroy($area);
	
	public function checkInteract($area);
	
	public function enableInteract($area);
	
	public function disableInteract($area);
	
	public function getAllPVP();
	
	public function getAllBuild();
	
	public function getAllDestroy();
	
	public function getAllInteract();
	
	public function getPositions($area);

	public function close();
}
