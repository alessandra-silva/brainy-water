-- DROP DATABASE BrainyWater;

CREATE DATABASE IF NOT EXISTS `BrainyWater`;

USE `BrainyWater`;

CREATE TABLE IF NOT EXISTS `User`(
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) UNIQUE NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`picture` VARCHAR(255) DEFAULT "images/default.png"
);

CREATE TABLE IF NOT EXISTS `Sensor`(
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`type` VARCHAR(5) NOT NULL,
	`name` VARCHAR(50) NOT NULL,
	`token` VARCHAR(255) NOT NULL,
	`user` INT NOT NULL,
  FOREIGN KEY (`user`) 
    REFERENCES `User`(`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `Sensor_value`(
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`reading` FLOAT NOT NULL,
	`date` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`sensor` INT NOT NULL,
  FOREIGN KEY (`sensor`) 
    REFERENCES `Sensor`(`id`)
);

-- https://emn178.github.io/online-tools/sha512.html

INSERT INTO `User` (`name`, `email`,`password`)
VALUES ("Lelezi", "alessandra-sa@hotmail.com", "a6ff8d5d74a25d0b1718252948c3420e710e7fa1ed2af4bdee8146c78ff7248ba9f2abf95646c6483520a723dbbde8987dc4c3acd88cac6fe279b0e79bf95b05");
-- password: amolelezi

INSERT INTO `Sensor` (`type`, `name`, `token`, `user`)
VALUES ("vazao", "Vazão de água", "jkh31j2kh4kj12h43.lelezi", 1);

INSERT INTO `Sensor_value` (`reading`, `sensor`)
VALUES (123.43, 1);

DELIMITER $$
CREATE TRIGGER Trigger_Auto_Clean_Senso_Value AFTER INSERT
ON Sensor_value
FOR EACH ROW
BEGIN
    DELETE FROM Sensor_value WHERE date < (NOW() - INTERVAL 12 HOUR) AND sensor = new.sensor;
END
$$
DELIMITER ;
