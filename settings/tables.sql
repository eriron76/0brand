-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `enricoferro` DEFAULT CHARACTER SET utf8 ;
USE `enricoferro` ;

-- -----------------------------------------------------
-- Table `enricoferro`.`node_tree`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `enricoferro`.`node_tree` (
  `idNode` INT NOT NULL,
  `level` INT NOT NULL,
  `iLeft` INT NOT NULL,
  `iRight` INT NOT NULL,
  PRIMARY KEY (`idNode`),
  INDEX `left` (`iLeft` ASC),
  INDEX `right` (`iRight` ASC),
  INDEX `left-right` (`iLeft` ASC, `iRight` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `enricoferro`.`node_tree_names`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `enricoferro`.`node_tree_names` (
  `idNode` INT NOT NULL,
  `language` ENUM('english', 'italian') NOT NULL,
  `NodeName` VARCHAR(45) NOT NULL,
  INDEX `language` (`language` ASC),
  INDEX `name` (`NodeName` ASC),
  PRIMARY KEY (`idNode`, `language`))
ENGINE = InnoDB



