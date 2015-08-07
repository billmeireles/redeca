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
 * Saulo Esteves Rodrigues  - S2it		    		29/01/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class PersonBusiness extends BasicBusiness
{
	/**
	 * Persiste informa��es no DB 
	 * @param Array $person - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conex�o
	 * @return ID
	 */
	public static function save($person, &$db=null)
	{
		
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Person();
			Zend_Loader::loadClass('PersonInsertsByUser');
			if($person[PRS_ID_PERSON] == false)
			{
				$obj->insert($person);
				$id = $db->lastInsertId(TBL_PERSON);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_PERSON .' [id='.$id.']');
				
				//log de inser��es de pessoas
				$logData			 	= array();
				$logData[PIU_ID_PERSON] = $id;
				$logData[PIU_ID_USER]	= UserLogged::getUserId();
				$logObj 				= new PersonInsertsByUser();
				$logObj->insert($logData);				
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(PRS_ID_PERSON.' = ?', $person[PRS_ID_PERSON]);
				$id = $obj->update($person, $where);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_PERSON.
					' ['.PRS_ID_PERSON.'='.$person[PRS_ID_PERSON].']');
			}
			
			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->person->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros
	 * 
	 * $start : Inicia a consulta do registro n. $start
	 * $limit : Total de registros � serem recuperados
	 * 
	 */
	public static function loadAll($lastName, $firstName, $start=null, $limit=null)
	{
		$db 		= Zend_Registry::get(DB_CONNECTION);
		try
		{
			$select 	= $db->select()->from(TBL_PERSON);
			
			if($lastName != null)
			{
				$select->where(PERSON_LASTNAME . ' LIKE ?', $lastName);
			}
			if($firstName != null)
			{
				$select->where(PERSON_FIRSTNAME . ' LIKE ?', $firstName);
			}
			$select->limit($limit, $start);

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Recupera a quantidade total de registros
	 * 
	 */
	public static function countAll($lastName, $firstName)
	{
		$db 		= Zend_Registry::get(DB_CONNECTION);
		try
		{
			$select 	= $db->select()
							->from(TBL_PERSON,
								array('total' => 'COUNT(*)'));
			if($lastName != null)
			{
				$select->where(PERSON_LASTNAME . ' LIKE ?', $lastName);
			}
			if($firstName != null)
			{
				$select->where(PERSON_FIRSTNAME . ' LIKE ?', $firstName);
			}
								
			$count		= $db->fetchRow($select);
			
			return $count['total'];
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega um registro
	 * 
	 */
	public static function load($id)
	{
		$obj = new Person();
		try
		{
			$res = $obj->find($id);
			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui um registro
	 * 
	 * Se passar o segundo par�metro (conex�o), o m�todo n�o 
	 * efetua o commit no final (assume que quem chama tem o 
	 * controle transacional)
	 */
	public static function drop($id, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$person = new Person();
			$where = PERSON_ID . ' = ' . $id;
			$person->delete($where);
			if($mt) $db->commit();
			Logger::loggerOperation('Pessoa exclu�da. [id='.$id.']');
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega uma �nica pessoa
	 */
	public static function loadPerson($idPerson)
	{	
		try
		{	
			$type = new Person();
			
			if(!empty($idPerson))
			{	
				//busca na tabela fam�lia "per_person" a linha referente ao "idPerson" 			
				$where = $type->getAdapter()->quoteInto(PRS_ID_PERSON.' = ?', $idPerson);
				$row = $type->fetchAll($where);
				
				return $row->current();
			}		
			
			return null;	
			
			Logger::loggerOperation('Nenhum registro encontrado para a pessoa de id = '.$idPerson.' = '.implode(',' ,$idPerson));
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega as �ltimas pessoas inseridas no sistema (por usu�rio)
	 * 
	 */
	public static function loadPersonsInsertedByUser($id_user, $limit)
	{
		$db 		= Zend_Registry::get(DB_CONNECTION);
		try
		{
			$select = $db->select()
						->from(array('log' => TBL_PERSON_INSERTS_BY_USER))
						->joinInner(array('per' => TBL_PERSON),
		        		'log.'. PIU_ID_PERSON .' = per.'. PRS_ID_PERSON);
			
			if($id_user != null)
			{
				$select->where('log.'.PIU_ID_USER . ' = ?', $id_user);
			}
			$select->order('log.'.PIU_TSTAMP.' DESC');
			$select->limit($limit, 0);

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	//Implementa��o - RF026, RF027, RF028 e RF029
	public static function loadPersonWithAddress($id_person) {
		$db = Zend_Db_Table :: getDefaultAdapter();

		$select = $db->select()
					 ->distinct()
					 ->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name'))
             		 ->join(array('fr' => 'fam_representative'),'fr.id_person = pp.id_person',array('fr.id_family'))
             		 ->joinLeft(array('ppat' => 'per_person_address_temp'),'pp.id_person = ppat.id_person',array('ppat.number', 'ppat.complement'))
	                 ->joinLeft(array('ca' => 'con_address'),'ppat.id_address = ca.id_address',array('ca.address'))
	                 ->joinLeft(array('cn' => 'con_neighborhood'),'ca.id_neighborhood = cn.id_neighborhood',array('cn.neighborhood'))
	                 ->joinLeft(array('cc' => 'con_city'),'cc.id_city = cn.id_city',array('cc.city'))
	                 ->joinLeft(array('cat' => 'con_address_type'),'cat.id_address_type = ca.id_address_type',array('cat.description'))
	                 ->joinLeft(array('cuf' => 'con_uf'),'cc.id_uf = cuf.id_uf',array('cuf.abbreviation'))
	                 ->where('pp.id_person = ?', $id_person)
                     ->order('name');
					
		$stmt = $db->query($select);

		$resultSet = $stmt->fetchAll();

		return $resultSet[0];
	}
}