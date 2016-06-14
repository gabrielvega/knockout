//User's table for the normal and Facebook register

CREATE TABLE IF NOT EXISTS `knockout_users` (
`id` int(10) NOT NULL AUTO_INCREMENT,
PRIMARY KEY  (id),
  `identifier` varchar(50) NOT NULL,
UNIQUE KEY `identifier` (`identifier`),
  `email` varchar(50) DEFAULT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `avatar_url` varchar(255),
  `password` varchar(16) DEFAULT NULL
) ENGINE=InnoDB