<?php

return array(
		"registro",
		array(
			"id" => "INT AUTO_INCREMENT PRIMARY KEY",
			"usuario_id" => "INT(11) NOT NULL",
			"entrada" => "DATETIME",
			"salida" => "DATETIME",
			"FOREIGN" => "KEY (usuario_id) REFERENCES usuario(id)"
		)
);