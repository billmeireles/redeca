<?php
/*
 * This program is free software: you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation, either version 2 of the License, or 
 * (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details. 
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 * 
 * @copyright Fundação Telefônica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Araçatuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guarujá - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de São Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de Várzea Paulista - http://www.varzeapaulista.sp.gov.br 
 * 
 * @copyright Copyright (C) 2008
 * 
 * @license GNU General Public License (GPL) - http://www.gnu.org/licenses/gpl.html 
 * 
 * @author Consulting services for Social Networks Creation by Instituto Fonte para o Desenvolvimento Social  - < fonte@fonte.org.br> - http:// www.fonte.org.br 
 * 
 * @author Consulting services for Software Requirements  by WebUse - <webuse@webuse.com.br > - http://webuse.com.br 
 * 
 * @author Initial Software development by S2it Solutions - <s2it@s2it.com.br> - http://s2it.com.br 
 * 
 * Changelog
 * 
 * Author                                           Date                               History 
 * -----------------------------------------        ------------                       ------------------ 
 * Lucas dos Santos Borges Corrêa  - S2it		   	05/05/2008	                       Create file 
 * 
 */

require_once 'BasicValidator.php';

define ( 'DONT_HAVE', 'Nnão consta nas possíveis opções de tabelas' );

abstract class AdditionalInformationValidator extends BasicValidator {
	public static function validateAdditionalInformationData(AdditionalInformationForm &$frm, &$errorMessages = null) {
		if (is_array ( $frm->getValue () )) {
			$values = $frm->getValue ();
			
			$fisrtValue = $values [0];
			$secondValue = $values [1];
			
			$errorMessages = AdditionalInformationValidator::validateAdditionalInformationValue ( $fisrtValue, $frm->getTableName () );
			
			AdditionalInformationValidator::validateAdditionalInformationEditEqualValue ( $frm->getId (), $fisrtValue, $frm->getTableName (), $errorMessages );
			AdditionalInformationValidator::validateAdditionalInformationSecondValue ( $secondValue, $frm->getTableName (), $errorMessages );
			
			return $errorMessages;
		} else {
			$errorMessages = AdditionalInformationValidator::validateAdditionalInformationValue ( $frm->getValue (), $frm->getTableName () );
			if (count ( $errorMessages ) == 0)
				AdditionalInformationValidator::validateAdditionalInformationEditEqualValue ( $frm->getId (), $frm->getValue (), $frm->getTableName (), $errorMessages );
			return $errorMessages;
		}
	}
	
	public static function validateAdditionalInformationDataAdd(AdditionalInformationForm &$frm, &$errorMessages = null) {
		if (is_array ( $frm->getValue () )) {
			$values = $frm->getValue ();
			
			$fisrtValue = $values [0];
			$secondValue = $values [1];
			
			AdditionalInformationValidator::validateAdditionalInformationValue ( $fisrtValue, $frm->getTableName (), $errorMessages );
			AdditionalInformationValidator::validateAdditionalInformationEqualValue ( $fisrtValue, $frm->getTableName (), $errorMessages );
			AdditionalInformationValidator::validateAdditionalInformationSecondValue ( $secondValue, $frm->getTableName (), $errorMessages );
			
			return $errorMessages;
		} else {
			AdditionalInformationValidator::validateAdditionalInformationValue ( $frm->getValue (), $frm->getTableName (), $errorMessages );
			if (count ( $errorMessages ) == 0)
				AdditionalInformationValidator::validateAdditionalInformationEqualValue ( $frm->getValue (), $frm->getTableName (), $errorMessages );
			
			return $errorMessages;
		}
	}
	
	public static function validateAdditionalInformationEqualValue($value, $table, &$errorMessages = null) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		
		$stmt = $db->query ( 'SHOW COLUMNS FROM ' . $table );
		$row = $stmt->fetchAll ();
		
		foreach ( $row as $v ) {
			if ((substr_count ( $v->Field, "id_" ) > 0) || ($v->Field == "status")) {
				;
			} else {
				$key = $v->Field;
			}
		}
		
		$select = $db->select ()->from ( $table );
		$select->where ( $key . ' = "' . $value . '"' );
		$row = $db->fetchRow ( $select );
		
