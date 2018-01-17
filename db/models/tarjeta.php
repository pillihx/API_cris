<?php

return array(
		"tarjeta",
		array(
			"id" => "INT AUTO_INCREMENT PRIMARY KEY",
			"n_tarjeta" => "BIGINT NOT NULL",
			"usuario_id" => "INT NOT NULL"
		),
		"KEY (usuario_id) REFERENCES usuario(id)"
);

