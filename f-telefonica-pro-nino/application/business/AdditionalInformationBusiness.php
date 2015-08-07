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
 * Lucas dos Santos Borges Corrêa  - S2it         	07/03/2008                         Create file 
 * 
 */
require_once ('BasicBusiness.php');

class AdditionalInformationBusiness extends BasicBusiness {
	/**
	 * Carrega todos os registros
	 * 
	 */
	public static function loadAll($tableName, $order) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			$select = $db->select ()->from ( $tableName )->order ( $order );
			return $db->fetchAll ( $select );
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	public static function loadAllEnabled($tableName) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			$select = $db->select ()->from ( $tableName )->where ( "status <> '" . Constants::DISABLE . "' or status is null" );
			return $db->fetchAll ( $select );
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Carrega todas as faixas etárias
	 */
	public static function loadAllAgeGroup() {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			$select = $db->select ()->from ( TBL_AGE_GROUP)->where("status <> '" . Constants::DISABLE . "' or status is null" )->order("begin_age")->order("end_age");
			return $db->fetchAll ( $select );
		} catch ( Zend_Exception $e ) {
			echo $e; die;
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	public static function loadAgeGroup($id) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			$select = $db->select()
			             ->from (TBL_AGE_GROUP)
			             ->where("id_age_group = ?", $id)
			             ->order("begin_age");
			return $db->fetchAll ( $select );
		} catch ( Zend_Exception $e ) {
			echo $e; die;
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Carrega todas as vacinas
	 */
	public static function loadAllVaccines() {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			$select = $db->select ()->from ( array ('v' => TBL_VACCINE ) )->join ( array ('vt' => TBL_VACCINE_TYPE ), 'v.' . VAC_ID_VACCINE_TYPE . ' = vt.' . VCT_ID_VACCINE_TYPE, array ('type_description' => 'vt.' . VCT_DESCRIPTION ) )->join ( array ('vp' => TBL_VACCINE_PERIOD ), 'v.' . VAC_ID_PERIOD . ' = vp.' . PER_ID_PERIOD, array ('period_description' => 'vp.' . PER_DESCRIPTION ) );
			return $db->fetchAll ( $select );
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Carrega um registros
	 * 
	 */
	public static function load($tableName, $id) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			$columns = AdditionalInformationBusiness::getColumnNameByClass ( $tableName );
			if ($columns == true) {
				$fieldId = $columns [0];
				
				$select = $db->select ()->from ( $tableName );
				$select->where ( $fieldId . ' = ' . $id );
				
				return $db->fetchAll ( $select );
			}
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Salva e atualiza campo de acordo com as informações passadas
	 */
	public static function save($tableName, $id, $value, $status, &$db = null) {
		if ($db == null) {
			$db = Zend_Registry::get ( DB_CONNECTION );
			$db->beginTransaction ();
			$mt = true;
		}
		
		try {
			if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
				$columns = AdditionalInformationBusiness::getColumnNameByClass ( $tableName );
			} else {
				try {
					$columns = AdditionalInformationBusiness::getColumnName ( $tableName );
				} catch ( Zend_Exception $e ) {
					$columns = AdditionalInformationBusiness::getColumnNameByClass ( $tableName );
				}
			}
			if ($columns == true) {
				$fieldId = $columns [0];
				$fieldValue = $columns [1];
				
				if (is_array ( $fieldValue )) {
					$data = array ($fieldValue [0] => $value [1], $fieldValue [1] => $value [0], F_STATUS => $status );
				} else {
					$data = array ($fieldValue => $value, F_STATUS => $status );
				}
				
				if ($id == false) {
					$insertedId = $db->insert ( $tableName, $data );
					Logger::loggerOperation ( 'Novo ' . $tableName . ' adicionado. [id=' . $insertedId . ']' );
				} else {
					$where = $fieldId . ' = ' . $id;
					$db->update ( $tableName, $data, $where );
					Logger::loggerOperation ( $tableName . ' modificado. [id=' . $id . ']' );
				}
				if ($mt)
					$db->commit ();
			}
		} catch ( Zend_Exception $e ) {
			$db->rollback ();
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->group->save->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Exclui campo de acordo com informações passadas
	 */
	public static function drop($tableName, $id, &$db = null) {
		if ($db == null) {
			$db = Zend_Registry::get ( DB_CONNECTION );
			$db->beginTransaction ();
			$mt = true;
		}
		
		try {
			if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
				$columns = AdditionalInformationBusiness::getColumnNameByClass ( $tableName );
			} else {
				$columns = AdditionalInformationBusiness::getColumnName ( $tableName );
			}
			if ($columns == true) {
				$fieldId = $columns [0];
				
				$where = $fieldId . ' = ' . $id;
				$status [F_STATUS] = Constants::DISABLE;
				
				$db->update ( $tableName, $status, $where );
				
				if ($mt)
					$db->commit ();
				Logger::loggerOperation ( $tableName . ' excluído. [id=' . $id . ']' );
			}
		} catch ( Zend_Exception $e ) {
			$db->rollback ();
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->group->drop->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Recupera dados da coluna vindos de um array
	 */
	public static function getColumnName($tableName) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		try {
			$stmt = $db->query ( 'SHOW COLUMNS FROM ' . $tableName );
			$rows = $stmt->fetchAll ();
			
			if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
				$values = array ();
				$i = 0;
			}
			
			foreach ( $rows as $k => $v ) {
				if ($v instanceof stdClass) {
					if ($v->Key == "PRI") {
						$fieldId = $v->Field;
					} else {
						if ($v->Field != F_STATUS) {
							if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
								$values [$i] = $v->Field;
								$i ++;
							} else {
								$fieldValue = $v->Field;
							}
						}
					}
				} else {
					if ($v ["Key"] == "PRI") {
						$fieldId = $v ["Field"];
					} else {
						if ($v ["Field"] != F_STATUS) {
							if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
								$values [$i] = $v ["Field"];
								$i ++;
							} else {
								$fieldValue = $v ["Field"];
							}
						}
					}
				}
			}
			if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
				$fieldValue = array ($values [0], $values [1] );
			}
			
			$fields = array ($fieldId, $fieldValue );
			
			return $fields;
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Recupera dados da coluna vindos de uma classe
	 */
	public static function getColumnNameByClass($tableName) {
		$db = Zend_Registry::get ( DB_CONNECTION );
		try {
			$stmt = $db->query ( 'SHOW COLUMNS FROM ' . $tableName );
			$rows = $stmt->fetchAll ();
			
			if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
				$values = array ();
				$i = 0;
			}
			
			foreach ( $rows as $k => $v ) {
				if ($v->Key == "PRI") {
					$fieldId = $v->Field;
				} else {
					if ($v->Field != F_STATUS) {
						if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
							$values [$i] = $v->Field;
							$i ++;
						} else {
							$fieldValue = $v->Field;
						}
					}
				}
			}
			
			if (($tableName == TBL_PROGRAM_TYPE) || ($tableName == TBL_SOCIAL_PROGRAM_TYPE)) {
				$fieldValue = array ($values [0], $values [1] );
			}
			
			$fields = array ($fieldId, $fieldValue );
			
			return $fields;
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	public static function saveVaccine($vaccine) {
		if ($db == null) {
			$db = Zend_Registry::get ( DB_CONNECTION );
			$db->beginTransaction ();
			$mt = true;
		}
		
		try {
			$id = $vaccine->data ["id_vaccine"];
			if ($id == 0) {
				$insertedId = $db->insert ( TBL_VACCINE, $vaccine->data );
				Logger::loggerOperation ( 'Nova vacina adicionada. [id=' . $insertedId . ']' );
			} else {
				$where = VAC_ID_VACCINE . ' = ' . $id;
				$db->update ( TBL_VACCINE, $vaccine->data, $where );
				Logger::loggerOperation ( 'Vacina modificada. [id=' . $id . ']' );
			}
			if ($mt)
				$db->commit ();
		} catch ( Zend_Exception $e ) {
			$db->rollback ();
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->group->save->fail, E_USER_ERROR );
		}
	}
	
