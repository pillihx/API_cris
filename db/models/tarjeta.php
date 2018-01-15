<?php

return array(
		"tarjeta",
		array(
			"id" => "INT AUTO_INCREMENT PRIMARY KEY",
			"n_tarjeta" => "INT(11) NOT NULL",
			"usuario_id" => "INT(11) NOT NULL",
			"FOREIGN" => "KEY (usuario_id) REFERENCES usuario(id)"
		)
);