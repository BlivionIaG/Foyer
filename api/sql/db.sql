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
  `periode_debut` varchar(64) NOT NULL,
  `periode_fin` varchar(128) NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `FK_COMMAND_login` (`login`),
  KEY `FK_COMMAND_state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

INSERT INTO `COMMAND` (`id_commande`, `login`, `state`, `time`, `periode_debut`, `periode_fin`) VALUES
(82, 'anicol17', '1', '2015-06-02 08:24:38', '23h10', '23h20'),
(83, 'anicol17', '3', '2015-06-04 16:03:28', '10h30', '10h40');

CREATE TABLE IF NOT EXISTS `NOTIFICATION` (
  `id_notification` int(11) NOT NULL AUTO_INCREMENT,
  `id_command` int(11) NOT NULL,
  `notification` varchar(256) DEFAULT NULL,
  `login` varchar(25) DEFAULT NULL,
  `method` int(11) NOT NULL,
  PRIMARY KEY (`id_notification`),
  KEY `FK_NOTIFICATION_login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=341 ;

INSERT INTO `NOTIFICATION` (`id_notification`, `id_command`, `notification`, `login`, `method`) VALUES
(335, 82, 'Votre commande est en cours de validation', 'anicol17', 0),
(337, 83, 'dazodz ', 'anicol17', 2),
(340, 83, 'Votre commande a ??pay?', 'anicol17', 0);

CREATE TABLE IF NOT EXISTS `PRODUCT` (
  `id_product` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `available` tinyint(1) DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

INSERT INTO `PRODUCT` (`id_product`, `name`, `price`, `description`, `available`, `date`) VALUES
(2, 'Pizza Italienne', 9.5, 'La pizza italienne est un vrai repas ?uilibr?et complet qui contient des glucides, des prot?nes et des vitamines', 1, '2015-05-23 16:01:09'),
(5, 'Verre de coca', 3.5, 'N''est pas du pepsi', 1, '2015-05-23 16:01:09'),
(6, 'Galette Saucisse', 2, 'J?en mangerai des kilos, et des kilos !, \r\nDans toute l?Ille-et-Vilaine, \r\nAvec du lait Ribot, du lait Ribot ! \r\nEt si tu m?abandonnes, ', 1, '2015-05-23 16:01:09'),
(16, 'Baguette', 0.8, 'Une baguette de pain ou simplement baguette, ou encore pain fran?is (qu??isme et belgicisme), est une vari??de pain, reconnaissable ?sa forme al', 1, '2015-05-23 16:01:09'),
(20, 'Frites', 2, 'Barquette de 200g de frites, fournis avec ses sauces aux choix (ketchup, moutardes ...).', 1, '2015-05-23 16:01:09'),
(22, 'Lasagne Findus', 3, 'D?icieuse lasagne ?la bolognaise (contiens de la viande de cheval).', 1, '2015-05-23 16:01:09'),
(23, 'Banane', 0.5, 'La banane est le fruit ou la baie d?ivant de l?inflorescence du bananier.', 1, '2015-05-11 16:01:09');

CREATE TABLE IF NOT EXISTS `PRODUCT_COMMAND` (
  `quantity` int(11) DEFAULT NULL,
  `id_product` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  PRIMARY KEY (`id_product`,`id_commande`),
  KEY `FK_PRODUCT_COMMAND_id_commande` (`id_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `PRODUCT_COMMAND` (`quantity`, `id_product`, `id_commande`) VALUES
(1, 5, 82),
(1, 5, 83),
(1, 6, 83),
(1, 16, 82),
(2, 16, 83),
(2, 20, 82),
(1, 22, 82);

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


INSERT INTO `USER` (`login`) VALUES
('anicol17'),
('ksidor18'),
('pmicha18');

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