	public static function saveAgeGroup($age_group, $status) {
		if ($db == null) {
			$db = Zend_Registry::get ( DB_CONNECTION );
			$db->beginTransaction ();
			$mt = true;
		}
		
		try {
			$id = $age_group[4];
			if ($id == null) {
				$data = array ("begin_age" => $age_group[1], "end_age" => $age_group[2], F_STATUS => $age_group[3] );
				$insertedId = $db->insert ( TBL_AGE_GROUP, $data );
				Logger::loggerOperation ( 'Nova vacina adicionada. [id=' . $insertedId . ']' );
			} else {
				$data = array ("begin_age" => $age_group[1], "end_age" => $age_group[2], F_STATUS => $status );
				$where = PAG_ID_AGE_GROUP . ' = ' . $id;
				$db->update ( TBL_AGE_GROUP, $data, $where );
				Logger::loggerOperation ( 'Vacina modificada. [id=' . $id . ']' );
			}
			if ($mt)
				$db->commit ();
		} catch ( Zend_Exception $e ) {
			$db->rollback ();
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->group->save->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Carrega todos os campos de target market
	 */
	public static function listTableTargetMarket() {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			Zend_Loader::loadClass ( 'TargetMarket' );
			$targetMarket = new TargetMarket ();
			return $targetMarket->fetchAll ();
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
	
	/**
	 * Carrega todos os campos de social program type
	 */
	public static function listTableSocialProgramOrigenType() {
		$db = Zend_Registry::get ( DB_CONNECTION );
		$db->setFetchMode ( Zend_Db::FETCH_OBJ );
		try {
			Zend_Loader::loadClass ( 'SocialProgramOrigin' );
			$socialProgram = new SocialProgramOrigin ();
			return $socialProgram->fetchAll ();
		} catch ( Zend_Exception $e ) {
			$db->closeConnection ();
			Logger::loggerError ( "Caught exception: " . get_class ( $e ) . "\nMessage: " . $e->getMessage () );
			trigger_error ( parent::getLabelResources ()->additional->load->fail, E_USER_ERROR );
		}
	}
}