		$i = 0;
		if (! empty ( $row )) {
			foreach ( $row as $count ) {
				$i ++;
			}
		}
		
		if ($i > 0) {
			$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->value->register;
		}
		
		unset ( $select );
		unset ( $row );
		unset ( $stmt );
		
		return $errorMessages;
	}
	
	public static function validateAdditionalInformationEditEqualValue($primaryValue, $value, $table, &$errorMessages = null) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		
		$stmt = $db->query ( 'SHOW COLUMNS FROM ' . $table );
		$row = $stmt->fetchAll ();
		
		foreach ( $row as $v ) {
			if (substr_count ( $v->Field, "id_" ) > 0) {
				if ($v->Key == 'PRI') {
					$primary = $v->Field;
				}
			} else if ($v->Field == "status") {
				;
			} else {
				$key = $v->Field;
			}
		}
		
		$select = $db->select ()->from ( $table );
		$select->where ( $key . ' = "' . $value . '"' );
		$row = $db->fetchRow ( $select );
		
		$i = 0;
		if (! empty ( $row )) {
			foreach ( $row as $count ) {
				$i ++;
			}
		}
		
		if ($i > 0) {
			if ($row->$primary != $primaryValue)
				$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->value->register;
		}
		
		unset ( $select );
		unset ( $row );
		unset ( $stmt );
		
		return $errorMessages;
	}
	
	public static function validateAdditionalInformationValue($value, $table, &$errorMessages = null) {
		$notEmpty = parent::validatorNotEmpty ();
		
		if (! $notEmpty->isValid ( $value )) {
			$errorMessages [AdditionalInformationForm::value ()] [] = $notEmpty->getMessages ();
		} else {
			$tables = AdditionalInformationValidator::getValuesOfTable ();
			
			foreach ( $tables as $k => $v ) {
				if ($table == $k) {
					$max = $v;
				}
			}
			
			if (is_int ( $max )) {
				$stringLenght = parent::validatorStringLength ( 3, $max );
				if (strlen ( $value ) > $max) {
					$abb = utils::abbreviate ( $value, 31 );
					$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->text->long1 . $abb . parent::getValidatorResources ()->text->long2 . $max . parent::getValidatorResources ()->text->long3;
				} else if (! $stringLenght->isValid ( $value )) {
					$errorMessages [AdditionalInformationForm::value ()] [] = $stringLenght->getMessages ();
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateAdditionalInformationSecondValue($id, $table, &$errorMessages = null) {
		$notEmpty = parent::validatorNotEmpty ();
		
		if (! $notEmpty->isValid ( $id )) {
			$errorMessages [AdditionalInformationForm::id ()] [] = $notEmpty->getMessages ();
		} else {
			$notInt = parent::validatorInt ();
			
			if (strlen ( $id ) > 10) {
				$abb = Utils::abbreviate ( $id, 31 );
				if (! $notInt->isValid ( $abb )) {
					$errorMessages [AdditionalInformationForm::id ()] [] = $notInt->getMessages ();
				}
			} else if (! $notInt->isValid ( $id )) {
				$errorMessages [AdditionalInformationForm::id ()] [] = $notInt->getMessages ();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateAdditionalInformationId(AdditionalInformationForm &$frm, &$errorMessages = null) {
		$notEmpty = parent::validatorNotEmpty ();
		
		if (! $notEmpty->isValid ( $frm->getId () )) {
			$errorMessages [AdditionalInformationForm::id ()] [] = $notEmpty->getMessages ();
		} else {
			$id = parent::validatorInt ();
			
			if (! $id->isValid ( $frm->getId () )) {
				$errorMessages [AdditionalInformationForm::id ()] [] = $id->getMessages ();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateAdditionalInformationTable(AdditionalInformationForm &$frm, &$errorMessages = null) {
		$tables = AdditionalInformationValidator::getValuesOfTable ();
		
		$tableName = $frm->getTableName ();
		
		$notEmpty = parent::validatorNotEmpty ();
		
		if (! $notEmpty->isValid ( $tableName )) {
			$errorMessages [AdditionalInformationForm::tableName ()] [] = $notEmpty->getMessages ();
		} else {
			$error = true;
			
			foreach ( $tables as $k => $v ) {
				if ($tableName == $k) {
					$error = false;
				}
			}
			if ($error == true) {
				$errorMessages [AdditionalInformationForm::tableName ()] [] = array (DONT_HAVE );
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateStatus(AdditionalInformationForm &$frm, &$errorMessages = null) {
		$validatorRequired = parent::validatorNotEmpty ();
		
		if (! $validatorRequired->isValid ( $frm->getStatus () )) {
			$errorMessages [AdditionalInformationForm::status ()] [] = $validatorRequired->getMessages ();
		} else {
			$validator = parent::validatorStringLength ( 1, 1 );
			
			if (! $validator->isValid ( $frm->getStatus () )) {
				$errorMessages [AdditionalInformationForm::status ()] [] = $validator->getMessages ();
			} else {
				if (($frm->getStatus () == Constants::DISABLE) || ($frm->getStatus () == Constants::ENABLE)) {
					;
				} else {
					$errorMessages [AdditionalInformationForm::status ()] [] [] = parent::getValidatorResources ()->error->disable;
				}
				
				if ($frm->getStatus () == Constants::ENABLE) {
					$frm->setStatus ( "" );
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateAdditionalInformationforEdit(AdditionalInformationForm &$frm) {
		$errorMessages = null;
		AdditionalInformationValidator::validateAdditionalInformationTable ( $frm, $errorMessages );
		AdditionalInformationValidator::validateAdditionalInformationId ( $frm, $errorMessages );
		AdditionalInformationValidator::validateAdditionalInformationData ( $frm, $errorMessages );
		AdditionalInformationValidator::validateStatus ( $frm, $errorMessages );
		
		return $errorMessages;
	}
	
	public static function validateAdditionalInformationforAdd(AdditionalInformationForm &$frm) {
		$errorMessages = null;
		AdditionalInformationValidator::validateAdditionalInformationTable ( $frm, $errorMessages );
		AdditionalInformationValidator::validateAdditionalInformationDataAdd ( $frm, $errorMessages );
		AdditionalInformationValidator::validateStatus ( $frm, $errorMessages );
		
		return $errorMessages;
	}
	
	public static function validateAdditionalInformationforDrop(AdditionalInformationForm &$frm) {
		$errorMessages = null;
		AdditionalInformationValidator::validateAdditionalInformationTable ( $frm, $errorMessages );
		AdditionalInformationValidator::validateAdditionalInformationId ( $frm, $errorMessages );
		
		return $errorMessages;
	}
	
	public static function validateVaccine($vaccine) {
		$errorMessages = null;
		$name = $vaccine->data [VAC_NAME];
		$nameLength = strlen ( $name );
		$type = $vaccine->data [VAC_ID_VACCINE_TYPE];
		$period = $vaccine->data [VAC_ID_PERIOD];
		
		if ($nameLength < 3 || $nameLength > 20)
			$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->additionalInformation->vaccine->validation->name;
		
		if ($type == null) {
			$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->additionalInformation->vaccine->validation->typeRequired;
		} else {
			$typeDb = AdditionalInformationBusiness::load ( TBL_VACCINE_TYPE, $type );
			if ($typeDb == null)
				$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->additionalInformation->vaccine->validation->type;
			elseif ($typeDb [0]->status == Constants::DISABLE)
				$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->additionalInformation->vaccine->validation->typeDisabled;
		}
		
		if ($period == null) {
			$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->additionalInformation->vaccine->validation->periodRequired;
		} else {
			$periodDb = AdditionalInformationBusiness::load ( TBL_VACCINE_PERIOD, $period );
			if ($periodDb == null)
				$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->additionalInformation->vaccine->validation->period;
			elseif ($periodDb [0]->status == Constants::DISABLE)
				$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->additionalInformation->vaccine->validation->periodDisabled;
			
			if (self::checkExistingVaccine ( $vaccine ))
				$errorMessages [AdditionalInformationForm::value ()] [] [] = parent::getValidatorResources ()->additionalInformation->vaccine->validation->existentVaccine;
		}
		
		return $errorMessages;
	}
	
	private static function checkExistingVaccine($vaccine) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			$select = $db->select ()->from ( array ('v' => TBL_VACCINE ) )->where ( VAC_NAME . ' = ?', $vaccine->data [VAC_NAME] )->where ( VAC_ID_VACCINE_TYPE . ' = ?', $vaccine->data [VAC_ID_VACCINE_TYPE] )->where ( VAC_ID_PERIOD . ' = ?', $vaccine->data [VAC_ID_PERIOD] )->where ( VAC_ID_VACCINE . ' <> ?', $vaccine->data [VAC_ID_VACCINE] );
			$results = $db->fetchAll ( $select );
			return ! empty ( $results );
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	public static function validadeAgeGroup($age_group, $edit) {
		
		if ($age_group [1] > $age_group [2] || $age_group [1] == $age_group [2]) {
			$errorMessages ['age_group'] = 'A idade inicial deve ser menor que a idade final.';
			return $errorMessages;
		} else {
			$db = Zend_Registry::get ( DB_CONNECTION );
			$db->setFetchMode ( Zend_Db::FETCH_OBJ );
			if ($edit != true) {
				try {
					$select = $db->select ()->from ( array ('v' => TBL_AGE_GROUP ) )->where ( PAG_BEGIN_AGE . ' = ?', $age_group [1] )->where ( PAG_END_AGE . ' = ?', $age_group [2] );
					$results = $db->fetchAll ( $select );
					
					if (! empty ( $results )) {
						$errorMessages ['age_group'] = 'Faixa etária já cadastrada.';
						return $errorMessages;
					}
				} catch ( Zend_Exception $e ) {
					$db->closeConnection ();
					Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
					trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
				}
			}
		}
	
	}
	
	public static function getValuesOfTable() {
		$tables = array ();
		$tables [TBL_FRAMEWORK_HEALTH_TYPE] = 15;
		$tables [TBL_SCHOOL_TYPE] = 20;
		$tables [TBL_SCHOOL_YEAR_TYPE] = 35;
		$tables [TBL_DEGREE_TYPE] = 55;
		$tables [TBL_PERIOD_TYPE] = 16;
		$tables [TBL_EXPENSE_TYPE] = 15;
		$tables [TBL_KINSHIP_TYPE] = 25;
		$tables [TBL_BUILDING_TYPE] = 30;
		$tables [TBL_MORADA_TYPE] = 15;
		$tables [TBL_LOCALITY_TYPE] = 15;
		$tables [TBL_STATUS_TYPE] = 15;
		$tables [TBL_WATER_TYPE] = 15;
		$tables [TBL_ILLUMINATION_TYPE] = 25;
		$tables [TBL_SANITARY_TYPE] = 20;
		$tables [TBL_TRASH_TYPE] = 15;
		$tables [TBL_SUPPLY_TYPE] = 15;
		$tables [TBL_CONSANGUINE_TYPE] = 25;
		$tables [TBL_SOCIAL_PROGRAM_ORIGIN_TYPE] = 15;
		$tables [TBL_DEFICIENCY_TYPE] = 15;
		$tables [TBL_INCOME_TYPE] = 20;
		$tables [TBL_EMPLOYMENT_STATUS_TYPE] = 40;
		$tables [TBL_ASSISTANCE_BENEFIT_TYPE] = 15;
		$tables [TBL_TELEPHONE_TYPE] = 15;
		$tables [TBL_ADDRESS_TYPE] = 30;
		$tables [TBL_SOURCE_TYPE] = 15;
		$tables [TBL_COVERAGE_HEALTH_TYPE] = 20;
		$tables [TBL_ENTITY_AREA_TYPE] = 50;
		$tables [TBL_ENTITY_CLASSIFICATION_TYPE] = 45;
		$tables [TBL_ENTITY_GROUP_TYPE] = 50;
		$tables [TBL_TARGET_MARKET] = 15;
		$tables [TBL_PROGRAM_TYPE] = 50;
		$tables [TBL_SOCIAL_PROGRAM_TYPE] = 25;
		$tables [TBL_HEALTH_PLAN] = 25;
		$tables [TBL_VACCINE] = 20;
		$tables [TBL_VACCINE_TYPE] = 25;
		$tables [TBL_VACCINE_PERIOD] = 25;
		$tables [TBL_AGE_GROUP] = 15;
		
		return $tables;
	}
}