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
 * Anderson Tiago Marques  - S2it		    		  30/03/2012	                       Create file 
 * 
 */
 
require_once('BasicBusiness.php');

class MonitoringBusiness extends BasicBusiness
{
	//Implementação - RF037, RF038
	public static function retPag(){
		$config = Zend_Registry::get(CONFIG);
		return $config->pagination->monitoring;
	}
	
	//Implementação - RF010, RF011, RF012, RF013.
	public static function fetchSchool($admin, $entity=null, $schools, $count) {
		
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('SchoolBusiness');
		Zend_Loader::loadClass('EntityMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('CategoryMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('PersonMonitoring', 'application/models/beans');
		
		$db = Zend_Db_Table::getDefaultAdapter();

		$result = array ();
		
		if($admin == true){
			$entitys = EntityBusiness::loadAll();
			$entitym = new EntityMonitoring();
			
			foreach ($entitys as $row){
				$entitym = new EntityMonitoring();
				$entitym->setIdEntity($row->id_entity)->setName($row->name);
				
				$idEntity = $row->id_entity;
				
				$categorys = array ();
				
				foreach ($schools as $sch){
					$categorym = new CategoryMonitoring();
					$school = SchoolBusiness::findForId($sch);
					
					$categorym->setIdCategory($school["id_school"])->setName($school["name"]);
											
					//Pessoa vinculada a escola e instituição						
					$select = $db->select()
								->distinct()
						 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
	             		 		->join(array('eli' => 'edu_level_instruction'),'pp.id_person = eli.id_person',array())
	                     		->join(array('er' => 'edu_registration'),'eli.id_level_instruction = er.id_level_instruction',array())
	                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
	                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
	                     		->where('er.id_school = ?', $sch)
	                     		->where('ap.id_entity = ?', $idEntity)
	                     		->order('name');
						
					$stmt = $db->query($select);

					$resultSet = $stmt->fetchAll();
					
					$persons = array ();
					
					if($count == false){
						foreach ($resultSet as $row) {
							$personm = new PersonMonitoring();
							$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"]);
							$persons[] = $personm;
						}
						
						//Adicionando as pessoas a escola (Categoria)
						$categorym->setPersons($persons);
						$categorym->setTotal(count($persons));
					}else{
						$categorym->setTotal(count($resultSet));
					}	
					
					$categorys[] = $categorym;
				}
				
				$entitym->setCategorys($categorys);
						
				$result[] = $entitym;
			}	
		}else{
			$entitys = EntityBusiness::load($entity);
			$entitym = new EntityMonitoring();
			$entitym->setIdEntity($entitys->id_entity)->setName($entitys->name);
			
			$idEntity = $entitys->id_entity;
			
			$categorys = array ();
			
			foreach ($schools as $sch){
				$categorym = new CategoryMonitoring();
				$school = SchoolBusiness::findForId($sch);
				
				$categorym->setIdCategory($school["id_school"])->setName($school["name"]);
										
				//Pessoa vinculada a escola e instituição						
				$select = $db->select()
							->distinct()
					 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
             		 		->join(array('eli' => 'edu_level_instruction'),'pp.id_person = eli.id_person',array())
                     		->join(array('er' => 'edu_registration'),'eli.id_level_instruction = er.id_level_instruction',array())
                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
                     		->where('er.id_school = ?', $sch)
                     		->where('ap.id_entity = ?', $idEntity)
                     		->order('name');
					
				$stmt = $db->query($select);
				$resultSet = $stmt->fetchAll();
			
				$persons = array ();
				
				if($count == false){
					foreach ($resultSet as $row) {
						$personm = new PersonMonitoring();
						$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"]);
						$persons[] = $personm;
					}
					
					//Adicionando as pessoas a escola (Categoria)
					$categorym->setPersons($persons);
					$categorym->setTotal(count($persons));
				}else{
					$categorym->setTotal(count($resultSet));
				}
				
				$categorys[] = $categorym;
			}
			
			$entitym->setCategorys($categorys);
			
			$result[] = $entitym;
		}
		
		return $result;
	}
	
	//Implementação - RF014, RF015, RF016, RF017.
	public static function fetchNeighborhoor($admin, $entity=null, $neighborhoors, $count) {
		
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('NeighborhoodBusiness');
		Zend_Loader::loadClass('EntityMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('CategoryMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('PersonMonitoring', 'application/models/beans');
		
		$db = Zend_Db_Table::getDefaultAdapter();

		$result = array ();
		
		if($admin == true){
			$entitys = EntityBusiness::loadAll();
			$entitym = new EntityMonitoring();
			
			foreach ($entitys as $row){
				$entitym = new EntityMonitoring();
				$entitym->setIdEntity($row->id_entity)->setName($row->name);
				
				$idEntity = $row->id_entity;
				
				$categorys = array ();
				
				foreach ($neighborhoors as $nei){
					$categorym = new CategoryMonitoring();
					$neighborhood = NeighborhoodBusiness::findForId($nei);
					
					$categorym->setIdCategory($neighborhood["id_neighborhood"])->setName($neighborhood["neighborhood"]);
											
					//Pessoa vinculada ao bairro e instituição						
					$select = $db->select()
								->distinct()
						 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
	             		 		->join(array('ppat' => 'per_person_address_temp'),'pp.id_person = ppat.id_person',array())
	                     		->join(array('ca' => 'con_address'),'ppat.id_address = ca.id_address',array())
	                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
	                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
	                     		->where('ca.id_neighborhood = ?', $nei)
	                     		->where('ap.id_entity = ?', $idEntity)
	                     		->order('name');
						
					$stmt = $db->query($select);

					$resultSet = $stmt->fetchAll();
					
					$persons = array ();
					
					if($count == false){
						foreach ($resultSet as $row) {
							$personm = new PersonMonitoring();
							$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"]);
							$persons[] = $personm;
						}
						
						//Adicionando as pessoas ao bairro (Categoria)
						$categorym->setPersons($persons);
						$categorym->setTotal(count($persons));
					}else{
						$categorym->setTotal(count($resultSet));
					}	
					
					$categorys[] = $categorym;
				}
				
				$entitym->setCategorys($categorys);
						
				$result[] = $entitym;
			}	
		}else{
			$entitys = EntityBusiness::load($entity);
			$entitym = new EntityMonitoring();
			$entitym->setIdEntity($entitys->id_entity)->setName($entitys->name);
			
			$idEntity = $entitys->id_entity;
			
			$categorys = array ();
			
			foreach ($neighborhoors as $nei){
				$categorym = new CategoryMonitoring();
				$neighborhood = NeighborhoodBusiness::findForId($nei);
				
				$categorym->setIdCategory($neighborhood["id_neighborhood"])->setName($neighborhood["neighborhood"]);
										
				//Pessoa vinculada ao bairro e instituição						
				$select = $db->select()
							->distinct()
					 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
             		 		->join(array('ppat' => 'per_person_address_temp'),'pp.id_person = ppat.id_person',array())
                     		->join(array('ca' => 'con_address'),'ppat.id_address = ca.id_address',array())
                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
                     		->where('ca.id_neighborhood = ?', $nei)
                     		->where('ap.id_entity = ?', $idEntity)
                     		->order('name');
					
				$stmt = $db->query($select);
				$resultSet = $stmt->fetchAll();
			
				$persons = array ();
				
				if($count == false){
					foreach ($resultSet as $row) {
						$personm = new PersonMonitoring();
						$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"]);
						$persons[] = $personm;
					}
					
					//Adicionando as pessoas ao bairro (Categoria)
					$categorym->setPersons($persons);
					$categorym->setTotal(count($persons));
				}else{
					$categorym->setTotal(count($resultSet));
				}
				
				$categorys[] = $categorym;
			}
			
			$entitym->setCategorys($categorys);
			
			$result[] = $entitym;
		}
		
		return $result;
	}
	
	//Implementação - RF026, RF027, RF028, RF029.
	public static function fetchRepresentative($admin, $entity=null, $representatives, $count) {
		
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('RepresentativeBusiness');
		Zend_Loader::loadClass('EntityMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('CategoryMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('PersonMonitoring', 'application/models/beans');
		
		$db = Zend_Db_Table::getDefaultAdapter();

		$result = array ();
		
		if($admin == true){
			$entitys = EntityBusiness::loadAll();
			$entitym = new EntityMonitoring();
			
			foreach ($entitys as $row){
				$entitym = new EntityMonitoring();
				$entitym->setIdEntity($row->id_entity)->setName($row->name);
				
				$idEntity = $row->id_entity;
				
				$categorys = array ();
				
				foreach ($representatives as $rep){
					$categorym = new CategoryMonitoring();
					$representative = PersonBusiness::loadPersonWithAddress($rep);
										
					$idFamily = $representative['id_family'];
					
					$categorym->setIdCategory($representative['id_person'])
								->setName($representative['name'])
								->setAddressType($representative['description'])
								->setAddress($representative['address'])
								->setNumber($representative['number'])
								->setComplement($representative['complement'])
								->setNeighborhood($representative['neighborhood'])
								->setCity($representative['city'])
								->setUfAbbreviation($representative['abbreviation']);
										
					//Pessoa vinculada ao representante e instituição						
					$select = $db->select()
								->distinct()
						 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
	             		 		->join(array('ff' => 'fam_family'),'pp.id_person = ff.id_person',array())
	             		 		->join(array('fkt' => 'fam_kinship_type'),'ff.id_kinship = fkt.id_kinship',array('fkt.kinship'))
	                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
	                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
	                     		->where('ff.id_family = ?', $idFamily)
	                     		->where('ap.id_entity = ?', $idEntity)
	                     		->where('ff.id_person <> ?', $categorym->getIdCategory())
	                     		->order('name');
						
					$stmt = $db->query($select);

					$resultSet = $stmt->fetchAll();
					
					$persons = array ();
					
					if($count == false){
						foreach ($resultSet as $row) {
							$personm = new PersonMonitoring();
							$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"])->setKinship($row["kinship"]);
							$persons[] = $personm;
						}
						
						//Adicionando as pessoas ao bairro (Categoria)
						$categorym->setPersons($persons);
						$categorym->setTotal(count($persons));
					}else{
						$categorym->setTotal(count($resultSet));
					}	
					
					$categorys[] = $categorym;
					
				}
				
				$entitym->setCategorys($categorys);
						
				$result[] = $entitym;
			}
		}else{
			$entitys = EntityBusiness::load($entity);
			$entitym = new EntityMonitoring();
			$entitym->setIdEntity($entitys->id_entity)->setName($entitys->name);
			
			$idEntity = $entitys->id_entity;
			
			$categorys = array ();
			
			foreach ($representatives as $rep){
				$categorym = new CategoryMonitoring();
				$representative = PersonBusiness::loadPersonWithAddress($rep);
										
				$idFamily = $representative['id_family'];
				
				$categorym->setIdCategory($representative['id_person'])
							->setName($representative['name'])
							->setAddressType($representative['description'])
							->setAddress($representative['address'])
							->setNumber($representative['number'])
							->setComplement($representative['complement'])
							->setNeighborhood($representative['neighborhood'])
							->setCity($representative['city'])
							->setUfAbbreviation($representative['abbreviation']);
									
				//Pessoa vinculada ao representante e instituição						
				$select = $db->select()
							->distinct()
					 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
             		 		->join(array('ff' => 'fam_family'),'pp.id_person = ff.id_person',array())
             		 		->join(array('fkt' => 'fam_kinship_type'),'ff.id_kinship = fkt.id_kinship',array('fkt.kinship'))
                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
                     		->where('ff.id_family = ?', $idFamily)
                     		->where('ap.id_entity = ?', $idEntity)
                     		->where('ff.id_person <> ?', $categorym->getIdCategory())
                     		->order('name');
					
				$stmt = $db->query($select);

				$resultSet = $stmt->fetchAll();
				
				$persons = array ();
				
				if($count == false){
					foreach ($resultSet as $row) {
						$personm = new PersonMonitoring();
						$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"])->setKinship($row["kinship"]);
						$persons[] = $personm;
					}
					
					//Adicionando as pessoas ao bairro (Categoria)
					$categorym->setPersons($persons);
					$categorym->setTotal(count($persons));
				}else{
					$categorym->setTotal(count($resultSet));
				}	
				
				$categorys[] = $categorym;
				
			}
			
			$entitym->setCategorys($categorys);
					
			$result[] = $entitym;
		}
		
		return $result;
	}
	
	//Implementação - RF022, RF023, RF024, RF025.
	public static function fetchBenefit($admin, $entity=null, $benefits, $count) {
		
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('SocialProgramBusiness');
		Zend_Loader::loadClass('EntityMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('CategoryMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('PersonMonitoring', 'application/models/beans');
		
		$db = Zend_Db_Table::getDefaultAdapter();

		$result = array ();
		
		if($admin == true){
			$entitys = EntityBusiness::loadAll();
			$entitym = new EntityMonitoring();
			
			foreach ($entitys as $row){
				$entitym = new EntityMonitoring();
				$entitym->setIdEntity($row->id_entity)->setName($row->name);
				
				$idEntity = $row->id_entity;
				
				$categorys = array ();
				
				foreach ($benefits as $ben){
					$categorym = new CategoryMonitoring();
					$benefit = SocialProgramBusiness::loadSocialPrograms($ben)->toArray();
					$benefit = $benefit[0];
					
					$categorym->setIdCategory($ben)
								->setName($benefit['benefit']);
					
					//Pessoa vinculada ao representante e instituição						
					$select = $db->select()
								->distinct()
						 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
	             		 		->join(array('ssp' => 'sop_social_program'),'pp.id_person = ssp.id_person',array())
	                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
	                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
	                     		->where('ssp.id_social_program_type = ?', $ben)
	                     		->where('ap.id_entity = ?', $idEntity)
	                     		->order('name');
						
					$stmt = $db->query($select);

					$resultSet = $stmt->fetchAll();
					
					$persons = array ();
					
					if($count == false){
						foreach ($resultSet as $row) {
							$personm = new PersonMonitoring();
							$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"]);
							$persons[] = $personm;
						}
						
						//Adicionando as pessoas ao bairro (Categoria)
						$categorym->setPersons($persons);
						$categorym->setTotal(count($persons));
					}else{
						$categorym->setTotal(count($resultSet));
					}	
					
					$categorys[] = $categorym;
					
				}
				
				$entitym->setCategorys($categorys);
						
				$result[] = $entitym;
			}
		}else{
			$entitys = EntityBusiness::load($entity);
			$entitym = new EntityMonitoring();
			$entitym->setIdEntity($entitys->id_entity)->setName($entitys->name);
			
			$idEntity = $entitys->id_entity;
			
			$categorys = array ();
			
			foreach ($benefits as $ben){
				$categorym = new CategoryMonitoring();
				$benefit = SocialProgramBusiness::loadSocialPrograms($ben)->toArray();
				$benefit = $benefit[0];
				
				$categorym->setIdCategory($ben)
							->setName($benefit['benefit']);
				
				//Pessoa vinculada ao representante e instituição						
				$select = $db->select()
							->distinct()
					 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
	         		 		->join(array('ssp' => 'sop_social_program'),'pp.id_person = ssp.id_person',array())
	                 		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
	                 		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
	                 		->where('ssp.id_social_program_type = ?', $ben)
	                 		->where('ap.id_entity = ?', $idEntity)
	                 		->order('name');
					
				$stmt = $db->query($select);

				$resultSet = $stmt->fetchAll();
				
				$persons = array ();
				
				if($count == false){
					foreach ($resultSet as $row) {
						$personm = new PersonMonitoring();
						$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"])->setKinship($row["kinship"]);
						$persons[] = $personm;
					}
					
					//Adicionando as pessoas ao bairro (Categoria)
					$categorym->setPersons($persons);
					$categorym->setTotal(count($persons));
				}else{
					$categorym->setTotal(count($resultSet));
				}	
				
				$categorys[] = $categorym;
				
			}
			
			$entitym->setCategorys($categorys);
					
			$result[] = $entitym;
		}
		
		return $result;
	}

	//Implementação - RF018, RF019, RF020, RF021.
	public static function fetchAgeGroup($admin, $entity=null, $ageGroups, $count) {
		
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('AdditionalInformationBusiness');
		Zend_Loader::loadClass('EntityMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('CategoryMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('PersonMonitoring', 'application/models/beans');
		
		$db = Zend_Db_Table::getDefaultAdapter();

		$result = array ();
		
		if($admin == true){
			$entitys = EntityBusiness::loadAll();
			$entitym = new EntityMonitoring();
			
			foreach ($entitys as $row){
				$entitym = new EntityMonitoring();
				$entitym->setIdEntity($row->id_entity)->setName($row->name);
				
				$idEntity = $row->id_entity;
				
				$categorys = array ();
				
				foreach ($ageGroups as $age){
					$categorym = new CategoryMonitoring();
					
					$ageGroup = AdditionalInformationBusiness::loadAgeGroup($age);
					$ageGroup = $ageGroup[0];
					
					$begin = (int) $ageGroup->begin_age;
					$end = (int) $ageGroup->end_age;
					
					$ageGroupName = 'De '.$ageGroup->begin_age.' a '.$ageGroup->end_age.' Ano(s)';
					
					$categorym->setIdCategory($age)->setName($ageGroupName);
					
					//Pessoa vinculada a escola e instituição						
					$select = $db->select()
								->distinct()
						 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
	                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
	                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
	                     		->where('((YEAR(CURDATE())-YEAR(pp.birth_date)) - (RIGHT(CURDATE(),5)<RIGHT(pp.birth_date,5))) >= ?', $begin)
	                     		->where('((YEAR(CURDATE())-YEAR(pp.birth_date)) - (RIGHT(CURDATE(),5)<RIGHT(pp.birth_date,5))) <= ?', $end)
	                     		->where('ap.id_entity = ?', $idEntity)
	                     		->order('name');
						
					$stmt = $db->query($select);

					$resultSet = $stmt->fetchAll();
					
					$persons = array ();
					
					if($count == false){
						foreach ($resultSet as $row) {
							$personm = new PersonMonitoring();
							$personm->setIdPerson($row->{"id_person"})->setName($row->{"name"})->setSex($row->{"sex"})->setBirthDate($row->{"birth_date"});
							$persons[] = $personm;
						}
					
						//Adicionando as pessoas a escola (Categoria)
						$categorym->setPersons($persons);
						$categorym->setTotal(count($persons));
					}else{
						$categorym->setTotal(count($resultSet));
					}	
					
					$categorys[] = $categorym;
				}
				
				$entitym->setCategorys($categorys);
						
				$result[] = $entitym;
			}	
		}else{
			$entitys = EntityBusiness::load($entity);
			$entitym = new EntityMonitoring();
			$entitym->setIdEntity($entitys->id_entity)->setName($entitys->name);
			
			$idEntity = $entitys->id_entity;
			$categorys = array ();
			
			foreach ($ageGroups as $age){
				$categorym = new CategoryMonitoring();
					
					$ageGroup = AdditionalInformationBusiness::loadAgeGroup($age);
					$ageGroup = $ageGroup[0];
					
					$ageGroupName = 'De '.$ageGroup->begin_age.' a '.$ageGroup->end_age.' Ano(s)';
					
					$begin = $ageGroup->begin_age;
					$end = $ageGroup->end_age;
					
					$categorym->setIdCategory($age)->setName($ageGroupName);
					
					//Pessoa vinculada a escola e instituição						
					$select = $db->select()
								->distinct()
						 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
	                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
	                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
	                     		->where('((YEAR(CURDATE())-YEAR(pp.birth_date)) - (RIGHT(CURDATE(),5)<RIGHT(pp.birth_date,5))) >= ?', $begin)
	                     		->where('((YEAR(CURDATE())-YEAR(pp.birth_date)) - (RIGHT(CURDATE(),5)<RIGHT(pp.birth_date,5))) <= ?', $end)
	                     		->where('ap.id_entity = ?', $idEntity)
	                     		->order('name');
						
					$stmt = $db->query($select);

					$resultSet = $stmt->fetchAll();
					
					$persons = array ();
					
					if($count == false){
						foreach ($resultSet as $row) {
							$personm = new PersonMonitoring();
							$personm->setIdPerson($row->{"id_person"})->setName($row->{"name"})->setSex($row->{"sex"})->setBirthDate($row->{"birth_date"});
							$persons[] = $personm;
						}
					
						//Adicionando as pessoas a escola (Categoria)
						$categorym->setPersons($persons);
						$categorym->setTotal(count($persons));
					}else{
						$categorym->setTotal(count($resultSet));
					}	
					
					$categorys[] = $categorym;
			}
			
			$entitym->setCategorys($categorys);
			
			$result[] = $entitym;
		}
		
		return $result;
	}

	//Implementação - RF030, RF031, RF032, RF033.
	public static function fetchAttendancePeriod($admin, $entity=null, $begin, $end, $count) {
		
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('EntityMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('CategoryMonitoring', 'application/models/beans');
		Zend_Loader::loadClass('PersonMonitoring', 'application/models/beans');
		
		$db = Zend_Db_Table::getDefaultAdapter();

		$result = array ();
		
		if($admin == true){
			$entitys = EntityBusiness::loadAll();
			$entitym = new EntityMonitoring();
			
			foreach ($entitys as $row){
				$entitym = new EntityMonitoring();
				$entitym->setIdEntity($row->id_entity)->setName($row->name);
				
				$idEntity = $row->id_entity;
				
				$categorys = array ();
				for ( $index = 0, $max_count = 2; $index < $max_count; $index++ ) {
					$categorym = new CategoryMonitoring();
					
					$cat = "";
					$sql = "";
					if ($index == 0){
						$cat = "Em Andamento";
						$sql = "aa.real_end_date is null";
					}else if($index == 1){
						$cat = "Encerrado";
						$sql = "aa.real_end_date is not null";
					}
										
					$categorym->setIdCategory($index)->setName($cat);
					
					//Pessoa vinculada a escola e instituição						
					$select = $db->select()
								->distinct()
						 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
	                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
	                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
	                     		->where('ap.id_entity = ?', $idEntity)
	                     		->where('aa.beginning_date >= ?', $begin)
	                     		->where('aa.beginning_date <= ?', $end)
	                     		->where($sql, '')
	                     		->order('name');
						
					$stmt = $db->query($select);

					$resultSet = $stmt->fetchAll();
					
					$persons = array ();
					
					if($count == false){
						foreach ($resultSet as $row) {
							$personm = new PersonMonitoring();
							$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"]);
							$persons[] = $personm;
						}
					
						//Adicionando as pessoas a escola (Categoria)
						$categorym->setPersons($persons);
						$categorym->setTotal(count($persons));
					}else{
						$categorym->setTotal(count($resultSet));
					}	
					
					$categorys[] = $categorym;
				}
				
				$entitym->setCategorys($categorys);
						
				$result[] = $entitym;
			}	
		}else{
			$entitys = EntityBusiness::load($entity);
			$entitym = new EntityMonitoring();
			$entitym->setIdEntity($entitys->id_entity)->setName($entitys->name);
			
			$idEntity = $entitys->id_entity;
			$categorys = array ();
			
			for ( $index = 0, $max_count = 2; $index < $max_count; $index++ ) {
				$categorym = new CategoryMonitoring();
				
				$cat = "";
				$sql = "";
				if ($index == 0){
					$cat = "Em Andamento";
					$sql = "aa.real_end_date is null";
				}else if($index == 1){
					$cat = "Encerrado";
					$sql = "aa.real_end_date is not null";
				}
									
				$categorym->setIdCategory($index)->setName($cat);
				
				//Pessoa vinculada a escola e instituição						
				$select = $db->select()
							->distinct()
					 		->from(array('pp' => 'per_person'), array('pp.id_person', 'pp.name', 'pp.sex', 'pp.birth_date'))
                     		->join(array('aa' => 'ast_assistance'),'pp.id_person = aa.id_person',array())
                     		->join(array('ap' => 'ast_program'),'aa.id_program = ap.id_program',array())
                     		->where('ap.id_entity = ?', $idEntity)
                     		->where('aa.beginning_date >= ?', $begin)
                     		->where('aa.beginning_date <= ?', $end)
                     		->where($sql, '')
                     		->order('name');
					
				$stmt = $db->query($select);
				$resultSet = $stmt->fetchAll();
				
				$persons = array ();
				
				if($count == false){
					foreach ($resultSet as $row) {
						$personm = new PersonMonitoring();
						$personm->setIdPerson($row["id_person"])->setName($row["name"])->setSex($row["sex"])->setBirthDate($row["birth_date"]);
						$persons[] = $personm;
					}
				
					//Adicionando as pessoas a escola (Categoria)
					$categorym->setPersons($persons);
					$categorym->setTotal(count($persons));
				}else{
					$categorym->setTotal(count($resultSet));
				}	
				
				$categorys[] = $categorym;
			}
			
			$entitym->setCategorys($categorys);
			
			$result[] = $entitym;
		}
		
		return $result;
	}
}