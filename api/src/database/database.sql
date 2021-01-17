-- DROP DATABASE BrainyWater;

CREATE DATABASE IF NOT EXISTS `BrainyWater`;

USE `BrainyWater`;

CREATE TABLE IF NOT EXISTS `User`(
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) UNIQUE NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`picture` VARCHAR(255) DEFAULT "image/default.png"
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
VALUES ("Mateus", "hello@mateux.dev", "2396a1c95eda66709d1609f7f74b6bd2306864f4287fa488ac4515c1bd3127940b69b9fa5dc82e0d71fa411ea093b7bedd3ef7c081405569dd54df97aa95676a");
-- password: mateus

INSERT INTO `Sensor` (`type`, `name`, `token`, `user`)
VALUES ("vazao", "Vazão de água", "jkh31j2kh4kj12h43.mateuslucas", 1);

INSERT INTO `Sensor_value` (`reading`, `sensor`)
VALUES (123.43, 1);