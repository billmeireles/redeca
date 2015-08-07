SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Create Table 
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Definition of table `act_category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `act_category` (
  `id_category` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_category_father` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `category` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_category`) ,
  INDEX `fk_act_category_act_subcategory` (`id_category_father` ASC) ,
  CONSTRAINT `fk_act_category_act_subcategory`
    FOREIGN KEY (`id_category_father` )
    REFERENCES `act_category` (`id_category` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das categorias das atividades das entidades vincula' ;


-- -----------------------------------------------------
-- Definition of table `ast_target_market`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ast_target_market` (
  `id_target_market` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `target_market` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_target_market`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'tabela de registro de publico-alvo' ;

-- -----------------------------------------------------
-- Definition of table `ast_program_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ast_program_type` (
  `id_program_type` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_target_market` INT(10) UNSIGNED NOT NULL ,
  `program_type` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_program_type`) ,
  INDEX `fk_ast_program_type_ast_target_market` (`id_target_market` ASC),
  CONSTRAINT `fk_ast_program_type_ast_target_market`
    FOREIGN KEY (`id_target_market` )
    REFERENCES `ast_target_market` (`id_target_market` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos tipos de programas disponiveis LA, PSC, Complem' ;


-- -----------------------------------------------------
-- Definition of table `con_address_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_address_type` (
  `id_address_type` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(72) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  `user_inserted` TINYINT(4) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id_address_type`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `con_uf`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_uf` (
  `id_uf` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `abbreviation` VARCHAR(2) NOT NULL ,
  `user_inserted` TINYINT(4) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id_uf`) ,
  UNIQUE INDEX `uf_uf_sigla_key` (`abbreviation` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das UFs' ;


-- -----------------------------------------------------
-- Definition of table `con_city`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_city` (
  `id_city` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_uf` INT(10) UNSIGNED NOT NULL ,
  `city` VARCHAR(72) NOT NULL ,
  `user_inserted` TINYINT(4) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id_city`) ,
  INDEX `fk_con_city_con_uf` (`id_uf` ASC) ,
  CONSTRAINT `fk_con_city_con_uf`
    FOREIGN KEY (`id_uf` )
    REFERENCES `con_uf` (`id_uf` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das cidades e suas faixas de ceps (inicial e final)' ;


-- -----------------------------------------------------
-- Definition of table `con_neighborhood`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_neighborhood` (
  `id_neighborhood` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_city` INT(10) UNSIGNED NOT NULL ,
  `neighborhood` VARCHAR(72) NULL DEFAULT NULL ,
  `user_inserted` TINYINT(4) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id_neighborhood`) ,
  INDEX `fk_con_neighborhood_con_city` (`id_city` ASC) ,
  CONSTRAINT `fk_con_neighborhood_con_city`
    FOREIGN KEY (`id_city` )
    REFERENCES `con_city` (`id_city` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos bairros do municipio.' ;


-- -----------------------------------------------------
-- Definition of table `con_address`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_address` (
  `id_address` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_neighborhood` INT(10) UNSIGNED NOT NULL ,
  `id_address_type` INT(10) UNSIGNED NOT NULL ,
  `zip_code` VARCHAR(8) NULL DEFAULT NULL ,
  `address` VARCHAR(72) NULL DEFAULT NULL ,
  `address_metafone` VARCHAR(72) NOT NULL ,
  `user_inserted` TINYINT(4) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id_address`) ,
  INDEX `fk_con_address_con_address_type` (`id_address_type` ASC) ,
  INDEX `fk_con_address_con_neighborhood` (`id_neighborhood` ASC) ,
  INDEX `fk_con_address_con_source_type` (`user_inserted` ASC) ,
  CONSTRAINT `fk_con_address_con_address_type`
    FOREIGN KEY (`id_address_type` )
    REFERENCES `con_address_type` (`id_address_type` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_con_address_con_neighborhood`
    FOREIGN KEY (`id_neighborhood` )
    REFERENCES `con_neighborhood` (`id_neighborhood` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos logradouros do sistema, que serao utilizados pa' ;


-- -----------------------------------------------------
-- Definition of table `ent_entity`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ent_entity` (
  `id_entity` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_address` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `name` VARCHAR(100) NOT NULL ,
  `email` VARCHAR(90) NULL DEFAULT NULL ,
  `homepage` VARCHAR(200) NULL DEFAULT NULL ,
  `logo_img` VARCHAR(200) NULL DEFAULT NULL ,
  `number` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `complement` VARCHAR(80) NULL DEFAULT NULL ,
  `latitude` DOUBLE NULL DEFAULT NULL ,
  `longitude` DOUBLE NULL DEFAULT NULL ,
  `cnpj` VARCHAR(14) NULL DEFAULT NULL ,
  `status` TINYINT(4) NOT NULL ,
  PRIMARY KEY (`id_entity`) ,
  INDEX `fk_ent_entity_con_address` (`id_address` ASC) ,
  CONSTRAINT `fk_ent_entity_con_address`
    FOREIGN KEY (`id_address` )
    REFERENCES `con_address` (`id_address` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registrar os dados cadastrais das entidades da rede' ;


-- -----------------------------------------------------
-- Definition of table `ast_program`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ast_program` (
  `id_program` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_entity` INT(10) UNSIGNED NOT NULL ,
  `id_program_type` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_program`) ,
  INDEX `fk_ass_program_ass_program_type` (`id_program_type` ASC) ,
  INDEX `fk_programa_entidade` (`id_entity` ASC) ,
  CONSTRAINT `fk_ass_program_ass_program_type`
    FOREIGN KEY (`id_program_type` )
    REFERENCES `ast_program_type` (`id_program_type` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_programa_entidade`
    FOREIGN KEY (`id_entity` )
    REFERENCES `ent_entity` (`id_entity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vinculo entre a entidade e os programas de atendimento dispo' ;


-- -----------------------------------------------------
-- Definition of table `act_activity_detail`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `act_activity_detail` (
  `id_activity_detail` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activity_detail` VARCHAR(100) NOT NULL ,
  `hourly_load` INT(11) NOT NULL ,
  `id_program` INT(10) UNSIGNED NOT NULL ,
  `id_category` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_activity_detail`) ,
  INDEX `fk_act_activity_detail_act_category` (`id_category` ASC) ,
  INDEX `fk_act_activity_detail_ass_program` (`id_program` ASC) ,
  CONSTRAINT `fk_act_activity_detail_act_category`
    FOREIGN KEY (`id_category` )
    REFERENCES `act_category` (`id_category` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_act_activity_detail_ass_program`
    FOREIGN KEY (`id_program` )
    REFERENCES `ast_program` (`id_program` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro detalhado das atividades oferecidas pela entidade' ;


-- -----------------------------------------------------
-- Definition of table `act_class`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `act_class` (
  `id_class` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_program` INT(10) UNSIGNED NOT NULL ,
  `vacancy` INT(11) NOT NULL ,
  `schedule` VARCHAR(30) NULL DEFAULT NULL ,
  `period` INT(11) NOT NULL ,
  `name` VARCHAR(80) NOT NULL ,
  `start_date` DATE NOT NULL ,
  `end_date` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id_class`) ,
  INDEX `fk_act_class_ass_program` (`id_program` ASC) ,
  CONSTRAINT `fk_act_class_ass_program`
    FOREIGN KEY (`id_program` )
    REFERENCES `ast_program` (`id_program` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da turmas disponiblizadas pelas entidades' ;

-- -----------------------------------------------------
-- Definition of table `act_class_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `act_class_history` (
  `id_class_history` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) DEFAULT NULL,
  `id_class` int(10) DEFAULT NULL,
  `id_program` int(10) DEFAULT NULL,
  `id_person` int(10) DEFAULT NULL,
  `vacancy` int(11) DEFAULT NULL,
  `schedule` varchar(30) DEFAULT NULL,
  `period` int(11) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `dat_operation` date DEFAULT NULL,
  `type_operation` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_class_history`),
  KEY `user_reference` (`id_user`),
  KEY `class_reference` (`id_class`),
  KEY `program_reference` (`id_program`),
  KEY `person_reference` (`id_person`)
) 
ENGINE=InnoDB 
DEFAULT CHARACTER SET = latin1,
COMMENT = 'Registro das alterações nas turmas disponiblizadas pelas entidades' ;



-- -----------------------------------------------------
-- Definition of table `act_activity_class`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `act_activity_class` (
  `id_activity_class` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_class` INT(10) UNSIGNED NOT NULL ,
  `id_activity_detail` INT(10) UNSIGNED NOT NULL ,
  `start_date` DATE NOT NULL ,
  `end_date` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id_activity_class`) ,
  INDEX `fk_act_class_activity_act_activity_detail` (`id_activity_detail` ASC) ,
  INDEX `fk_act_class_activity_act_class` (`id_class` ASC) ,
  CONSTRAINT `fk_act_class_activity_act_activity_detail`
    FOREIGN KEY (`id_activity_detail` )
    REFERENCES `act_activity_detail` (`id_activity_detail` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_act_class_activity_act_class`
    FOREIGN KEY (`id_class` )
    REFERENCES `act_class` (`id_class` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Vinculo entre as atividades e suas turmas disponibilizadas p' ;


-- -----------------------------------------------------
-- Definition of table `act_status_class`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `act_status_class` (
  `id_status` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `status` VARCHAR(80) NOT NULL ,
  PRIMARY KEY (`id_status`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do status do atendimento em relacao a turma (atendi' ;


-- -----------------------------------------------------
-- Definition of table `auth_group`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_group` (
  `id_group` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `group_name` VARCHAR(90) NULL DEFAULT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_group`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Grupos criados pelo gerente da rede' ;


-- -----------------------------------------------------
-- Definition of table `auth_role`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_role` (
  `id_role` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `role` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_role`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `auth_user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_user` (
  `id_user` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_entity` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_group` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_role` INT(10) UNSIGNED NOT NULL ,
  `name` VARCHAR(90) NULL DEFAULT NULL ,
  `login` VARCHAR(30) NULL DEFAULT NULL ,
  `pass` VARCHAR(32) NOT NULL ,
  `email` VARCHAR(90) NULL DEFAULT NULL ,
  `cpf` VARCHAR(11) NOT NULL ,
  `active` TINYINT(4) NOT NULL ,
  `dat_creation` DATE NOT NULL ,
  `permission` INT(11) NOT NULL ,
  PRIMARY KEY (`id_user`) ,
  UNIQUE INDEX `auth_user_login_Idx` (`login` ASC) ,
  INDEX `fk_login_entidade` (`id_entity` ASC) ,
  INDEX `user_group_FK` (`id_group` ASC) ,
  INDEX `user_role_FK` (`id_role` ASC) ,
  CONSTRAINT `fk_user_entity`
    FOREIGN KEY (`id_entity` )
    REFERENCES `ent_entity` (`id_entity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_group_FK`
    FOREIGN KEY (`id_group` )
    REFERENCES `auth_group` (`id_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_role_FK`
    FOREIGN KEY (`id_role` )
    REFERENCES `auth_role` (`id_role` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos usuarios do sistema' ;


-- -----------------------------------------------------
-- Definition of table `per_marital_status`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_marital_status` (
  `id_marital_status` INT(10) UNSIGNED NOT NULL ,
  `marital_status` VARCHAR(30) NOT NULL ,
  PRIMARY KEY (`id_marital_status`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do estado civil da pessoa' ;


-- -----------------------------------------------------
-- Definition of table `per_nationality`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_nationality` (
  `id_nationality` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nationality` VARCHAR(80) NOT NULL ,
  PRIMARY KEY (`id_nationality`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da nacionalidade (Brasileiro, Estrangeiro, Naturali' ;


-- -----------------------------------------------------
-- Definition of table `per_race`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_race` (
  `id_race` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `race` VARCHAR(30) NOT NULL ,
  PRIMARY KEY (`id_race`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da raca da pessoa' ;


-- -----------------------------------------------------
-- Definition of table `per_person`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_person` (
  `id_person` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(90) NOT NULL ,
  `metaname` VARCHAR(60) NOT NULL ,
  `nickname` VARCHAR(80) NULL DEFAULT NULL ,
  `metanickname` VARCHAR(60) NULL DEFAULT NULL ,
  `sex` CHAR(1) NOT NULL DEFAULT 'M' ,
  `tattoo` VARCHAR(500) NULL DEFAULT NULL ,
  `native_country` VARCHAR(100) NULL DEFAULT NULL ,
  `arrival_date` DATE NULL DEFAULT NULL ,
  `death_date` DATE NULL DEFAULT NULL ,
  `birth_date` DATE NOT NULL ,
  `id_nationality` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_race` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_marital_status` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id_person`) ,
  UNIQUE INDEX `name` (`name` ASC, `birth_date` ASC) ,
  INDEX `fk_per_person_per_marital_status` (`id_marital_status` ASC) ,
  INDEX `fk_per_person_per_nationality` (`id_nationality` ASC) ,
  INDEX `fk_per_person_per_race` (`id_race` ASC) ,
  CONSTRAINT `fk_per_person_per_marital_status`
    FOREIGN KEY (`id_marital_status` )
    REFERENCES `per_marital_status` (`id_marital_status` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_person_per_nationality`
    FOREIGN KEY (`id_nationality` )
    REFERENCES `per_nationality` (`id_nationality` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_person_per_race`
    FOREIGN KEY (`id_race` )
    REFERENCES `per_race` (`id_race` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos dados cadastrais das pessoas' ;

-- -----------------------------------------------------
-- Definition of table `per_age_group`
-- -----------------------------------------------------
CREATE TABLE `per_age_group` (
  `id_age_group` int(10) NOT NULL AUTO_INCREMENT,
  `begin_age` int(10) NOT NULL,
  `end_age` int(10) NOT NULL,
  `status` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_age_group`)
) 
ENGINE=InnoDB 
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das faixas etárias disnponíveis no sistema' ;

-- -----------------------------------------------------
-- Definition of table `ast_assistance`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ast_assistance` (
  `id_assistance` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_program` INT(10) UNSIGNED NOT NULL ,
  `id_user` INT(10) UNSIGNED NOT NULL ,
  `beginning_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `end_date_prevision` DATE NULL DEFAULT NULL ,
  `real_end_date` TIMESTAMP NULL DEFAULT NULL ,
  `confidentiality` TINYINT(4) NOT NULL ,
  PRIMARY KEY (`id_assistance`) ,
  INDEX `fk_ass_assistance_ass_program` (`id_program` ASC) ,
  INDEX `fk_ass_assistance_per_person` (`id_person` ASC) ,
  INDEX `fk_atendimento_login` (`id_user` ASC) ,
  CONSTRAINT `fk_assistance_user_login`
    FOREIGN KEY (`id_user` )
    REFERENCES `auth_user` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ass_assistance_ass_program`
    FOREIGN KEY (`id_program` )
    REFERENCES `ast_program` (`id_program` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ass_assistance_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do atendimento de uma pessoa, onde serao informadas' ;


-- -----------------------------------------------------
-- Definition of table `act_class_assistance`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `act_class_assistance` (
  `id_class` INT(10) UNSIGNED NOT NULL ,
  `id_assistance` INT(10) UNSIGNED NOT NULL ,
  `id_status` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_class`, `id_assistance`) ,
  INDEX `fk_act_class_assistance_act_status_class` (`id_status` ASC) ,
  INDEX `fk_act_class_assistance_ass_assistance` (`id_assistance` ASC) ,
  CONSTRAINT `fk_act_class_assistance_act_class`
    FOREIGN KEY (`id_class` )
    REFERENCES `act_class` (`id_class` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_act_class_assistance_act_status_class`
    FOREIGN KEY (`id_status` )
    REFERENCES `act_status_class` (`id_status` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_act_class_assistance_ass_assistance`
    FOREIGN KEY (`id_assistance` )
    REFERENCES `ast_assistance` (`id_assistance` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vinculo entre o atendimento e as turmas disponibilizadas pel' ;


-- -----------------------------------------------------
-- Definition of table `gas_general_assistance`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `gas_general_assistance` (
  `id_assistance` INT(10) UNSIGNED NOT NULL ,
  `id_general_assistance` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `assistance_comment` TEXT NOT NULL ,
  `register_data` DATE NOT NULL ,
  `confidentiality` TINYINT(4) NOT NULL ,
  PRIMARY KEY (`id_general_assistance`) ,
  INDEX `fk_gas_general_assistance_ass_assistance` (`id_assistance` ASC) ,
  CONSTRAINT `fk_gas_general_assistance_ass_assistance`
    FOREIGN KEY (`id_assistance` )
    REFERENCES `ast_assistance` (`id_assistance` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro de atendimentos  diferentes da complementacao, prof' ;


-- -----------------------------------------------------
-- Definition of table `auth_profile`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_profile` (
  `id_profile` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `profile` VARCHAR(90) NULL DEFAULT NULL ,
  `active` TINYINT(4) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_profile`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Perfis' ;


-- -----------------------------------------------------
-- Definition of table `ast_assistance_profile`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ast_assistance_profile` (
  `id_general_assistance` INT(10) UNSIGNED NOT NULL ,
  `id_profile` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_general_assistance`, `id_profile`) ,
  INDEX `profile_assistance_FK` (`id_profile` ASC) ,
  CONSTRAINT `assistance_profile_FK`
    FOREIGN KEY (`id_general_assistance` )
    REFERENCES `gas_general_assistance` (`id_general_assistance` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `profile_assistance_FK`
    FOREIGN KEY (`id_profile` )
    REFERENCES `auth_profile` (`id_profile` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `auth_group_entity`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_group_entity` (
  `id_entity` INT(10) UNSIGNED NOT NULL ,
  `id_group` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_entity`, `id_group`) ,
  INDEX `group_entity_FK` (`id_group` ASC) ,
  CONSTRAINT `entity_group_FK`
    FOREIGN KEY (`id_entity` )
    REFERENCES `ent_entity` (`id_entity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `group_entity_FK`
    FOREIGN KEY (`id_group` )
    REFERENCES `auth_group` (`id_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `auth_resource`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_resource` (
  `id_resource` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `controller_name` VARCHAR(25) NOT NULL ,
  `resource_type` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_resource`) )
ENGINE = InnoDB
AUTO_INCREMENT = 36
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `auth_role_resource`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_role_resource` (
  `id_resource` INT(10) UNSIGNED NOT NULL ,
  `id_role` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_resource`, `id_role`) ,
  INDEX `role_resource_FK` (`id_role` ASC) ,
  CONSTRAINT `resource_role_FK`
    FOREIGN KEY (`id_resource` )
    REFERENCES `auth_resource` (`id_resource` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `role_resource_FK`
    FOREIGN KEY (`id_role` )
    REFERENCES `auth_role` (`id_role` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `auth_group_resource`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_group_resource` (
  `role_resource_id_role` INT(10) UNSIGNED NOT NULL ,
  `role_resource_id_resource` INT(10) UNSIGNED NOT NULL ,
  `id_group` INT(10) UNSIGNED NOT NULL ,
  `readonly` TINYINT(4) NOT NULL ,
  `change_confidentiality` TINYINT(4) NOT NULL ,
  `default_confidentiality` TINYINT(4) NULL DEFAULT NULL ,
  PRIMARY KEY (`role_resource_id_role`, `role_resource_id_resource`, `id_group`) ,
  INDEX `group_resource_FK` (`id_group` ASC) ,
  INDEX `role_resource_group_FK` (`role_resource_id_role` ASC, `role_resource_id_resource` ASC) ,
  CONSTRAINT `group_resource_FK`
    FOREIGN KEY (`id_group` )
    REFERENCES `auth_group` (`id_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `role_resource_group_FK`
    FOREIGN KEY (`role_resource_id_role` , `role_resource_id_resource` )
    REFERENCES `auth_role_resource` (`id_role` , `id_resource` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `auth_user_profile`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `auth_user_profile` (
  `id_profile` INT(10) UNSIGNED NOT NULL ,
  `id_user` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_profile`, `id_user`) ,
  INDEX `user_profile_FK` (`id_user` ASC) ,
  CONSTRAINT `profile_user_FK`
    FOREIGN KEY (`id_profile` )
    REFERENCES `auth_profile` (`id_profile` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_profile_FK`
    FOREIGN KEY (`id_user` )
    REFERENCES `auth_user` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `con_address_nickname`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_address_nickname` (
  `id_address` INT(10) UNSIGNED NOT NULL ,
  `id_nickname` INT(10) UNSIGNED NOT NULL ,
  `nickname` VARCHAR(72) NOT NULL ,
  `nickname_metafone` VARCHAR(72) NOT NULL ,
  PRIMARY KEY (`id_address`, `id_nickname`) ,
  INDEX `fk_con_address_nickname_con_address` (`id_address` ASC) ,
  CONSTRAINT `fk_con_address_nickname_con_address`
    FOREIGN KEY (`id_address` )
    REFERENCES `con_address` (`id_address` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos possiveis apelidos que o logradouro pode ter' ;


-- -----------------------------------------------------
-- Definition of table `con_region`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_region` (
  `id_region` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `region` VARCHAR(72) NOT NULL ,
  `region_img` VARCHAR(200) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_region`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das regioes da cidade possibilitando o agrupamento ' ;


-- -----------------------------------------------------
-- Definition of table `con_neighborhood_region`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_neighborhood_region` (
  `id_neighborhood` INT(10) UNSIGNED NOT NULL ,
  `id_region` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_neighborhood`, `id_region`) ,
  INDEX `fk_con_neighborhood_region_con_region` (`id_region` ASC) ,
  CONSTRAINT `fk_con_neighborhood_region_con_neighborhood`
    FOREIGN KEY (`id_neighborhood` )
    REFERENCES `con_neighborhood` (`id_neighborhood` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_con_neighborhood_region_con_region`
    FOREIGN KEY (`id_region` )
    REFERENCES `con_region` (`id_region` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vinculo dos bairros do municipio com as regioes' ;


-- -----------------------------------------------------
-- Definition of table `con_telephone_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_telephone_type` (
  `id_telephone` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `telephone` VARCHAR(30) NULL DEFAULT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_telephone`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos tipos de telefone disponiveis no sistema (resid' ;


-- -----------------------------------------------------
-- Definition of table `con_telephone_number`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `con_telephone_number` (
  `id_telephone_number` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_telephone_type` INT(10) UNSIGNED NOT NULL ,
  `ddd` INT(11) NOT NULL DEFAULT '11' ,
  `number` VARCHAR(30) NOT NULL COMMENT 'retirar todos caracteres estranhos, espacos, etc, deixando apenas os numeros' ,
  PRIMARY KEY (`id_telephone_number`) ,
  UNIQUE INDEX `telefone_id_key` (`id_telephone_number` ASC) ,
  INDEX `fk_con_telephone_number_con_telephone_type` (`id_telephone_type` ASC) ,
  CONSTRAINT `fk_con_telephone_number_con_telephone_type`
    FOREIGN KEY (`id_telephone_type` )
    REFERENCES `con_telephone_type` (`id_telephone` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro de todos os telefones do sistema, que podem ser uti' ;


-- -----------------------------------------------------
-- Definition of table `cov_coverage_health_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `cov_coverage_health_type` (
  `id_coverage_health` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `coverage_health` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_coverage_health`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos tipos de cobertura de saude disponiveis no muni' ;


-- -----------------------------------------------------
-- Definition of table `cov_ubs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `cov_ubs` (
  `id_ubs` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_coverage_health` INT(10) UNSIGNED NOT NULL ,
  `ubs_name` VARCHAR(90) NOT NULL ,
  `number` INT(10) UNSIGNED NOT NULL ,
  `id_address` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_ubs`) ,
  INDEX `fk_cov_ubs_con_address` (`id_address` ASC) ,
  INDEX `fk_cov_ubs_cov_coverage_health_type` (`id_coverage_health` ASC) ,
  CONSTRAINT `fk_cov_ubs_con_address`
    FOREIGN KEY (`id_address` )
    REFERENCES `con_address` (`id_address` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cov_ubs_cov_coverage_health_type`
    FOREIGN KEY (`id_coverage_health` )
    REFERENCES `cov_coverage_health_type` (`id_coverage_health` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das Unidades Basicas de Saude que atendem por bairr' ;


-- -----------------------------------------------------
-- Definition of table `res_building_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_building_type` (
  `id_building` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `building` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_building`) )
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do tipo de construcao do domicilio (taipa, adobe, t' ;


-- -----------------------------------------------------
-- Definition of table `res_illumination_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_illumination_type` (
  `id_illumination` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `illumination` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_illumination`) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do tipo de iluminacao do domicilio (relogio proprio' ;


-- -----------------------------------------------------
-- Definition of table `res_locality_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_locality_type` (
  `id_locality` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `place` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_locality`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do tipo de domicilio, rural ou urbano' ;


-- -----------------------------------------------------
-- Definition of table `res_morada_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_morada_type` (
  `id_morada` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `morada` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_morada`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do tipo de moraria(casa, apartamento, comodo, etc)' ;


-- -----------------------------------------------------
-- Definition of table `res_sanitary_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_sanitary_type` (
  `id_sanitary` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `sanitary` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_sanitary`) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro tipo de escoamento sanitario do domicilio (rede pub' ;


-- -----------------------------------------------------
-- Definition of table `res_status_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_status_type` (
  `id_status` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `status_type` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_status`) )
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da situacao do domicilio (proprio, alugado, invasao' ;


-- -----------------------------------------------------
-- Definition of table `res_supply_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_supply_type` (
  `id_supply` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `supply` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_supply`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do tipo de abastecimento de agua (rede publica, nas' ;


-- -----------------------------------------------------
-- Definition of table `res_trash_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_trash_type` (
  `id_trash` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `trash` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_trash`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do destino do lixo do domicilio (coleta, queimado, ' ;


-- -----------------------------------------------------
-- Definition of table `res_water_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_water_type` (
  `id_water` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `water` VARCHAR(30) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_water`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do tipo de tratamento da agua da moradia (filtracao' ;


-- -----------------------------------------------------
-- Definition of table `res_residence`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_residence` (
  `id_residence` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_address` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `number` INT(11) NULL DEFAULT NULL ,
  `complement` VARCHAR(72) NULL DEFAULT NULL ,
  `reference_point` VARCHAR(72) NULL DEFAULT NULL ,
  `accommodation` INT(11) NULL DEFAULT NULL ,
  `id_morada` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_status` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_locality` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_building` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_supply` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_water` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_illumination` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_sanitary` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_trash` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_residence`) ,
  INDEX `fk_res_residence_con_address` (`id_address` ASC) ,
  INDEX `fk_res_residence_res_building_type` (`id_building` ASC) ,
  INDEX `fk_res_residence_res_illumination_type` (`id_illumination` ASC) ,
  INDEX `fk_res_residence_res_locality_type` (`id_locality` ASC) ,
  INDEX `fk_res_residence_res_morada_type` (`id_morada` ASC) ,
  INDEX `fk_res_residence_res_sanitary_type` (`id_sanitary` ASC) ,
  INDEX `fk_res_residence_res_status_type` (`id_status` ASC) ,
  INDEX `fk_res_residence_res_supply_type` (`id_supply` ASC) ,
  INDEX `fk_res_residence_res_trash_type` (`id_trash` ASC) ,
  INDEX `fk_res_residence_res_water_type` (`id_water` ASC) ,
  CONSTRAINT `fk_res_residence_con_address`
    FOREIGN KEY (`id_address` )
    REFERENCES `con_address` (`id_address` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_building_type`
    FOREIGN KEY (`id_building` )
    REFERENCES `res_building_type` (`id_building` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_illumination_type`
    FOREIGN KEY (`id_illumination` )
    REFERENCES `res_illumination_type` (`id_illumination` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_locality_type`
    FOREIGN KEY (`id_locality` )
    REFERENCES `res_locality_type` (`id_locality` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_morada_type`
    FOREIGN KEY (`id_morada` )
    REFERENCES `res_morada_type` (`id_morada` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_sanitary_type`
    FOREIGN KEY (`id_sanitary` )
    REFERENCES `res_sanitary_type` (`id_sanitary` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_status_type`
    FOREIGN KEY (`id_status` )
    REFERENCES `res_status_type` (`id_status` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_supply_type`
    FOREIGN KEY (`id_supply` )
    REFERENCES `res_supply_type` (`id_supply` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_trash_type`
    FOREIGN KEY (`id_trash` )
    REFERENCES `res_trash_type` (`id_trash` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_water_type`
    FOREIGN KEY (`id_water` )
    REFERENCES `res_water_type` (`id_water` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das caracteristicas de cada domicilio' ;


-- -----------------------------------------------------
-- Definition of table `cov_coverage_address`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `cov_coverage_address` (
  `id_ubs` INT(10) UNSIGNED NOT NULL ,
  `id_residence` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_ubs`, `id_residence`) ,
  INDEX `fk_cov_coverage_address_cov_ubs` (`id_ubs` ASC) ,
  INDEX `fk_cov_coverage_address_res_residence` (`id_residence` ASC) ,
  CONSTRAINT `fk_cov_coverage_address_cov_ubs`
    FOREIGN KEY (`id_ubs` )
    REFERENCES `cov_ubs` (`id_ubs` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cov_coverage_address_res_residence`
    FOREIGN KEY (`id_residence` )
    REFERENCES `res_residence` (`id_residence` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vincular as unidades de saude com os logradouros do municipi' ;


-- -----------------------------------------------------
-- Definition of table `csg_consanguine_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `csg_consanguine_type` (
  `id_consanguine_type` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_consanguine_type`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `csg_consanguine`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `csg_consanguine` (
  `id_person_from` INT(10) UNSIGNED NOT NULL ,
  `id_consanguine_type` INT(10) UNSIGNED NOT NULL ,
  `id_person_to` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_person_to`, `id_person_from`) ,
  INDEX `fk_consanguine_consanguine_type` (`id_consanguine_type` ASC) ,
  INDEX `fk_csg_consanguine_per_person_from` (`id_person_from` ASC) ,
  CONSTRAINT `fk_csg_consanguine_csg_consanguine_type`
    FOREIGN KEY (`id_consanguine_type` )
    REFERENCES `csg_consanguine_type` (`id_consanguine_type` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_csg_consanguine_per_person_from`
    FOREIGN KEY (`id_person_from` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_csg_consanguine_per_person_to`
    FOREIGN KEY (`id_person_to` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `eas_lawsuit_origin`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `eas_lawsuit_origin` (
  `id_lawsuit_origin` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `lawsuit_origin` VARCHAR(40) NOT NULL ,
  PRIMARY KEY (`id_lawsuit_origin`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da origem do processo de encaminhamento pelo juiz p' ;


-- -----------------------------------------------------
-- Definition of table `eas_official_letter_origin`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `eas_official_letter_origin` (
  `id_official_letter_origin` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `official_letter_origin` VARCHAR(40) NOT NULL ,
  PRIMARY KEY (`id_official_letter_origin`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da origem do oficio de encaminhamento emitido pelo ' ;


-- -----------------------------------------------------
-- Definition of table `eas_especial_assistance`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `eas_especial_assistance` (
  `id_assistance` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_official_letter_origin` INT(10) UNSIGNED NOT NULL ,
  `id_lawsuit_origin` INT(10) UNSIGNED NOT NULL ,
  `official_letter_number` INT(11) NULL DEFAULT NULL ,
  `official_letter_year` INT(11) NULL DEFAULT NULL ,
  `lawsuit_number` INT(11) NULL DEFAULT NULL ,
  `lawsuit_year` INT(11) NULL DEFAULT NULL ,
  `lawsuit_detail` TEXT NULL DEFAULT NULL ,
  `ruling` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id_assistance`) ,
  INDEX `fk_eas_especial_assistance_eas_lawsuit_origin` (`id_lawsuit_origin` ASC) ,
  INDEX `fk_eas_especial_assistance_eas_official_letter_origin` (`id_official_letter_origin` ASC) ,
  CONSTRAINT `fk_eas_especial_assistance_ast_assistance`
    FOREIGN KEY (`id_assistance` )
    REFERENCES `ast_assistance` (`id_assistance` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_eas_especial_assistance_eas_lawsuit_origin`
    FOREIGN KEY (`id_lawsuit_origin` )
    REFERENCES `eas_lawsuit_origin` (`id_lawsuit_origin` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_eas_especial_assistance_eas_official_letter_origin`
    FOREIGN KEY (`id_official_letter_origin` )
    REFERENCES `eas_official_letter_origin` (`id_official_letter_origin` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro de casos especiais, usado nos casos de PSC, LA, SEM' ;


-- -----------------------------------------------------
-- Definition of table `edu_degree_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `edu_degree_type` (
  `id_degree` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `degree` VARCHAR(70) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_degree`) )
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos graus de instrucao possiveis.' ;


-- -----------------------------------------------------
-- Definition of table `edu_level_instruction`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `edu_level_instruction` (
  `id_level_instruction` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_degree` INT(10) UNSIGNED NOT NULL ,
  `last_year_studied` INT(11) NULL DEFAULT NULL ,
  `status` CHAR(1) NULL DEFAULT NULL ,
  `last_month_studied` INT(11) NULL DEFAULT NULL ,
  `date_collected` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id_level_instruction`) ,
  INDEX `fk_edu_level_instruction_edu_degree_type` (`id_degree` ASC) ,
  INDEX `fk_edu_level_instruction_per_person` (`id_person` ASC) ,
  CONSTRAINT `fk_edu_level_instruction_edu_degree_type`
    FOREIGN KEY (`id_degree` )
    REFERENCES `edu_degree_type` (`id_degree` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_level_instruction_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do grau de instrucao da pessoa' ;


-- -----------------------------------------------------
-- Definition of table `edu_period_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `edu_period_type` (
  `id_period` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `period` VARCHAR(16) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_period`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos periodos da aulas disponiveis no municipio (man' ;


-- -----------------------------------------------------
-- Definition of table `edu_school_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `edu_school_type` (
  `id_school_type` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `school_type` VARCHAR(40) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_school_type`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos tipos de escola (medio, fundamental,etc)' ;


-- -----------------------------------------------------
-- Definition of table `edu_school`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `edu_school` (
  `id_school` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `inep` VARCHAR(8) NOT NULL ,
  `name` VARCHAR(120) NOT NULL ,
  `id_school_type` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id_school`) ,
  INDEX `fk_edu_school_edu_school_type` (`id_school_type` ASC) ,
  CONSTRAINT `fk_edu_school_edu_school_type`
    FOREIGN KEY (`id_school_type` )
    REFERENCES `edu_school_type` (`id_school_type` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das escolas do municipio' ;


-- -----------------------------------------------------
-- Definition of table `edu_school_year_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `edu_school_year_type` (
  `id_school_year` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `school_year` VARCHAR(70) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_school_year`) )
ENGINE = InnoDB
AUTO_INCREMENT = 19
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das series/anos escolares' ;


-- -----------------------------------------------------
-- Definition of table `edu_registration`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `edu_registration` (
  `id_registration` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_level_instruction` INT(10) UNSIGNED NOT NULL ,
  `id_school_year` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_period` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_school` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `status` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_registration`) ,
  INDEX `fk_edu_registration_edu_level_instruction` (`id_level_instruction` ASC) ,
  INDEX `fk_edu_registration_edu_period_type` (`id_period` ASC) ,
  INDEX `fk_edu_registration_edu_school` (`id_school` ASC) ,
  INDEX `fk_edu_registration_edu_school_year_type` (`id_school_year` ASC) ,
  CONSTRAINT `fk_edu_registration_edu_level_instruction`
    FOREIGN KEY (`id_level_instruction` )
    REFERENCES `edu_level_instruction` (`id_level_instruction` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_registration_edu_period_type`
    FOREIGN KEY (`id_period` )
    REFERENCES `edu_period_type` (`id_period` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_registration_edu_school`
    FOREIGN KEY (`id_school` )
    REFERENCES `edu_school` (`id_school` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_registration_edu_school_year_type`
    FOREIGN KEY (`id_school_year` )
    REFERENCES `edu_school_year_type` (`id_school_year` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do vinculo entre a escola e a pessoa. Tem caracteri' ;


-- -----------------------------------------------------
-- Definition of table `emp_employment_status_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `emp_employment_status_type` (
  `id_employment_status` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `employment_status` VARCHAR(70) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_employment_status`) )
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da situacao do emprego da pessoa (formal, informal,' ;


-- -----------------------------------------------------
-- Definition of table `emp_income_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `emp_income_type` (
  `id_income` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `income` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_income`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos tipos de renda que a pessoa pode receber (empre' ;


-- -----------------------------------------------------
-- Definition of table `emp_income`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `emp_income` (
  `id_emp_income` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_income` INT(10) UNSIGNED NOT NULL ,
  `register_date` DATE NOT NULL ,
  `value` FLOAT NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_emp_income`) ,
  INDEX `fk_emp_income_emp_income_type` (`id_income` ASC) ,
  INDEX `fk_emp_income_per_person` (`id_person` ASC) ,
  CONSTRAINT `fk_emp_income_emp_income_type`
    FOREIGN KEY (`id_income` )
    REFERENCES `emp_income_type` (`id_income` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emp_income_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da renda da pessoa.' ;


-- -----------------------------------------------------
-- Definition of table `emp_employment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `emp_employment` (
  `id_employment` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_employment_status` INT(10) UNSIGNED NOT NULL ,
  `id_address` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_emp_income` INT(10) UNSIGNED NOT NULL ,
  `company_name` VARCHAR(80) NULL DEFAULT NULL ,
  `start_date` DATE NULL DEFAULT NULL ,
  `end_date` DATE NULL DEFAULT NULL ,
  `number` INT(11) NULL DEFAULT NULL ,
  `complement` VARCHAR(72) NULL DEFAULT NULL ,
  `reference_point` VARCHAR(72) NULL DEFAULT NULL ,
  `occupation` VARCHAR(32) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_employment`) ,
  INDEX `fk_emp_employment_emp_employment_status_type` (`id_employment_status` ASC) ,
  INDEX `fk_emp_employment_emp_income` (`id_emp_income` ASC) ,
  INDEX `fk_emp_employment_con_address` (`id_address` ASC) ,
  CONSTRAINT `fk_emp_employment_con_address`
    FOREIGN KEY (`id_address` )
    REFERENCES `con_address` (`id_address` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emp_employment_emp_employment_status_type`
    FOREIGN KEY (`id_employment_status` )
    REFERENCES `emp_employment_status_type` (`id_employment_status` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emp_employment_emp_income`
    FOREIGN KEY (`id_emp_income` )
    REFERENCES `emp_income` (`id_emp_income` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos dados relativos ao emprego da pessoa' ;


-- -----------------------------------------------------
-- Definition of table `emp_employment_telephone`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `emp_employment_telephone` (
  `id_employment` INT(10) UNSIGNED NOT NULL ,
  `id_telephone` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_employment`, `id_telephone`) ,
  INDEX `fk_emp_employment_telephone_con_telephone_number` (`id_telephone` ASC) ,
  CONSTRAINT `fk_emp_employment_telephone_con_telephone_number`
    FOREIGN KEY (`id_telephone` )
    REFERENCES `con_telephone_number` (`id_telephone_number` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emp_employment_telephone_emp_employment`
    FOREIGN KEY (`id_employment` )
    REFERENCES `emp_employment` (`id_employment` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vincular telefones ao emprego' ;


-- -----------------------------------------------------
-- Definition of table `ent_entity_area_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ent_entity_area_type` (
  `id_entity_area` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entity_area` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_entity_area`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das areas de atuacao das entidades com base nos pri' ;


-- -----------------------------------------------------
-- Definition of table `ent_entity_area`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ent_entity_area` (
  `id_entity` INT(10) UNSIGNED NOT NULL ,
  `id_entity_area` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_entity_area`, `id_entity`) ,
  INDEX `fk_ent_entity_area_ent_entity` (`id_entity` ASC) ,
  CONSTRAINT `fk_ent_entity_area_ent_entity`
    FOREIGN KEY (`id_entity` )
    REFERENCES `ent_entity` (`id_entity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ent_entity_area_ent_entity_area_type`
    FOREIGN KEY (`id_entity_area` )
    REFERENCES `ent_entity_area_type` (`id_entity_area` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vincular a entidade com as areas de atuacao do ECA' ;


-- -----------------------------------------------------
-- Definition of table `ent_entity_classification_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ent_entity_classification_type` (
  `id_entity_classification` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entity_classification` VARCHAR(150) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_entity_classification`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das classificacoes possiveis das entidades (creche,' ;


-- -----------------------------------------------------
-- Definition of table `ent_entity_classification`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ent_entity_classification` (
  `id_entity` INT(10) UNSIGNED NOT NULL ,
  `id_entity_classification` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_entity`, `id_entity_classification`) ,
  INDEX `fk_ent_entity_classification_ent_entity_classification_type` (`id_entity_classification` ASC) ,
  CONSTRAINT `fk_ent_entity_classification_ent_entity`
    FOREIGN KEY (`id_entity` )
    REFERENCES `ent_entity` (`id_entity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ent_entity_classification_ent_entity_classification_type`
    FOREIGN KEY (`id_entity_classification` )
    REFERENCES `ent_entity_classification_type` (`id_entity_classification` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vincular a entidade com as classificacoes cadastradas' ;


-- -----------------------------------------------------
-- Definition of table `ent_entity_group_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ent_entity_group_type` (
  `id_entity_group` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entity_group` VARCHAR(200) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_entity_group`) )
ENGINE = InnoDB
AUTO_INCREMENT = 18
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da entidade \"Matriz\" no caso de agrupamento de enti' ;


-- -----------------------------------------------------
-- Definition of table `ent_entity_group`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ent_entity_group` (
  `id_entity` INT(10) UNSIGNED NOT NULL ,
  `id_entity_group` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_entity_group`, `id_entity`) ,
  INDEX `fk_ent_entity_group_ent_entity` (`id_entity` ASC) ,
  CONSTRAINT `fk_ent_entity_group_ent_entity`
    FOREIGN KEY (`id_entity` )
    REFERENCES `ent_entity` (`id_entity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ent_entity_group_ent_entity_group_type`
    FOREIGN KEY (`id_entity_group` )
    REFERENCES `ent_entity_group_type` (`id_entity_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vincular entidades \"matriz e filiais\"' ;


-- -----------------------------------------------------
-- Definition of table `ent_entity_telephone`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `ent_entity_telephone` (
  `id_entity` INT(10) UNSIGNED NOT NULL ,
  `id_telephone` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_entity`, `id_telephone`) ,
  INDEX `fk_ent_entity_telephone_con_telephone_number` (`id_telephone` ASC) ,
  CONSTRAINT `fk_ent_entity_telephone_con_telephone_number`
    FOREIGN KEY (`id_telephone` )
    REFERENCES `con_telephone_number` (`id_telephone_number` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ent_entity_telephone_ent_entity`
    FOREIGN KEY (`id_entity` )
    REFERENCES `ent_entity` (`id_entity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vincular telefones com a entidade' ;


-- -----------------------------------------------------
-- Definition of table `exp_expense_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `exp_expense_type` (
  `id_expense` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `expense` VARCHAR(90) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_expense`) )
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro das despesas da familia: aluguel, prestacao habitac' ;


-- -----------------------------------------------------
-- Definition of table `fam_family_id`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `fam_family_id` (
  `id_family` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id_family`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `exp_expense`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `exp_expense` (
  `id_expense` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_expense_type` INT(10) UNSIGNED NOT NULL ,
  `id_family` INT(10) UNSIGNED NOT NULL ,
  `expense_value` FLOAT NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_expense`) ,
  INDEX `fk_exp_expense_exp_expense_type` (`id_expense_type` ASC) ,
  INDEX `fk_exp_expense_fam_family` (`id_family` ASC) ,
  CONSTRAINT `fk_exp_expense_exp_expense_type`
    FOREIGN KEY (`id_expense_type` )
    REFERENCES `exp_expense_type` (`id_expense` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exp_expense_fam_family_id`
    FOREIGN KEY (`id_family` )
    REFERENCES `fam_family_id` (`id_family` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do valor de cada despesa da familia' ;


-- -----------------------------------------------------
-- Definition of table `fam_kinship_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `fam_kinship_type` (
  `id_kinship` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `kinship` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_kinship`) )
ENGINE = InnoDB
AUTO_INCREMENT = 21
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do vinculo de parentesco entre a pessoa e seu repre' ;


-- -----------------------------------------------------
-- Definition of table `fam_family`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `fam_family` (
  `id_family` INT(10) UNSIGNED NOT NULL ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_kinship` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_kinship`, `id_person`, `id_family`) ,
  INDEX `fk_fam_family_fam_family_id` (`id_family` ASC) ,
  INDEX `fk_formacao_familia` (`id_person` ASC) ,
  INDEX `fk_formacao_familia_tipo_parentesco` (`id_kinship` ASC) ,
  CONSTRAINT `fk_fam_family_fam_family_id`
    FOREIGN KEY (`id_family` )
    REFERENCES `fam_family_id` (`id_family` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_formacao_familia`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_formacao_familia_tipo_parentesco`
    FOREIGN KEY (`id_kinship` )
    REFERENCES `fam_kinship_type` (`id_kinship` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vinculo entre a pessoa, seu parentesco em relacao ao represe' ;


-- -----------------------------------------------------
-- Definition of table `fam_representative`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `fam_representative` (
  `id_representative` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_family` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_representative`) ,
  INDEX `fk_fam_representative_fam_family` (`id_family` ASC) ,
  INDEX `fk_fam_representative_per_person` (`id_person` ASC) ,
  CONSTRAINT `fk_fam_representative_fam_family_id`
    FOREIGN KEY (`id_family` )
    REFERENCES `fam_family_id` (`id_family` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fam_representative_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do representante legal da familia' ;


-- -----------------------------------------------------
-- Definition of table `gas_assistance_benefit_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `gas_assistance_benefit_type` (
  `id_assistance_benefit_type` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(72) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_assistance_benefit_type`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `gas_assistance_benefit`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `gas_assistance_benefit` (
  `id_assistance` INT(10) UNSIGNED NOT NULL ,
  `id_general_assistance` INT(10) UNSIGNED NOT NULL ,
  `id_assistance_benefit_type` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_assistance`, `id_general_assistance`, `id_assistance_benefit_type`) ,
  INDEX `fk_gas_assitance_benefit_gas_assistance_benefit_type` (`id_assistance_benefit_type` ASC) ,
  CONSTRAINT `fk_gas_assitance_benefit_gas_assistance_benefit_type`
    FOREIGN KEY (`id_assistance_benefit_type` )
    REFERENCES `gas_assistance_benefit_type` (`id_assistance_benefit_type` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gas_assitance_benefit_gas_general_assistance`
    FOREIGN KEY (`id_assistance` , `id_general_assistance` )
    REFERENCES `gas_general_assistance` (`id_assistance` , `id_general_assistance` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Relacao de beneifico que podem ser oferecidos' ;


-- -----------------------------------------------------
-- Definition of table `hlt_framework_health_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_framework_health_type` (
  `id_framework_health` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `framework_health` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_framework_health`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro do quadro de saude onde sera registrada as situacoe' ;


-- -----------------------------------------------------
-- Definition of table `hlt_health_plan`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_health_plan` (
  `id_health_plan` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_health_plan`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `hlt_health`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_health` (
  `id_health` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `drug_user` TINYINT(4) NOT NULL ,
  `vaccine` TINYINT(4) NOT NULL ,
  `vaccine_to_date` DATE NULL DEFAULT NULL ,
  `health_plan` VARCHAR(90) NULL DEFAULT NULL ,
  `status` CHAR(1) NULL DEFAULT NULL ,
  `id_health_plan` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `prontuary` VARCHAR(100) NULL DEFAULT NULL ,
  `id_entity` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id_health`) ,
  INDEX `fk_hlt_health_per_person` (`id_person` ASC) ,
  INDEX `FK_hlt_health_2` (`id_health_plan` ASC) ,
  INDEX `FK_hlt_health_3` (`id_entity` ASC) ,
  CONSTRAINT `FK_hlt_health_2`
    FOREIGN KEY (`id_health_plan` )
    REFERENCES `hlt_health_plan` (`id_health_plan` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_hlt_health_3`
    FOREIGN KEY (`id_entity` )
    REFERENCES `ent_entity` (`id_entity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hlt_health_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro de alguns dados relativos a saude da pessoa' ;


-- -----------------------------------------------------
-- Definition of table `hlt_framework_health`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_framework_health` (
  `id_health` INT(10) UNSIGNED NOT NULL ,
  `id_framework_health` INT(10) UNSIGNED NOT NULL ,
  `framework_health_description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id_health`, `id_framework_health`) ,
  INDEX `fk_hlt_framework_health_hlt_framework_health_type` (`id_framework_health` ASC) ,
  CONSTRAINT `fk_hlt_framework_health_hlt_framework_health_type`
    FOREIGN KEY (`id_framework_health` )
    REFERENCES `hlt_framework_health_type` (`id_framework_health` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hlt_framework_health_hlt_health`
    FOREIGN KEY (`id_health` )
    REFERENCES `hlt_health` (`id_health` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da descricao da situacao de saude da pessoa que nao' ;


-- -----------------------------------------------------
-- Definition of table `hlt_period`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_period` (
  `id_period` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(100) NOT NULL ,
  `status` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_period`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `hlt_pregnancy`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_pregnancy` (
  `id_pregnancy` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `prenatal_sis` VARCHAR(10) NULL DEFAULT NULL ,
  `beginning_pregnancy` DATE NULL DEFAULT NULL ,
  `met` TINYINT(4) NOT NULL ,
  `status` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_pregnancy`) ,
  INDEX `fk_hlt_pregnancy_per_person` (`id_person` ASC) ,
  CONSTRAINT `fk_hlt_pregnancy_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos dados referentes a gestacao' ;


-- -----------------------------------------------------
-- Definition of table `hlt_vaccine_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_vaccine_type` (
  `id_vaccine_type` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(100) NOT NULL ,
  `status` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_vaccine_type`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `hlt_vaccine`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_vaccine` (
  `id_vaccine` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `id_vaccine_type` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_period` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_vaccine`) ,
  INDEX `FK_hlt_vaccine_1` (`id_vaccine_type` ASC) ,
  INDEX `FK_hlt_vaccine_2` (`id_period` ASC) ,
  CONSTRAINT `FK_hlt_vaccine_1`
    FOREIGN KEY (`id_vaccine_type` )
    REFERENCES `hlt_vaccine_type` (`id_vaccine_type` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_hlt_vaccine_2`
    FOREIGN KEY (`id_period` )
    REFERENCES `hlt_period` (`id_period` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `hlt_vaccination`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `hlt_vaccination` (
  `id_vaccination` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_vaccine` INT(10) UNSIGNED NOT NULL ,
  `date` DATETIME NOT NULL ,
  `lot` VARCHAR(100) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_vaccination`) ,
  INDEX `FK_hlt_vaccination_1` (`id_person` ASC) ,
  INDEX `FK_hlt_vaccination_2` (`id_vaccine` ASC) ,
  CONSTRAINT `FK_hlt_vaccination_1`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_hlt_vaccination_2`
    FOREIGN KEY (`id_vaccine` )
    REFERENCES `hlt_vaccine` (`id_vaccine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `per_civil_certificate`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_civil_certificate` (
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_uf` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `certificate_type` INT(11) NULL DEFAULT NULL ,
  `term` INT(11) NULL DEFAULT NULL ,
  `book` INT(11) NULL DEFAULT NULL ,
  `leaf` INT(11) NULL DEFAULT NULL ,
  `emission_date` DATE NULL DEFAULT NULL ,
  `registry_office_name` VARCHAR(100) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_person`) ,
  INDEX `certidaocivil_termo_key` (`term` ASC, `leaf` ASC, `book` ASC) ,
  INDEX `fk_per_civil_certificate_con_uf` (`id_uf` ASC) ,
  CONSTRAINT `fk_per_civil_certificate_con_uf`
    FOREIGN KEY (`id_uf` )
    REFERENCES `con_uf` (`id_uf` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_civil_certificate_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos dados de certidao de civil da pessoa' ;


-- -----------------------------------------------------
-- Definition of table `per_ctps`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_ctps` (
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_uf` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `number` INT(11) NULL DEFAULT NULL ,
  `series` INT(11) NULL DEFAULT NULL ,
  `emission_date` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id_person`) ,
  INDEX `fk_per_ctps_con_uf` (`id_uf` ASC) ,
  CONSTRAINT `fk_per_ctps_con_uf`
    FOREIGN KEY (`id_uf` )
    REFERENCES `con_uf` (`id_uf` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_ctps_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos dados relativos a carteira profissional da pess' ;


-- -----------------------------------------------------
-- Definition of table `per_deficiency_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_deficiency_type` (
  `id_deficiency` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_deficiency`) )
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos tipos deficiencias  (cegueira, surdez, etc)' ;


-- -----------------------------------------------------
-- Definition of table `per_deficiency`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_deficiency` (
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_deficiency` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_person`, `id_deficiency`) ,
  INDEX `fk_per_deficiency_per_deficiency_type` (`id_deficiency` ASC) ,
  CONSTRAINT `fk_per_deficiency_per_deficiency_type`
    FOREIGN KEY (`id_deficiency` )
    REFERENCES `per_deficiency_type` (`id_deficiency` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_deficiency_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vincular a pessoa e as deficiencias que essas possam ter' ;


-- -----------------------------------------------------
-- Definition of table `per_document`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_document` (
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `cpf` VARCHAR(11) NULL DEFAULT NULL ,
  `nis` VARCHAR(21) NULL DEFAULT NULL ,
  `sus_card` VARCHAR(15) NULL DEFAULT NULL ,
  `ra` VARCHAR(20) NULL DEFAULT NULL ,
  `rg_number` VARCHAR(21) NULL DEFAULT NULL ,
  `rg_complement` VARCHAR(5) NULL DEFAULT NULL ,
  `rg_emission_date` DATE NULL DEFAULT NULL ,
  `rg_sender` VARCHAR(10) NULL DEFAULT NULL ,
  `id_rg_uf` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `title_number` VARCHAR(13) NULL DEFAULT NULL ,
  `title_zone` VARCHAR(4) NULL DEFAULT NULL ,
  `title_section` VARCHAR(4) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_person`) ,
  INDEX `fk_per_document_con_uf` (`id_rg_uf` ASC) ,
  CONSTRAINT `fk_per_document_con_uf`
    FOREIGN KEY (`id_rg_uf` )
    REFERENCES `con_uf` (`id_uf` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_document_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos documentos pessoais das pessoas' ;


-- -----------------------------------------------------
-- Definition of table `per_person_address_temp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_person_address_temp` (
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_address` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `live_since` DATE NULL DEFAULT NULL ,
  `number` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `complement` VARCHAR(72) NULL DEFAULT NULL ,
  `reference_point` VARCHAR(72) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_person`) ,
  INDEX `fk_per_person_address_temp_con_address` (`id_address` ASC) ,
  INDEX `fk_per_person_address_temp_per_person` (`id_person` ASC) ,
  CONSTRAINT `fk_per_person_address_temp_con_address`
    FOREIGN KEY (`id_address` )
    REFERENCES `con_address` (`id_address` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_person_address_temp_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro de um endereco temporario que a pessoa pode estar m' ;


-- -----------------------------------------------------
-- Definition of table `per_person_change_history`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_person_change_history` (
  `id_person_change_history` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_reference_foreign` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_resource` INT(10) UNSIGNED NOT NULL ,
  `id_user` INT(10) UNSIGNED NOT NULL ,
  `dat_operation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `table_name` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id_person_change_history`) ,
  INDEX `person_history_FK` (`id_person` ASC) ,
  INDEX `resource_person_history_FK` (`id_resource` ASC) ,
  INDEX `user_person_history_FK` (`id_user` ASC) ,
  CONSTRAINT `person_history_FK`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `resource_person_history_FK`
    FOREIGN KEY (`id_resource` )
    REFERENCES `auth_resource` (`id_resource` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_person_history_FK`
    FOREIGN KEY (`id_user` )
    REFERENCES `auth_user` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Definition of table `per_person_telephone`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `per_person_telephone` (
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_telephone` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_person`, `id_telephone`) ,
  INDEX `fk_per_person_telephone_con_telephone_number` (`id_telephone` ASC) ,
  CONSTRAINT `fk_per_person_telephone_con_telephone_number`
    FOREIGN KEY (`id_telephone` )
    REFERENCES `con_telephone_number` (`id_telephone_number` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_person_telephone_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vincular numero de telefone com uma pessoa' ;


-- -----------------------------------------------------
-- Definition of table `res_family_residence`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `res_family_residence` (
  `id_family` INT(10) UNSIGNED NOT NULL ,
  `id_residence` INT(10) UNSIGNED NOT NULL ,
  `live_since` DATE NOT NULL ,
  PRIMARY KEY (`id_residence`, `id_family`) ,
  INDEX `fk_res_family_residence_fam_family_id` (`id_family` ASC) ,
  CONSTRAINT `fk_res_family_residence_fam_family_id`
    FOREIGN KEY (`id_family` )
    REFERENCES `fam_family_id` (`id_family` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_family_residence_res_residence`
    FOREIGN KEY (`id_residence` )
    REFERENCES `res_residence` (`id_residence` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vinculo entre a familia e um domicilio.' ;


-- -----------------------------------------------------
-- Definition of table `sop_social_program_origin_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `sop_social_program_origin_type` (
  `id_origin` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `origin` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_origin`) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro da tipo da origem do beneficio (federal, municipal,' ;


-- -----------------------------------------------------
-- Definition of table `sop_social_program_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `sop_social_program_type` (
  `id_social_program_type` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_origin` INT(10) UNSIGNED NOT NULL COMMENT 'federal, estadual, municipal, entidade' ,
  `benefit` VARCHAR(80) NOT NULL ,
  `status` CHAR(1) NOT NULL ,
  PRIMARY KEY (`id_social_program_type`) ,
  INDEX `fk_social_program_type_social_program_origin_type` (`id_origin` ASC) ,
  CONSTRAINT `fk_social_program_type_social_program_origin_type`
    FOREIGN KEY (`id_origin` )
    REFERENCES `sop_social_program_origin_type` (`id_origin` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Registro dos beneficios disponiveis no municipio' ;


-- -----------------------------------------------------
-- Definition of table `sop_social_program`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `sop_social_program` (
  `id_social_program` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_social_program_type` INT(10) UNSIGNED NOT NULL ,
  `register_date` DATE NULL DEFAULT NULL ,
  `status` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_social_program`) ,
  INDEX `beneficio_idpessoa_fkey` (`id_person` ASC) ,
  INDEX `fk_beneficio_tipo_beneficio` (`id_social_program_type` ASC) ,
  CONSTRAINT `beneficio_idpessoa_fkey`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_beneficio_tipo_beneficio`
    FOREIGN KEY (`id_social_program_type` )
    REFERENCES `sop_social_program_type` (`id_social_program_type` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Vinculo entre a pessoa e beneficios de programas sociais' ;


-- -----------------------------------------------------
-- Definition of table `sys_person_inserts_by_user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS  `sys_person_inserts_by_user` (
  `id_log` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_person` INT(10) UNSIGNED NOT NULL ,
  `id_user` INT(10) UNSIGNED NOT NULL ,
  `tstamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id_log`) ,
  INDEX `fk_sys_person_inserts_by_user_auth_user` (`id_user` ASC) ,
  INDEX `fk_sys_person_inserts_by_user_per_person` (`id_person` ASC) ,
  CONSTRAINT `fk_sys_person_inserts_by_user_auth_user`
    FOREIGN KEY (`id_user` )
    REFERENCES `auth_user` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sys_person_inserts_by_user_per_person`
    FOREIGN KEY (`id_person` )
    REFERENCES `per_person` (`id_person` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

-- -----------------------------------------------------
-- Insert into Table 
-- -----------------------------------------------------

--
-- Dumping data for table `act_activity_class`
--

--
-- Dumping data for table `act_activity_detail`
--

--
-- Dumping data for table `act_category`
--

--
-- Dumping data for table `act_class`
--

--
-- Dumping data for table `act_class_assistance`
--

--
-- Dumping data for table `act_status_class`
--

INSERT INTO `act_status_class` (`id_status`,`status`) VALUES 
 (1,'Em atendimento'),
 (2,'Em espera'),
 (3,'Encerrado');


--
-- Dumping data for table `ast_assistance`
--


--
-- Dumping data for table `ast_assistance_profile`
--

--
-- Dumping data for table `ast_program`
--

--
-- Dumping data for table `ast_program_type`
--
INSERT INTO `ast_program_type` (`id_program_type`,`id_target_market`,`program_type`,`status`) VALUES 
 (1,3,'Profissionalizante',''),
 (2,3,'Complementação Escolar',''),
 (3,3,'Prestação de Serviço à Comunidade (PSC)',''),
 (4,3,'Liberdade Assistida',''),
 (5,3,'Semi-liberdade',''),
 (6,3,'Internação',''),
 (7,3,'Abrigamento',''),
 (8,2,'Programas específicos',''),
 (9,1,'Creches e Escolas (Escolar)','');


--
-- Dumping data for table `ast_target_market`
--

INSERT INTO `ast_target_market` (`id_target_market`,`target_market`,`status`) VALUES 
 (1,'0 - 7 anos',''),
 (2,'8 - 13 anos',''),
 (3,'14 - 18 anos','');

--
-- Dumping data for table `auth_group`
--

--
-- Dumping data for table `auth_group_entity`
--

--
-- Dumping data for table `auth_group_resource`
--

--
-- Dumping data for table `auth_profile`
--

--
-- Dumping data for table `auth_resource`
--

INSERT INTO `auth_resource` (`id_resource`,`controller_name`,`resource_type`) VALUES 
 (1,'/user','A'),
 (2,'/activity','A'),
 (3,'/additional-information','A'),
 (4,'/area','A'),
 (5,'/associate-entity','A'),
 (6,'/attendance','E'),
 (7,'/auth','A'),
 (8,'/benefit','E'),
 (9,'/biological-relationship','E'),
 (10,'/class','E'),
 (11,'/classification','A'),
 (12,'/education','E'),
 (13,'/entity','A'),
 (14,'/export','A'),
 (15,'/family-expense','E'),
 (16,'/family-relationship','E'),
 (17,'/group','A'),
 (18,'/health','E'),
 (19,'/history','E'),
 (20,'/import','A'),
 (21,'/income','E'),
 (22,'/index','A'),
 (23,'/network','A'),
 (24,'/person','E'),
 (25,'/profile','A'),
 (26,'/program','A'),
 (27,'/region','A'),
 (28,'/report','A'),
 (29,'/residence','E'),
 (30,'/search-address','E'),
 (31,'/search','E'),
 (32,'/entity-initial','A'),
 (33,'/access-denied','A'),
 (34,'/activity-detail','A'),
 (35,'/person-log','A'),
 (36,'/monitoring','A');
 
--
-- Dumping data for table `auth_role`
--

INSERT INTO `auth_role` (`id_role`,`role`,`status`) VALUES 
 (1,'Administrador da Rede',''),
 (2,'Gerente da Rede',''),
 (3,'Coordenador da Entidade',''),
 (4,'Técnico',''),
 (5,'Operador','');

--
-- Dumping data for table `auth_role_resource`
--
INSERT INTO `auth_role_resource` (`id_resource`,`id_role`) VALUES 
 (1,1),
 (2,1),
 (3,1),
 (4,1),
 (5,1),
 (6,1),
 (7,1),
 (8,1),
 (9,1),
 (10,1),
 (11,1),
 (12,1),
 (13,1),
 (14,1),
 (15,1),
 (16,1),
 (17,1),
 (18,1),
 (19,1),
 (20,1),
 (21,1),
 (22,1),
 (23,1),
 (24,1),
 (25,1),
 (26,1),
 (27,1),
 (28,1),
 (29,1),
 (30,1),
 (31,1),
 (32,1),
 (33,1),
 (35,1),
 (1,2),
 (6,2),
 (7,2),
 (8,2),
 (9,2),
 (10,2),
 (12,2),
 (13,2),
 (14,2),
 (15,2),
 (16,2),
 (18,2),
 (19,2),
 (21,2),
 (22,2),
 (23,2),
 (24,2),
 (28,2),
 (29,2),
 (30,2),
 (31,2),
 (33,2),
 (34,2),
 (35,2),
 (1,3),
 (6,3),
 (7,3),
 (8,3),
 (9,3),
 (10,3),
 (12,3),
 (13,3),
 (14,3),
 (15,3),
 (16,3),
 (18,3),
 (19,3),
 (21,3),
 (22,3),
 (23,3),
 (24,3),
 (28,3),
 (29,3),
 (30,3),
 (31,3),
 (33,3),
 (34,3),
 (35,3),
 (6,4),
 (7,4),
 (8,4),
 (9,4),
 (10,4),
 (12,4),
 (15,4),
 (16,4),
 (18,4),
 (19,4),
 (21,4),
 (22,4),
 (23,4),
 (24,4),
 (29,4),
 (30,4),
 (31,4),
 (33,4),
 (35,4),
 (7,5),
 (8,5),
 (9,5),
 (12,5),
 (15,5),
 (16,5),
 (18,5),
 (21,5),
 (22,5),
 (23,5),
 (24,5),
 (29,5),
 (30,5),
 (31,5),
 (33,5),
 (35,5),
 (36,1),
 (36,3);

--
-- Dumping data for table `auth_user`
--

INSERT INTO `auth_user` (`id_user`,`id_entity`,`id_group`,`id_role`,`name`,`login`,`pass`,`email`,`cpf`,`active`,`dat_creation`,`permission`) VALUES 
 (1,NULL,NULL,1,'root','root','63a9f0ea7bb98050796b649e85481845','admin@admin.com.br','29836240802',1,'2010-10-26',1);

--
-- Dumping data for table `auth_user_profile`
--

--
-- Dumping data for table `con_address`
--

--
-- Dumping data for table `con_address_nickname`
--

--
-- Dumping data for table `con_address_type`
--

--
-- Dumping data for table `con_city`
--

--
-- Dumping data for table `con_neighborhood`
--

--
-- Dumping data for table `con_neighborhood_region`
--

--
-- Dumping data for table `con_region`
--

--
-- Dumping data for table `con_telephone_number`
--


--
-- Dumping data for table `con_telephone_type`
--

INSERT INTO `con_telephone_type` (`id_telephone`,`telephone`,`status`) VALUES 
 (1,'Fixo',''),
 (2,'Celular',''),
 (3,'Fax','');

--
-- Dumping data for table `con_uf`
--

--
-- Dumping data for table `cov_coverage_address`
--


--
-- Dumping data for table `cov_coverage_health_type`
--

INSERT INTO `cov_coverage_health_type` (`id_coverage_health`,`coverage_health`,`status`) VALUES 
 (1,'PACS',''),
 (2,'PSF',''),
 (3,'Similares a PSF',''),
 (4,'Outro','');

--
-- Dumping data for table `cov_ubs`
--


--
-- Dumping data for table `csg_consanguine`
--

--
-- Dumping data for table `csg_consanguine_type`
--

INSERT INTO `csg_consanguine_type` (`id_consanguine_type`,`description`,`status`) VALUES 
 (1,'Pai/Mãe',''),
 (2,'Avô/Avó',''),
 (3,'Filho/Filha','');


--
-- Dumping data for table `eas_especial_assistance`
--

--
-- Dumping data for table `eas_lawsuit_origin`
--


INSERT INTO `eas_lawsuit_origin` (`id_lawsuit_origin`,`lawsuit_origin`) VALUES 
 (1,'Prefeitura Municipal'),
 (2,'Judiciário');


--
-- Dumping data for table `eas_official_letter_origin`
--

INSERT INTO `eas_official_letter_origin` (`id_official_letter_origin`,`official_letter_origin`) VALUES 
 (1,'Prefeitura Municipal'),
 (2,'Judiciário');


--
-- Dumping data for table `edu_degree_type`
--

INSERT INTO `edu_degree_type` (`id_degree`,`degree`,`status`) VALUES 
 (1,'Analfabeto',''),
 (2,'Até 4a série incompleta do ensino fundamental',''),
 (3,'Com 4a série completa do ensino fundamental',''),
 (4,'De 5a a 8a série incompleta do ensino fundamental',''),
 (5,'Ensino fundamental completo',''),
 (6,'Ensino médio incompleto',''),
 (7,'Ensino médio completo',''),
 (8,'Superior incompleto',''),
 (9,'Superior completo',''),
 (10,'Especialização',''),
 (11,'Mestrado',''),
 (12,'Doutorado','');



--
-- Dumping data for table `edu_level_instruction`
--


--
-- Dumping data for table `edu_period_type`
--

INSERT INTO `edu_period_type` (`id_period`,`period`,`status`) VALUES 
 (1,'Manhã',''),
 (2,'Tarde',''),
 (3,'Noite',''),
 (4,'Integral','');



--
-- Dumping data for table `edu_registration`
--


--
-- Dumping data for table `edu_school`
--



--
-- Dumping data for table `edu_school_type`
--

INSERT INTO `edu_school_type` (`id_school_type`,`school_type`,`status`) VALUES 
 (1,'Pública municipal',''),
 (2,'Pública estadual',''),
 (3,'Pública federal',''),
 (4,'Particular',''),
 (5,'Outra','');

--
-- Dumping data for table `edu_school_year_type`
--

INSERT INTO `edu_school_year_type` (`id_school_year`,`school_year`,`status`) VALUES 
 (1,'Maternal I',''),
 (2,'Maternal II  ',''),
 (3,'Maternal III',''),
 (4,'Jardim I',''),
 (5,'Jardim II',''),
 (6,'Jardim III',''),
 (7,'CA (alfabetização)',''),
 (8,'1a série do ensino fundamental',''),
 (9,'2a série do ensino fundamental',''),
 (10,'3a série do ensino fundamental',''),
 (11,'4a série do ensino fundamental',''),
 (12,'5a série do ensino fundamental',''),
 (13,'6a série do ensino fundamental',''),
 (14,'7a série do ensino fundamental',''),
 (15,'8a série do ensino fundamental',''),
 (16,'1a série do ensino médio',''),
 (17,'2a série do ensino médio',''),
 (18,'3a série do ensino médio','');


--
-- Dumping data for table `emp_employment`
--


--
-- Dumping data for table `emp_employment_status_type`
--

INSERT INTO `emp_employment_status_type` (`id_employment_status`,`employment_status`,`status`) VALUES 
 (1,'Empregador',''),
 (2,'Assalariado com carteira de trabalho',''),
 (3,'Assalariado sem carteira de trabalho',''),
 (4,'Autônomo com previdência social',''),
 (5,'Autônomo sem previdência social',''),
 (6,'Aposentado/Pensionista ',''),
 (7,'Trabalhador rural',''),
 (8,'Empregador rural',''),
 (9,'Não trabalha',''),
 (10,'Outra','');


--
-- Dumping data for table `emp_employment_telephone`
--


--
-- Dumping data for table `emp_income`
--


--
-- Dumping data for table `emp_income_type`
--

INSERT INTO `emp_income_type` (`id_income`,`income`,`status`) VALUES 
 (1,'emprego',''),
 (2,'aposentadoria',''),
 (3,'seguro-desemprego',''),
 (4,'pensao',''),
 (5,'outras rendas','');


--
-- Dumping data for table `ent_entity`
--


--
-- Dumping data for table `ent_entity_area`
--


--
-- Dumping data for table `ent_entity_area_type`
--

INSERT INTO `ent_entity_area_type` (`id_entity_area`,`entity_area`,`status`) VALUES 
 (1,'Vida e Saúde ',''),
 (2,'Educação, Cultura, Esporte e Lazer',''),
 (3,'Convivência familiar e comunitária',''),
 (4,'Liberdade, respeito e Dignidade',''),
 (5,'Profissionalização e Proteção no Trabalho. ','');


--
-- Dumping data for table `ent_entity_classification`
--


--
-- Dumping data for table `ent_entity_classification_type`
--

--
-- Dumping data for table `ent_entity_group`
--

--
-- Dumping data for table `ent_entity_group_type`
--

INSERT INTO `ent_entity_group_type` (`id_entity_group`,`entity_group`,`status`) VALUES 
 (1,'Creche Pública',''),
 (2,'Creche Conveniada',''),
 (3,'Escola municipal de Educação Infantil',''),
 (4,'Escola municipal de Ensino Básico',''),
 (5,'Fundação de direito Público',''),
 (6,'Fundação de direito privado',''),
 (7,'Escola estadual de ensino Fundamental',''),
 (8,'Escola estadual de Ensino Médio',''),
 (9,'OSCIPs',''),
 (10,'Associação Filantrópica',''),
 (11,'Unidade Básica de Saúde',''),
 (12,'Unidade de Especialidades',''),
 (13,'Hospital',''),
 (14,'Escola Técnica',''),
 (15,'Escola de Educação Infantil (Particular)',''),
 (16,'Escola de Educação Fundamental (Particular)',''),
 (17,'Outros','');

--
-- Dumping data for table `ent_entity_telephone`
--


--
-- Dumping data for table `exp_expense`
--


--
-- Dumping data for table `exp_expense_type`
--

INSERT INTO `exp_expense_type` (`id_expense`,`expense`,`status`) VALUES 
 (1,'HABITAÇÂO',''),
 (2,'ALIMENTAÇÃO',''),
 (3,'ÁGUA',''),
 (4,'LUZ',''),
 (5,'TRANSPORTE',''),
 (6,'MEDICAMENTOS',''),
 (7,'GÁS',''),
 (8,'OUTRAS','');


--
-- Dumping data for table `fam_family`
--


--
-- Dumping data for table `fam_family_id`
--


--
-- Dumping data for table `fam_kinship_type`
--

INSERT INTO `fam_kinship_type` (`id_kinship`,`kinship`,`status`) VALUES 
 (1,'Mãe',''),
 (2,'Esposo(a)',''),
 (3,'Companheiro(a)',''),
 (4,'Filho(a)',''),
 (5,'Pai',''),
 (6,'Avô/Avó',''),
 (7,'Irmão/Irmã',''),
 (8,'Cunhado(a)',''),
 (9,'Genro/Nora',''),
 (10,'Sobrinho(a)',''),
 (11,'Primo(a)',''),
 (12,'Sogro(a)',''),
 (13,'Neto(a)',''),
 (14,'Tio(a)',''),
 (15,'Adotivo(a)',''),
 (16,'Padrasto/Madrasta',''),
 (17,'Enteado(a)',''),
 (18,'Bisneto(a)',''),
 (19,'Sem parentesco',''), 
 (20,'Outro','');
 
--
-- Dumping data for table `fam_representative`
--


--
-- Dumping data for table `gas_assistance_benefit`
--


--
-- Dumping data for table `gas_assistance_benefit_type`
--

INSERT INTO `gas_assistance_benefit_type` (`id_assistance_benefit_type`,`description`,`status`) VALUES 
 (1,'Cesta básica',''),
 (2,'Remédios',''),
 (3,'Roupas',''),
 (4,'Outros','');


--
-- Dumping data for table `gas_general_assistance`
--


--
-- Dumping data for table `hlt_framework_health`
--

--
-- Dumping data for table `hlt_framework_health_type`
--

--
-- Dumping data for table `hlt_health`
--

--
-- Dumping data for table `hlt_health_plan`
--

--
-- Dumping data for table `hlt_period`
--

--
-- Dumping data for table `hlt_pregnancy`
--

--
-- Dumping data for table `hlt_vaccination`
--

--
-- Dumping data for table `hlt_vaccine`
--


--
-- Dumping data for table `hlt_vaccine_type`
--

--
-- Dumping data for table `per_age_group`
--
INSERT INTO `per_age_group` (`begin_age`, `end_age`, `status`) VALUES 
(0, 1, 'e'),
(2, 4, 'e'),
(5, 9, 'e'),
(10, 14, 'e'),
(15, 19, 'e'),
(20, 24, 'e'),
(25, 29, 'e'),
(30, 34, 'e'),
(35, 39, 'e'),
(40, 44, 'e'),
(45, 49, 'e'),
(50, 54, 'e'),
(55, 59, 'e'),
(60, 64, 'e'),
(65, 69, 'e'),
(70, 120, 'e');
 
--
-- Dumping data for table `per_civil_certificate`
--


--
-- Dumping data for table `per_ctps`
--

--
-- Dumping data for table `per_deficiency`
--

--
-- Dumping data for table `per_deficiency_type`
--

INSERT INTO `per_deficiency_type` (`id_deficiency`,`name`,`status`) VALUES 
 (1,'Cegueira',''),
 (2,'Mudez',''),
 (3,'Surdez',''),
 (4,'Mental',''),
 (5,'Fisica',''),
 (6,'Outra','');

--
-- Dumping data for table `per_document`
--


--
-- Dumping data for table `per_marital_status`
--

INSERT INTO `per_marital_status` (`id_marital_status`,`marital_status`) VALUES 
 (1,'Solteiro(a)'),
 (2,'Casado(a)'),
 (3,'Divorciado(a)'),
 (4,'Separado(a)'),
 (5,'Viúvo(a)');

--
-- Dumping data for table `per_nationality`
--

INSERT INTO `per_nationality` (`id_nationality`,`nationality`) VALUES 
 (1,'Brasileiro(a)'),
 (2,'Estrangeiro(a)');



--
-- Dumping data for table `per_person`
--

--
-- Dumping data for table `per_person_address_temp`
--


--
-- Dumping data for table `per_person_change_history`
--


--
-- Dumping data for table `per_person_telephone`
--

--
-- Dumping data for table `per_race`
--

INSERT INTO `per_race` (`id_race`,`race`) VALUES 
 (1,'Branca'),
 (2,'Negra'),
 (3,'Parda'),
 (4,'Amarela'),
 (5,'Indígena');

--
-- Dumping data for table `res_building_type`
--

INSERT INTO `res_building_type` (`id_building`,`building`,`status`) VALUES 
 (1,'Tijolo/Alvenaria',''),
 (2,'Adobe',''),
 (3,'Taipa revestida',''),
 (4,'Taipa não revestida',''),
 (5,'Madeira',''),
 (6,'Material aproveitado',''),
 (7,'Outro','');


--
-- Dumping data for table `res_family_residence`
--


--
-- Dumping data for table `res_illumination_type`
--

INSERT INTO `res_illumination_type` (`id_illumination`,`illumination`,`status`) VALUES 
 (1,'Relógio próprio',''),
 (2,'Sem relógio',''),
 (3,'Relógio comunitário',''),
 (4,'Lampião',''),
 (5,'Vela',''),
 (6,'Outro','');

--
-- Dumping data for table `res_locality_type`
--

INSERT INTO `res_locality_type` (`id_locality`,`place`,`status`) VALUES 
 (1,'Urbana',''),
 (2,'Rural','');

--
-- Dumping data for table `res_morada_type`
--

INSERT INTO `res_morada_type` (`id_morada`,`morada`,`status`) VALUES 
 (1,'Casa',''),
 (2,'Apartamento',''),
 (3,'Cômodos',''),
 (4,'Outro','');

--
-- Dumping data for table `res_residence`
--


--
-- Dumping data for table `res_sanitary_type`
--

INSERT INTO `res_sanitary_type` (`id_sanitary`,`sanitary`,`status`) VALUES 
 (1,'Rede Pública',''),
 (2,'Fossa rudimentar',''),
 (3,'Fossa séptica',''),
 (4,'Vala',''),
 (5,'Céu aberto',''),
 (6,'Outro','');

--
-- Dumping data for table `res_status_type`
--

INSERT INTO `res_status_type` (`id_status`,`status_type`,`status`) VALUES 
 (1,'Próprio',''),
 (2,'Alugado',''),
 (3,'Arrendado',''),
 (4,'Cedido',''),
 (5,'Invasão',''),
 (6,'Financiado',''),
 (7,'Outra','');

--
-- Dumping data for table `res_supply_type`
--

INSERT INTO `res_supply_type` (`id_supply`,`supply`,`status`) VALUES 
 (1,'Rede Pública',''),
 (2,'Poço/Nascente',''),
 (3,'Carro pipa',''),
 (4,'Outro','');

--
-- Dumping data for table `res_trash_type`
--
INSERT INTO `res_trash_type` (`id_trash`,`trash`,`status`) VALUES 
 (1,'Coletado',''),
 (2,'Queimado',''),
 (3,'Enterrado',''),
 (4,'Céu aberto',''),
 (5,'Outro','');

--
-- Dumping data for table `res_water_type`
--

INSERT INTO `res_water_type` (`id_water`,`water`,`status`) VALUES 
 (1,'Filtração',''),
 (2,'Fervura',''),
 (3,'Cloração',''),
 (4,'Sem tratamento',''),
 (5,'Outro','');

--
-- Dumping data for table `sop_social_program`
--


--
-- Dumping data for table `sop_social_program_origin_type`
--

INSERT INTO `sop_social_program_origin_type` (`id_origin`,`origin`,`status`) VALUES 
 (1,'Federal',''),
 (2,'Estadual',''),
 (3,'Municipal',''),
 (4,'ONG',''),
 (5,'Outros','');

--
-- Dumping data for table `sop_social_program_type`
--

INSERT INTO `sop_social_program_type` (`id_social_program_type`,`id_origin`,`benefit`,`status`) VALUES 
 (1,1,'PETI',''),
 (2,1,'LOAS',''),
 (3,1,'Agente Jovem',''),
 (4,1,'Previdência Rural',''),
 (5,1,'PRONAF',''),
 (6,1,'PROGER',''),
 (7,1,'Bolsa Família',''),
 (8,5,'Outros','');


--
-- Dumping data for table `sys_person_inserts_by_user`
--

--
-- Dumping data for table `hlt_health_plan`
--

--
-- Dumping data for table `hlt_period`
--

--
-- Dumping data for table `hlt_vaccine_type`
--

INSERT INTO `hlt_vaccine_type` VALUES  (1,'Opcional',NULL),
 (2,'Obrigatória',NULL),
 (3,'Reforço',NULL);

--
-- Dumping data for table `hlt_vaccine`
--


--
-- Dumping data for table `hlt_vaccination`
--  

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;