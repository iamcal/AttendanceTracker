
CREATE TABLE IF NOT EXISTS `et_attendance_days` (
  `d` date NOT NULL DEFAULT '0000-00-00',
  `who` varchar(255) NOT NULL DEFAULT '',
  `mins` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`d`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `et_attendance_timeline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `d` date NOT NULL DEFAULT '0000-00-00',
  `t` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event` varchar(255) NOT NULL DEFAULT '',
  `who` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `d` (`d`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `et_attendance_who` (
  `d` date NOT NULL DEFAULT '0000-00-00',
  `who` varchar(255) NOT NULL DEFAULT '',
  `mins` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`d`,`who`),
  KEY `who` (`who`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
