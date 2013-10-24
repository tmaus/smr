
#INSERT INTO permission (permission_id, permission_name,link_to) VALUES (34, 'Research','research_view.php');
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `game_research`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `game_research` ;

CREATE TABLE IF NOT EXISTS `game_research` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `game_id` INT NULL,
  `ship_research` TINYINT(1) NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE INDEX `GR_GAME_IDX` ON `game_research` (`game_id` ASC);


-- -----------------------------------------------------
-- Table `game_research_ship_certificate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `game_research_ship_certificate` ;

CREATE TABLE IF NOT EXISTS `game_research_ship_certificate` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `game_research_certificate_id` INT NOT NULL,
  `ship_type_id` INT NOT NULL,
  `parent_id` INT NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE INDEX `GRSC_GAME_RESEARCH_CERTIFICATE_IDX` ON `game_research_ship_certificate` (`game_research_certificate_id` ASC);

CREATE INDEX `GRSC_PARENT_IDX` ON `game_research_ship_certificate` (`parent_id` ASC);


-- -----------------------------------------------------
-- Table `game_research_certificate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `game_research_certificate` ;

CREATE TABLE IF NOT EXISTS `game_research_certificate` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `game_research_id` INT NOT NULL,
  `label` VARCHAR(45) NULL,
  `duration` INT NULL,
  `iteration` INT NULL,
  `race_id` INT NULL,
  `combined_research` TINYINT(1) NULL,
  `parent_id` INT NULL,
  `credits` INT NULL,
  `computer` INT NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE INDEX `GRC_GAME_RESARCH_IDX` ON `game_research_certificate` (`game_research_id` ASC);

CREATE INDEX `GRC_PARENT_IDX` ON `game_research_certificate` (`parent_id` ASC);


-- -----------------------------------------------------
-- Table `game_alliance_ship_certificate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `game_alliance_ship_certificate` ;

CREATE TABLE IF NOT EXISTS `game_alliance_ship_certificate` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `game_research_ship_certificate_id` INT NOT NULL,
  `alliance_id` INT NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE INDEX `GASC_GAME_RESEARCH_SHIP_CERTIFICATE_IDX` ON `game_alliance_ship_certificate` (`game_research_ship_certificate_id` ASC);

CREATE INDEX `GASC_ALLIANCE_IDX` ON `game_alliance_ship_certificate` (`alliance_id` ASC);


-- -----------------------------------------------------
-- Table `game_alliance_research_progress`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `game_alliance_research_progress` ;

-- -----------------------------------------------------
-- Table `game_alliance_research_progress`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `game_alliance_research_progress` ;

CREATE TABLE IF NOT EXISTS `game_alliance_research_progress` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `game_research_ship_certificate_id` INT NULL,
  `started_at` INT(10) NOT NULL,
  `player_id` INT NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `alliance_id` INT NOT NULL,
  `sector_id` INT NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE INDEX `GARP_GAME_RESEARCH_SHIP_CERTIFICATE_IDX` ON `game_alliance_research_progress` (`game_research_ship_certificate_id` ASC);

CREATE INDEX `GARP_ALLIANCE_IDX` ON `game_alliance_research_progress` (`alliance_id` ASC);

CREATE INDEX `GARP_SECTOR_IDX` ON `game_alliance_research_progress` (`sector_id` ASC);




SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
