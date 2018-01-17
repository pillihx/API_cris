<?php

return array(
		"registro",
		array(
			"id" => "INT AUTO_INCREMENT PRIMARY KEY",
			"tarjeta_id" => "INT NOT NULL",
			"tipo" => "TINYINT(1)",
			"datetime" => "DATETIME"
		),
		"KEY (tarjeta_id) REFERENCES tarjeta(id)"
);