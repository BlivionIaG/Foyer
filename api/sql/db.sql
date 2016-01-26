CREATE TABLE IF NOT EXISTS `CLUB` (
  `id_club` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_club`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `CLUB` (`id_club`) VALUES
(1);

CREATE TABLE IF NOT EXISTS `COMMAND` (
  `id_commande` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(25) DEFAULT NULL,
  `state` varchar(25) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date` timestamp NOT NULL,
  `periode_debut` varchar(64) NOT NULL,
  `periode_fin` varchar(128) NOT NULL,
  `image` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `FK_COMMAND_login` (`login`),
  KEY `FK_COMMAND_state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

CREATE TABLE IF NOT EXISTS `NOTIFICATION` (
  `id_notification` int(11) NOT NULL AUTO_INCREMENT,
  `id_command` int(11) NOT NULL,
  `notification` varchar(256) DEFAULT NULL,
  `login` varchar(25) DEFAULT NULL,
  `method` int(11) NOT NULL,
  PRIMARY KEY (`id_notification`),
  KEY `FK_NOTIFICATION_login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=341 ;

CREATE TABLE IF NOT EXISTS `PRODUCT` (
  `id_product` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `available` tinyint(1) DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

CREATE TABLE IF NOT EXISTS `PRODUCT_COMMAND` (
  `quantity` int(11) DEFAULT NULL,
  `id_product` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  PRIMARY KEY (`id_product`,`id_commande`),
  KEY `FK_PRODUCT_COMMAND_id_commande` (`id_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `STATE` (
  `state` varchar(25) NOT NULL,
  PRIMARY KEY (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `STATE` (`state`) VALUES
('0'),
('1'),
('2'),
('3');

CREATE TABLE IF NOT EXISTS `USER` (
  `login` varchar(25) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `USER_CLUB` (
  `login` varchar(25) NOT NULL,
  `password` varchar(20) DEFAULT NULL,
  `id_club` int(11) NOT NULL,
  PRIMARY KEY (`login`,`id_club`),
  KEY `id_club` (`id_club`),
  KEY `id_club_2` (`id_club`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `USER_CLUB` (`login`, `password`, `id_club`) VALUES
('ksidor18', 's3curit3', 1);


ALTER TABLE `COMMAND`
  ADD CONSTRAINT `FK_COMMAND_login` FOREIGN KEY (`login`) REFERENCES `USER` (`login`),
  ADD CONSTRAINT `FK_COMMAND_state` FOREIGN KEY (`state`) REFERENCES `STATE` (`state`);

ALTER TABLE `NOTIFICATION`
  ADD CONSTRAINT `FK_NOTIFICATION_login` FOREIGN KEY (`login`) REFERENCES `USER` (`login`);

ALTER TABLE `PRODUCT_COMMAND`
  ADD CONSTRAINT `FK_PRODUCT_COMMAND_id_commande` FOREIGN KEY (`id_commande`) REFERENCES `COMMAND` (`id_commande`),
  ADD CONSTRAINT `FK_PRODUCT_COMMAND_id_product` FOREIGN KEY (`id_product`) REFERENCES `PRODUCT` (`id_product`);

ALTER TABLE `USER_CLUB`
  ADD CONSTRAINT `FK_USER_CLUB_id_club` FOREIGN KEY (`id_club`) REFERENCES `CLUB` (`id_club`),
  ADD CONSTRAINT `FK_USER_CLUB_login` FOREIGN KEY (`login`) REFERENCES `USER` (`login`);

