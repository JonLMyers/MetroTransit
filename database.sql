USE metro;

drop table users_tickets;
drop table tickets;
drop table users;

CREATE TABLE IF NOT EXISTS `users` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT, 
  `username` varchar(100) NOT NULL, 
  `password` varchar(100) NOT NULL,
  `credits` decimal(6,2),
  PRIMARY KEY (`id`) 
);

CREATE TABLE IF NOT EXISTS `tickets` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
  `start` varchar(100) NOT NULL, 
  `end` varchar(100) NOT NULL, 
  `cost` decimal(6,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS `users_tickets` (
  `user_id` int (11) NOT NULL,
  `ticket_id` int (11) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  PRIMARY KEY (ticket_id, user_id)
);

INSERT INTO `tickets` (`id`, `start`, `end`, `cost`) VALUES
(1, 'Rochester, NY', 'Albany, NY', '15.00'), 
(2, 'RIT, NY', 'UofR, NY', '20.00'), 
(3, 'McGreggors, NY', 'Dribbles, NY', '50.00'), 
(4, 'Washington DC', 'Baltimore, MD', '55.00'), 
(5, 'RIT, NY', 'Baltimore. MD', '54.00'), 
(6, 'Rochester, NY', 'Rome, NY', '34.00');