-- MySQL Workbench Forward Engineering

create database banco_SA;

use banco_SA;

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema banco_SA
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema banco_SA
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `banco_SA` DEFAULT CHARACTER SET utf8 ;
USE `banco_SA` ;

-- -----------------------------------------------------
-- Table `banco_SA`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `banco_SA`.`usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `email_usuario` VARCHAR(45) NOT NULL,
  `senha_usuario` VARCHAR(255) NOT NULL,
  `cpf_usuario` CHAR(11) NOT NULL,
  `nome_usuario` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_usuario`));

CREATE TABLE IF NOT EXISTS `banco_SA` . `admin` (
  `id_admin` INT NOT NULL AUTO_INCREMENT,
  `email_admin` VARCHAR(45) NOT NULL,
  `senha_admin` VARCHAR(255) NOT NULL,
  `cpf_admin` CHAR(11) NOT NULL,
  `nome_admin` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_admin`)
);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


