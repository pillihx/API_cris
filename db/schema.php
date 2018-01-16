<?php
//SETTINGS
$SETTINGS = require( __DIR__ . "/../app/settings.php");
//TABLES
$files = glob($dir . '/*.php');
$tables = array();
$foreign = array();
foreach(glob( __DIR__ . "/models/*.php") as $file){
	$temp = require($file);
	$tables[$temp[0]] = $temp[1];
	if(isset($temp[2]))
		$foreign[$temp[0]] = $temp[2];
}
//SEEDS
$seeds = require("seeds.php");
//SCHEMA
return array(
	"dbname" => $SETTINGS["settings"]["db"]["dbname"],
	"tables" => $tables,
	"seeds" => $seeds,
	"foreign" => $foreign
);