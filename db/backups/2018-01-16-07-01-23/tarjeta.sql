DROP TABLE IF EXISTS `tarjeta`;

CREATE TABLE IF NOT EXISTS `tarjeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `n_tarjeta` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `tarjeta_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `tarjeta` (`id`, `n_tarjeta`, `usuario_id`) VALUES
	(1,1483418765,1);

-- ------------------------------------------------ 

