DROP TABLE IF EXISTS `tarjeta`;

CREATE TABLE IF NOT EXISTS `tarjeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `n_tarjeta` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `tarjeta_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- ------------------------------------------------ 

