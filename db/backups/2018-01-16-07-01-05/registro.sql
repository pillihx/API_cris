DROP TABLE IF EXISTS `registro`;

CREATE TABLE IF NOT EXISTS `registro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tarjeta_id` int(11) NOT NULL,
  `entrada` datetime DEFAULT NULL,
  `salida` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tarjeta_id` (`tarjeta_id`),
  CONSTRAINT `registro_ibfk_1` FOREIGN KEY (`tarjeta_id`) REFERENCES `tarjeta` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- ------------------------------------------------ 

