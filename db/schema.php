<?php
//SETTINGS
$SETTINGS = require( __DIR__ . "/../app/settings.php");
//MODELS
$files = glob($dir . '/*.php');
$tables = array();
foreach(glob( __DIR__ . "/models/*.php") as $file){
	$temp = require($file);
	$tables[$temp[0]] = $temp[1];
}
//SEEDS
$seeds = require("seeds.php");
//SCHEMA
return array(
	"dbname" => $SETTINGS["settings"]["db"]["dbname"],
	"tables" => $tables,
	"seeds" => $seeds
);
