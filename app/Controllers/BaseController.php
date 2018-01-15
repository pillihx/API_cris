<?php

namespace App\Controllers;

class BaseController {
	protected $container;
	protected $DB;

	public function __construct($container) {
		$this->container = $container;
		$this->DB = $container->db;
	}
}
