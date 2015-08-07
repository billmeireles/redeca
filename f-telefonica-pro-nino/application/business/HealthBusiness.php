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
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br
 *
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br
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
 * Fabricio Meireles Monteiro  - S2it		    	14/03/2008	                       Create file
 *
 */

require_once('BasicBusiness.php');

class HealthBusiness extends BasicBusiness
{
	/**
	 * Carrega do banco as informa��es de sa�de conforme "id" e/ou "status" da pessoa
	 */
	public static function loadHealthByPerson($idPerson)
	{
		try
		{
			$type = new Health();

			if(!empty($idPerson))
			{
					
				//busca na tabela sa�de a linha referente ao "idPerson" e "status" informados
				$where[] = $type->getAdapter()->quoteInto(HLT_ID_PERSON.' = ?', $idPerson);
				$where[] = $type->getAdapter()->quoteInto(HLT_STATUS.' is null', null);
					
				$row = $type->fetchAll($where);
					
				return $row->current();
			}

			Logger::loggerOperation('Nenhum registro de sa�de encontrado para '.$idPerson.' = '.implode(',' ,$idPerson));
			return ;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->health->load->fail, E_USER_ERROR);
		}
	}


	/**
	 * Carrega do banco as informa��es de gesta��o conforme "id" e/ou "status" da pessoa
	 */
	public static function loadPregnancyByPerson($idPerson)
	{
		try
		{
			$type = new Pregnancy();

			if(!empty($idPerson))
			{
					
				//busca na tabela sa�de a linha referente ao "idPerson" e "status" informados
				$where[] = $type->getAdapter()->quoteInto(PRG_ID_PERSON.' = ?', $idPerson);
				$where[] = $type->getAdapter()->quoteInto(PRG_STATUS.' is null', null);
					
				$row = $type->fetchAll($where);
					
				return $row->current();
			}

			Logger::loggerOperation('Nenhum registro de sa�de encontrado para '.$idPerson.' = '.implode(',' ,$idPerson));
			return ;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->pregnancy->load->fail, E_USER_ERROR);
		}
	}


	public static function loadAllHealthTypes()
	{
		Zend_Loader::loadClass('FrameworkHealthType');
			
		$table	= new FrameworkHealthType();

		try
		{
			$where = $table->getAdapter()->quoteInto(FHT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, FHT_FRAMEWORK_HEALTH);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typeframeworkhealth->load->fail, E_USER_ERROR);
		}
	}

	public static function loadHealthTypes($id)
	{
		Zend_Loader::loadClass('FrameworkHealthType');
			
		$table	= new FrameworkHealthType();

		try
		{
			$where[] = $table->getAdapter()->quoteInto(FHT_ID_FRAMEWORK_HEALTH.' in (?)', $id);
			$where[] = $table->getAdapter()->quoteInto(FHT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, FHT_FRAMEWORK_HEALTH);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typeframeworkhealth->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Persiste as informa��es editadas pelo usu�rio referentes a sa�de
	 */
	public static function save($bean, $nameController, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$transaction = true;
		}
			
		try
		{
			if(!empty($bean))
			{
				$verifyHealth = self::existPersonInHealth($bean[ID_PERSON], $db);
					
				if($verifyHealth)
				{
					self::updateStatusHealth($bean[ID_PERSON], $db);
				}
					
				$insertedHealthId = self::saveHealth($bean, $db);

				if($insertedHealthId != null)
				{
					//cria objeto do tipo hist�rico de altera��es
					$chHealth = array();
					$chHealth[PCH_ID_REFERENCE_FOREIGN]	= $insertedHealthId;
					$chHealth[PCH_ID_USER]				= UserLogged::getUserId();
					$chHealth[PCH_ID_PERSON]			= $bean[ID_PERSON];
					$chHealth[PCH_ID_RESOURCE]			= parent::loadIdResource($nameController);
					$chHealth[PCH_DATE_OPERATION]		= date("Y-m-d");
					$chHealth[PCH_TABLE_NAME]			= TBL_HEALTH;

					//persiste na tabela hist�rico de altera��es
					HistoryBusiness::save($chHealth, $db);

					self::saveFrameworkHealth($bean, $insertedHealthId, $db);

					$verifyPregnancy = self::existPersonInPregnancy($bean[ID_PERSON], $db);
					if($verifyPregnancy)
					{
						self::updateStatusPregnancy($bean[ID_PERSON], $db);
					}

					if($bean[PREGNANCY] == 1)
					{
						$insertedPregnancyId = self::savePregnancy($bean, $db);
							
						if($insertedPregnancyId != null)
						{
							//cria objeto do tipo hist�rico de altera��es
							$chPregnancy = array();
							$chPregnancy[PCH_ID_REFERENCE_FOREIGN]	= $insertedPregnancyId;
							$chPregnancy[PCH_ID_USER]				= $chHealth[PCH_ID_USER];
							$chPregnancy[PCH_ID_PERSON]				= $bean[ID_PERSON];
							$chPregnancy[PCH_ID_RESOURCE]			= $chHealth[PCH_ID_RESOURCE];
							$chPregnancy[PCH_DATE_OPERATION]		= date("Y-m-d");
							$chPregnancy[PCH_TABLE_NAME]			= TBL_PREGNANCY;
								
							//persiste na tabela hist�rico de altera��es
							HistoryBusiness::save($chPregnancy, $db);
						}
					}
				}
			}

			if($transaction)
			{
				$db -> commit();
			}
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->health->fail, E_USER_ERROR);
		}
	}

	/**
	 * Verifica se a pessoa j� tem cadastro referente a sa�de
	 */
	public static function existPersonInHealth($idPerson, &$db)
	{
		try
		{
			$obj = new Health();
				
			//verifica se na tabela gesta��o existe registro referente ao "idPerson"
			$where = $obj->getAdapter()->quoteInto(HLT_ID_PERSON.' = ?', $idPerson);
			$row = count($obj->fetchAll($where));

			if($row > 0)
			{
				return true;
			}
			return false;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->health->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Verifica se a pessoa j� tem cadastro referente a gesta��o
	 */
	public static function existPersonInPregnancy($idPerson, &$db)
	{
		try
		{
			$obj = new Pregnancy();
				
			//verifica se na tabela gesta��o existe registro referente ao "idPerson"
			$where = $obj->getAdapter()->quoteInto(PRG_ID_PERSON.' = ?', $idPerson);
			$row = count($obj->fetchAll($where));

			if($row > 0)
			{
				return true;
			}
			return false;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->pregnancy->error->failDB, E_USER_ERROR);
		}
	}

	/**
	 * Atualiza o campo "status" da tabela sa�de
	 */
	public static function updateStatusHealth($idPerson, &$db)
	{
		$obj = new Health();
			
		//busca na tabela n�vel de instru��o a linha referente ao "idPerson" cujo "status" seja null
		$where[] = $obj->getAdapter()->quoteInto(HLT_ID_PERSON.' = ?', $idPerson);
		$where[] = $obj->getAdapter()->quoteInto(HLT_STATUS.' is null', null);
		$rows = $obj->fetchAll($where);
			
		$rowHLT = $rows->current();

		//se busca retornar mais de uma linha, o campo "status" da tabela "Health" n�o ser� atualizado.
		//uma exception � gerada e um "log" ser� escrito informando o ocorrido
		try
		{
			if($rows == 1)
			{
				if($rowHLT->{HLT_ID_PERSON} != null)
				{
					Zend_Loader::loadClass('Constants');
					$data = array
					(
							'status' => Constants::HISTORY
					);

					try
					{
						$table = new Health();
						$table->update($data, $where);
							
						Logger::loggerOperation('Sa�de da pessoa '.$rowHLT->{HLT_ID_PERSON}.' atualizado com sucesso');
					}
					catch(Zend_Exception $e)
					{
						Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
						trigger_error(parent::getLabelResources()->health->error->failDB, E_USER_ERROR);
					}
				}
			}
			else
			{
				throw new Exception('Existe mais de um registro da pessoa '.$rowHLT->{HLT_ID_PERSON}.' em sa�de para ser atualizado');
			}
		}
		catch(Exception $e)
		{
			Logger::loggerOperation($e->getMessage());
			trigger_error(parent::getLabelResources()->updateHealth->error->impossible, E_USER_ERROR);
		}
	}

	/**
	 * Atualiza o campo "status" da tabela gravidez
	 */
	public static function updateStatusPregnancy($idPerson, &$db)
	{
		$obj = new Pregnancy();
			
		//busca na tabela n�vel de instru��o a linha referente ao "idPerson" cujo "status" seja null
		$where[] = $obj->getAdapter()->quoteInto(PRG_ID_PERSON.' = ?', $idPerson);
		$where[] = $obj->getAdapter()->quoteInto(PRG_STATUS.' is null', null);
		$rows = $obj->fetchAll($where);
			
		$rowPRG = $rows->current();

		//se busca retornar mais de uma linha, o campo "status" da tabela "Health" n�o ser� atualizado.
		//uma exception � gerada e um "log" ser� escrito informando o ocorrido
		try
		{
			if($rows == 1)
			{
				if($rowPRG->{PRG_ID_PERSON} != null)
				{
					Zend_Loader::loadClass('Constants');
					$data = array
					(
							'status' => Constants::HISTORY
					);

					try
					{
						$table = new Pregnancy();
						$where = null;
						$where = $table->getAdapter()->quoteInto(PRG_ID_PERSON. ' = ?', $rowPRG->{PRG_ID_PERSON});
						$table->update($data, $where);
							
						Logger::loggerOperation('Registro de gravizez da pessoa '.$rowPRG->{HLT_ID_PERSON}.' atualizado com sucesso');
					}
					catch(Zend_Exception $e)
					{
						Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
						trigger_error(parent::getLabelResources()->health->error->failDB, E_USER_ERROR);
					}
				}
			}
			else
			{
				throw new Exception('Existe mais de um registro relacionado a gravidez da pessoa '.$rowHLT->{HLT_ID_PERSON}.' para ser atualizado');
			}
		}
		catch(Exception $e)
		{
			Logger::loggerOperation($e->getMessage());
			trigger_error(parent::getLabelResources()->updatePregnancy->error->impossible, E_USER_ERROR);
		}
	}


	/**
	 * Persiste as informa��es de sa�de
	 */
	public static function saveHealth($bean, &$db)
	{
		$health = array();
		$health[HLT_ID_PERSON]		= $bean[ID_PERSON];
		$health[HLT_DRUG_USER]		= $bean[USER_DRUG];
		$health[HLT_VACCINE]		= $bean[VACCINE];
		$health[HLT_VACCINE_TO_DATE]= date("Y-m-d");
		$health[HLT_ID_HEALTH_PLAN]	= $bean[ID_HEALTH_PLAN];
		$health[HLT_HEALTH_PLAN]	= $bean[NAME_PLAN];
		$health[HLT_STATUS]		 	= null;
		$health[HLT_PRONTUARY] 		= $bean[PRONTUARY];
			
		if($bean[ENTITY] > 0){
			$health[HLT_ENTITY]	   = $bean[ENTITY];
		}else{
			$health[HLT_PRONTUARY] = null;
			$health[HLT_ENTITY]	   = null;
		}
			
		try
		{
			$healthObj = new Health();
			$id = $healthObj->insert($health);
			Logger::loggerOperation('Novo registro de sa�de adicionado. [id ='.$id.']');

			return $id;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->health->error->failDB, E_USER_ERROR);
		}
	}

	/**
	 * Persiste as informa��es de quadro se sa�de
	 */
	public static function saveFrameworkHealth($bean, $idHealth, &$db)
	{
		if($idHealth != null)
		{
			$frameworkHealth = array();
			$frameworkHealth[FHL_ID_HEALTH] = $idHealth;

			try
			{
				foreach($bean[OBJECTS_HEALTH] as $objFrameworkHealth)
				{
					$frameworkHealth[FHL_ID_FRAMEWORK_HEALTH]			= $objFrameworkHealth[FHL_ID_FRAMEWORK_HEALTH];
					$frameworkHealth[FHL_FRAMEWORK_HEALTH_DESCRIPTION]	= $objFrameworkHealth[FHL_FRAMEWORK_HEALTH_DESCRIPTION];

					$frameworkHealthObj = new FrameworkHealth();
					$id = $frameworkHealthObj->insert($frameworkHealth);
					Logger::loggerOperation('Novo registro quadro de sa�de adicionado. [id ='.$id.']'.'[idFrameworkHealth='.$frameworkHealth[FHL_ID_FRAMEWORK_HEALTH].']');
				}
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->frameworkHealth->error->failDB, E_USER_ERROR);
			}
		}
	}

	/**
	 * Persiste as informa��es de gravidez
	 */
	public static function savePregnancy($bean, &$db)
	{
		$pregnancy = array();
		$pregnancy[PRG_ID_PERSON]			= $bean[ID_PERSON];
		$pregnancy[PRG_PRENATAL_SIS]		= $bean[SIS_PREGNANCY];
		$pregnancy[PRG_BEGINNING_PREGNANCY]	= $bean[BEGIN_PREGNANCY];
		$pregnancy[PRG_MET]					= $bean[MET];
		$pregnancy[PRG_STATUS]	 			= null;
			
		try
		{
			$pregnancyObj = new Pregnancy();
			$idInserted = $pregnancyObj->insert($pregnancy);
			Logger::loggerOperation('Novo registro de gravidez adicionado. [idPerson='.$pregnancy[HLT_ID_PERSON].']');

			return $idInserted;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->pregnancy->error->failDB, E_USER_ERROR);
		}
	}

	/**
	 * Carrega todas as unidades de atendimento
	 *
	 */
	public static function loadAllCareUnits()
	{
			
		$config = Zend_Registry::get(CONFIG);
		$ent = Utils::buildMap($config->entities->classification->showable);

		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		try
		{
			$select = $db->select()->from(TBL_ENTITY)
			->where('id_entity IN (?)', $ent)
			->where('status = 1')
			->order(ENT_NAME);

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega todos os planos de saude
	 *
	 */
	public static function loadAllHealthPlans()
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		try
		{
			$select = $db->select()->from(TBL_HEALTH_PLAN)
			->where("status <> 'd'")
			->order(HLP_NAME);

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega todas as vacinas
	 *
	 */
	public static function loadAllVaccines()
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		try
		{
			$select = $db->select()->from(array('v' => TBL_VACCINE))
						->join(array('p' => TBL_VACCINE_PERIOD),'v.id_period = p.id_period',array('id_period', 'description'))
						->order(VAC_NAME);
			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega do banco as informa��es de unidade de atendimento conforme seu "id"
	 */
	public static function loadCareUnityById($idCareUnit)
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		try
		{
			$select = $db->select()->from(TBL_ENTITY)
			->where('id_entity = ? ', $idCareUnit);

			return $db->fetchRow($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}


	/**
	 * Carrega do banco as informa��es de vacina conforme seu "id"
	 */
	public static function loadVaccineById($idVaccine)
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		try
		{
			
			$select = $db->select()->from(array('v' => TBL_VACCINE))
						->join(array('p' => TBL_VACCINE_PERIOD),'v.id_period = p.id_period',array('id_period', 'description'))
						->where('id_vaccine = ? ', $idVaccine);

			return $db->fetchRow($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega do banco o plano de saude confirme seu "id"
	 */
	public static function loadHealthPlanById($idHealthPlan, $validate=null)
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		try
		{
			if($validate){
				$select = $db->select()->from(TBL_HEALTH_PLAN)
				->where("id_health_plan = ? ", $idHealthPlan)
				->where("status <> 'd'");
			}else{
				$select = $db->select()->from(TBL_HEALTH_PLAN)
				->where('id_health_plan = ? ', $idHealthPlan);
			}

			return $db->fetchRow($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	public static function saveVaccination($vaccination)
	{
		$vacc = array();
			
		$vacc[VCN_ID_PERSON]		= $vaccination[ID_PERSON];
		$vacc[VCN_ID_VACCINE]		= $vaccination[ID_VACCINE];
		$vacc[VCN_DATE]				= $vaccination[DATE];
		$vacc[VCN_LOT]				= $vaccination[LOT];
		$vacc[VCN_STATUS]	 		= $vaccination[STATUS];
			
		try
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			
			$vaccinationObj = new Vaccination();

			//Insere os registros da tabela
			$idInserted = $vaccinationObj->insert($vacc);
			
			if($idInserted != null)
			{
				//cria objeto do tipo hist�rico de altera��es
				$history = array();
				$history[PCH_ID_REFERENCE_FOREIGN]	= $idInserted;
				$history[PCH_ID_USER]				= UserLogged::getUserId();
				$history[PCH_ID_PERSON]				= $vaccination[ID_PERSON];
				$history[PCH_ID_RESOURCE]			= parent::loadIdResource("health");
				$history[PCH_DATE_OPERATION]		= date("Y-m-d");
				$history[PCH_TABLE_NAME]			= TBL_VACCINATION;

				//persiste na tabela hist�rico de altera��es
				HistoryBusiness::save($history, $db);
			}
			
			$db->commit();
			
			Logger::loggerOperation('Novo registro de vacina��o adicionado. [idPerson='.$vacc[VCN_ID_PERSON].']');

			return $idInserted;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->pregnancy->error->failDB, E_USER_ERROR);
		}
	}

	public static function deleteVaccinations($idPerson)
	{
		try
		{
			//Remove os registros atuais da tabela
			$db = Zend_Db_Table::getDefaultAdapter();
			$where = $db->quoteInto('id_person = ?', $idPerson);
			$db->delete(TBL_VACCINATION, $where);
			Logger::loggerOperation('Registros de vacina��o removidos. [idPerson='.$vacc[VCN_ID_PERSON].']');
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->pregnancy->error->failDB, E_USER_ERROR);
		}
	}

	public static function disabVaccinations($idVaccination)
	{
		try
		{
			$db = Zend_Db_Table::getDefaultAdapter();
			$where = $db->quoteInto('id_vaccination = ?', $idVaccination);
			$db->update(TBL_VACCINATION, array('status'=>0), $where);
			
			Logger::loggerOperation('Registros de vacina��o removidos. [idPerson='.$vacc[VCN_ID_PERSON].']');
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->pregnancy->error->failDB, E_USER_ERROR);
		}
	}

	/**
	 * Carrega do banco as informa��es de vacina��o conforme "id" do usu�rio
	 */
	public static function loadVaccinationsById($idPerson)
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		try
		{
			$select = $db->select()->from(array('vn' => TBL_VACCINATION),array('id_vaccination', 'id_person', 'date', 'lot', 'status'))
									->join(array('v' => TBL_VACCINE),'vn.id_vaccine = v.id_vaccine',array('id_vaccine', 'name'))
									->join(array('p' => TBL_VACCINE_PERIOD),'v.id_period = p.id_period',array('id_period', 'description'))
									->where('vn.id_person = ? ', $idPerson)
									->order('vn.date');

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega do banco as informa��es de vacina��o e vacina conforme "id" do usu�rio
	 */
	public static function loadVaccinations($idPerson)
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
		try
		{

			$select = $db->select()->from(array('vn' => TBL_VACCINATION))
									->join(array('v' => TBL_VACCINE),'vn.id_vaccine = v.id_vaccine')
									->join(array('p' => TBL_VACCINE_PERIOD),'v.id_period = p.id_period')
									->where('vn.id_person = ? ', $idPerson)
									->where('vn.status = 1')
									->order('vn.date');

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}


}