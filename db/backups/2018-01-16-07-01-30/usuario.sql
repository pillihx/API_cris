DROP TABLE IF EXISTS `usuario`;

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `usuario` (`id`, `nombre`, `email`) VALUES
	(1,'test','test@test.org');

-- ------------------------------------------------ 

