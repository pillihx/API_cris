DROP TABLE IF EXISTS `registro`;

CREATE TABLE IF NOT EXISTS `registro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `n_tarjeta` int(11) NOT NULL,
  `entrada` datetime DEFAULT NULL,
  `salida` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `registro` (`id`, `n_tarjeta`, `entrada`, `salida`) VALUES
	(1,1483418765,'2018-01-16 19:48:18',NULL),
	(2,1483418765,'2018-01-16 19:48:50',NULL);

-- ------------------------------------------------ 

