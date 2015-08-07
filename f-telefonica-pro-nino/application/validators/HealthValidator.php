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
 * Fabricio Meireles Monteiro  - S2it		   		14/03/2008	                       Create file 
 * 
 */
 
	require_once 'BasicValidator.php';
	
	abstract class HealthValidator extends BasicValidator
	{	
		public static function validatePersonId(HealthForm &$frm, &$errorMessages = null)
		{
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getIdPerson()))
			{
				$errorMessages[HealthForm::idPerson()][] = $validatorRequired->getMessages();
			}
			else
			{
				if(!$validator->isValid($frm->getIdPerson()))
				{
					$errorMessages[HealthForm::idPerson()][] = $validator->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('PersonBusiness');
				
					$row = PersonBusiness::load($frm->getIdPerson());				
					if(count($row) == 0)
					{
						$errorMessages[HealthForm::idPerson()][][] = parent::getValidatorResources()->person->notfound;					
					}
				}
			}
			
			return $errorMessages;
		}
				
		public static function validatePregnancy(HealthForm &$frm, &$errorMessages = null)
		{			
			Zend_Loader::loadClass('PersonBusiness');
			$row = PersonBusiness::load($frm->getIdPerson());
			if($row->current()->{PRS_SEX} == Constants::WOMAN)
			{
				$validatorPersonIsFemale = parent::validatePersonIsFemale();
				if(!$validatorPersonIsFemale->isValid($frm->getIdPerson()))
				{	
					$errorMessages[HealthForm::pregnancy()][] = $validatorPersonIsFemale->getMessages();
				}			
				else
				{	
					$validator = parent::validatorInt();
					if(!$validator->isValid($frm->getPregnancy()))
					{
						$errorMessages[HealthForm::pregnancy()][] = $validator->getMessages();	
					}
					else
					{
						if(($frm->getPregnancy() == 2) || ($frm->getPregnancy() == 1) || ($frm->getPregnancy() == 0))
						{
							;
						}
						else
						{
							$errorMessages[HealthForm::pregnancy()][][] = parent::getValidatorResources()->pregnancy->notfound;
						}
					}
				}
			}	
			
			return $errorMessages;
		}
				
		public static function validateMet(HealthForm &$frm, &$errorMessages = null)
		{	
			Zend_Loader::loadClass('PersonBusiness');
			$row = PersonBusiness::load($frm->getIdPerson());
			if($row->current()->{PRS_SEX} == Constants::WOMAN)
			{
				$validator = parent::validatorInt();
				
				if(!$validator->isValid($frm->getMet()))
				{
					$errorMessages[HealthForm::met()][] = $validator->getMessages();
				}
				else
				{
					if(($frm->getMet() == 0) || ($frm->getMet() == 1) || ($frm->getMet() == 2))
					{
						;
					}
					else
					{
						$errorMessages[HealthForm::met()][][] = parent::getValidatorResources()->met->notfound;
					}
				}
			}
			
			return $errorMessages;
		}
			
		public static function validatePregnancySis(HealthForm &$frm, &$errorMessages = null)
		{	
			Zend_Loader::loadClass('PersonBusiness');
			$row = PersonBusiness::load($frm->getIdPerson());
			if($row->current()->{PRS_SEX} == Constants::WOMAN)
			{
				if($frm->getMet() == 1)
				{
					$validatorRequired = parent::validatorNotEmpty();
				
					if (!$validatorRequired->isValid($frm->getPregnancySis()))
					{
						$errorMessages[HealthForm::pregnancySis()][] = $validatorRequired->getMessages();
					}
					else
					{
						$validator = parent::validatorStringLength(3, 50);
					
						if (!$validator->isValid($frm->getPregnancySis()))
						{
							$errorMessages[HealthForm::pregnancySis()][] = $validator->getMessages();
						}
						else
						{
							$validator = parent::validatorInt();
					
							if(!$validator->isValid($frm->getPregnancySis()))
							{
								$errorMessages[HealthForm::pregnancySis()][] = $validator->getMessages();
							}
							else
							{
								if($frm->getPregnancySis() <= 0)
								{
									$errorMessages[HealthForm::pregnancySis()][][] = parent::getValidatorResources()->sis->greater;
								}
							}
						}
					}
				}
			}
			
			return $errorMessages;
		}
		
		public static function validatePregnancyBegin(HealthForm &$frm, &$errorMessages = null)
		{	
			Zend_Loader::loadClass('PersonBusiness');
			$row = PersonBusiness::load($frm->getIdPerson());
			if($row->current()->{PRS_SEX} == Constants::WOMAN)
			{
				$validator = parent::validatorDate();
				
				//formata a data inserida pelo usuário
				$formatedDate = BasicForm::dateFormat($frm->getPregnancyBegin());
				
				if((strlen($frm->getPregnancyBegin()) > 0) && !$validator->isValid($formatedDate))
				{
					$errorMessages[HealthForm::pregnancyBegin()][] = $validator->getMessages();
				}
			}
			
			return $errorMessages;
		}
		
		public static function validateUserDrug(HealthForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorInt();
			
			if(!$validator->isValid($frm->getUserDrug()))
			{
				$errorMessages[HealthForm::userDrug()][] = $validator->getMessages();
			}
			else
			{
				if(($frm->getUserDrug() == 0) || ($frm->getUserDrug() == 1) || ($frm->getUserDrug() == 2))
				{
					;
				}
				else
				{
					$errorMessages[HealthForm::userDrug()][][] = parent::getValidatorResources()->drug->notfound;
				}
			}
			
			return $errorMessages;
		}
		
		public static function validateVaccine(HealthForm &$frm, &$errorMessages = null)
		{		
			$validator = parent::validatorInt();
			
			if(!$validator->isValid($frm->getVaccine()))
			{
				$errorMessages[HealthForm::vaccine()][] = $validator->getMessages();
			}
			else
			{
				if(($frm->getVaccine() == 0) || ($frm->getVaccine() == 1) || ($frm->getVaccine() == 2))
				{
					;
				}
				else
				{
					$errorMessages[HealthForm::vaccine()][][] = parent::getValidatorResources()->vaccine->notfound;
				}
			}
			
			return $errorMessages;
		}
		
		public static function validateCheckedHealthPlan(HealthForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorInt();
			
			if(!$validator->isValid($frm->getCheckedHealthPlan()))
			{
				$errorMessages[HealthForm::checkedHealthPlan()][] = $validator->getMessages();
			}
			else
			{
				if(($frm->getCheckedHealthPlan() == 0) || ($frm->getCheckedHealthPlan() == 1) || ($frm->getCheckedHealthPlan() == 2))
				{
					;
				}
				else
				{
					$errorMessages[HealthForm::checkedHealthPlan()][][] = parent::getValidatorResources()->healthPlan->notfound;
				}
			}
			
			return $errorMessages;
		}
			
		public static function validateHealthPlan(HealthForm &$frm, &$errorMessages = null)
		{
			if($frm->getCheckedHealthPlan() == 1 && $frm->getIdHealthPlan() == 'outro')
			{
				$validator = parent::validatorStringLength(3, 20);
			
				if(strlen($frm->getHealthPlan()) > 20)
				{
					$healthPlan = Utils::abbreviate($frm->getHealthPlan(), 21);
					if (!$validator->isValid($healthPlan))
					{
						$errorMessages[HealthForm::healthPlan()][] = $validator->getMessages();
					}
				}
				else if (!$validator->isValid($frm->getHealthPlan()))
				{
					$errorMessages[HealthForm::healthPlan()][] = $validator->getMessages();
				}
				
				// valida se o valor digitado no campo plano de saÃºde já está
				// cadastrado no banco de dados
				$db = Zend_Registry::get(DB_CONNECTION);		
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				try
				{
					$select = $db->select()->from(TBL_HEALTH_PLAN)
							->where(HLP_NAME.' = ?', $frm->getHealthPlan());
					$res = $db->fetchAll($select);
					if (!empty($res))
						$errorMessages[HealthForm::healthPlan()][][] = parent::getValidatorResources()->healthPlan->existent;
				}
				catch(Zend_Exception $e)
				{
					$db->closeConnection();
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
					trigger_error(parent::getLabelResources()->additional->load->fail, E_USER_ERROR);
				}
			}
			
			return $errorMessages;
		}
		
		public static function validateObjectTypeHealth(HealthForm &$frm, &$errorMessages = null)
		{
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			$validatorLength = parent::validatorStringLength(3, 100);
						
			if(sizeof($frm->getCollFrameworkHealth()) > 0)
			{	
				foreach($frm->getCollFrameworkHealth() as $obj)
				{	
					if($obj[FHL_ID_FRAMEWORK_HEALTH])
					{
						if(!$validatorRequired->isValid($obj[FHL_ID_FRAMEWORK_HEALTH]))
						{
							$errorMessages[HealthForm::idTypeHealth()][] = $validatorRequired->getMessages();
						}
						else
						{
							if(strlen($obj[FHL_ID_FRAMEWORK_HEALTH]) > 10)
							{
								$idFrame = Utils::abbreviate($obj[FHL_ID_FRAMEWORK_HEALTH], 31);
								if(!$validator->isValid($idFrame))
								{
									$errorMessages[HealthForm::idTypeHealth()][] = $validator->getMessages();
								}
							}
							else if(!$validator->isValid($obj[FHL_ID_FRAMEWORK_HEALTH]))
							{
								$errorMessages[HealthForm::idTypeHealth()][] = $validator->getMessages();
							}
							else
							{
								Zend_Loader::loadClass('HealthBusiness');
								$row = HealthBusiness::loadHealthTypes($obj[FHL_ID_FRAMEWORK_HEALTH]);
								if(count($row) == 0)
								{
									$errorMessages[HealthForm::idTypeHealth()][][] = parent::getValidatorResources()->healthFrame->notfound;
								}
							}
						}
						
						if(strlen($obj[FHL_FRAMEWORK_HEALTH_DESCRIPTION]) > 100)
						{
							$description = Utils::abbreviate($obj[FHL_FRAMEWORK_HEALTH_DESCRIPTION], 31);
							$errorMessages[HealthForm::descrTypeHealth()][][] = parent::getValidatorResources()->text->long1.$description.parent::getValidatorResources()->text->long2.'100'.parent::getValidatorResources()->text->long3;							
						}
						else if (!$validatorLength->isValid($obj[FHL_FRAMEWORK_HEALTH_DESCRIPTION]))
						{
							$errorMessages[HealthForm::descrTypeHealth()][] = $validatorLength->getMessages();
						}
						else
						{
							$validatorWord = parent::validatorWords($obj[FHL_FRAMEWORK_HEALTH_DESCRIPTION]);
							if(!$validatorWord->isValid($obj[FHL_FRAMEWORK_HEALTH_DESCRIPTION]))
							{
								$errorMessages[HealthForm::descrTypeHealth()][] = $validatorWord->getMessages();
							}
						}
					}
				}
				
				return $errorMessages;
			}
		}
		
		public static function validateProntuary(HealthForm &$frm, &$errorMessages = null)
		{
			if (strlen($frm->getProntuary()) > 0){
				if(strlen($frm->getProntuary()) <= 100){
					if($frm->getEntity() == 0){
						$errorMessages[HealthForm::entity()][][] = parent::getValidatorResources()->healthProntuary->entityRequired;
					}
				}else{
					$errorMessages[HealthForm::entity()][][] = parent::getValidatorResources()->healthProntuary->maxlength;
				}
			}
			
			return $errorMessages;
		}
		
		public static function validateEntity(HealthForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorInt();
			
			if($validator->isValid($frm->getEntity())){
				if($frm->getEntity() != 0){
					
					Zend_Loader::loadClass('EntityBusiness');
					$row = EntityBusiness::loadEntity($frm->getEntity());				
					
					if(count($row) > 0){
						if(!$row->status){
							$errorMessages[HealthForm::entity()][][] = parent::getValidatorResources()->entity->notfound;
						}
					}else{
						$errorMessages[HealthForm::entity()][][] = parent::getValidatorResources()->entity->notfound;
					}
				}
			}else{
				$errorMessages[HealthForm::entity()][][] = parent::getValidatorResources()->entity->notfound;
			}
			
			return $errorMessages;
		}
	
		public static function validateIdHealthPlan(HealthForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorInt();
			
			if($frm->getCheckedHealthPlan() == 1){
				if($frm->getIdHealthPlan() != 'outro'){
					if($validator->isValid($frm->getIdHealthPlan())){
						if($frm->getIdHealthPlan() > 0){
							Zend_Loader::loadClass('HealthBusiness');
							$row = HealthBusiness::loadHealthPlanById($frm->getIdHealthPlan(), $validate=true);				
							
							if(!$row){
								$errorMessages[HealthForm::idHealthPlan()][][] = parent::getValidatorResources()->healthPlan->notfound;					
							}
						}else{
							$errorMessages[HealthForm::idHealthPlan()][][] = parent::getValidatorResources()->idHealthPlan->required;
						}
					}else{
						$errorMessages[HealthForm::idHealthPlan()][][] = parent::getValidatorResources()->healthPlan->notfound;
					}
				}
			}
			
			return $errorMessages;
		}
		
		public static function validateHealth(HealthForm &$frm)
		{	
			$errorMessages = null;
			HealthValidator::validatePersonId($frm, $errorMessages);
			HealthValidator::validateObjectTypeHealth($frm, $errorMessages);
			HealthValidator::validatePregnancy($frm, $errorMessages);
			HealthValidator::validateMet($frm, $errorMessages);
			HealthValidator::validateHealthPlan($frm, $errorMessages);
			HealthValidator::validateIdHealthPlan($frm, $errorMessages);
			HealthValidator::validateCheckedHealthPlan($frm, $errorMessages);
			HealthValidator::validateVaccine($frm, $errorMessages);
			
			if(Zend_Registry::get(CONFIG)->drugs->user->show == "true"){
				HealthValidator::validateUserDrug($frm, $errorMessages);
			}
			
			HealthValidator::validatePregnancyBegin($frm, $errorMessages);
			HealthValidator::validatePregnancySis($frm, $errorMessages);
			HealthValidator::validateProntuary($frm, $errorMessages);
			HealthValidator::validateEntity($frm, $errorMessages);
			return $errorMessages;
		}

		/**
		 * Validaï¿½ï¿½es para o formulï¿½rio de vacinaï¿½ï¿½o
		 */
		public static function validatePersonIdVaccination(HealthForm &$frm, &$errorMessages = null)
		{
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getIdPerson()))
			{
				$errorMessages[HealthForm::idPerson()][] = $validatorRequired->getMessages();
			}
			else
			{
				if(!$validator->isValid($frm->getIdPerson()))
				{
					$errorMessages[HealthForm::idPerson()][] = $validator->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('PersonBusiness');
				
					$row = PersonBusiness::load($frm->getIdPerson());				
					if(count($row) == 0)
					{
						$errorMessages[HealthForm::idPerson()][][] = parent::getValidatorResources()->person->notfound;					
					}
				}
			}
			
			return $errorMessages;
		}
		
		//valida a vacina escolhada na combobox
		public static function validateIdVaccine(HealthForm &$frm, &$errorMessages = null)
		{	
			$validator = parent::validatorInt();
			
			if($validator->isValid($frm->getIdVaccine())){
				if($frm->getIdVaccine() != 0){
					
					Zend_Loader::loadClass('HealthBusiness');
					$row = HealthBusiness::loadVaccineById($frm->getIdVaccine());				
					
					if(count($row) > 0){
						if(!$row->status || $row->status == 'd'){
							$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->idVaccine->notfound;
						}
					}else{
						$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->idVaccine->notfound;
					}
				}else{
					$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->idVaccine->required;
				}
			}else{
				$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->idVaccine->notfound;
			}
			
			return $errorMessages;
		}
		
		//valida a data da vacinaï¿½ï¿½o informada pelo usuï¿½rio
		public static function validateVaccinationDate(HealthForm &$frm, &$errorMessages = null)
		{	
			Zend_Loader::loadClass('HealthBusiness');
			
			$validator = parent::validatorDate();
			
			if($frm->getDate() && (strlen($frm->getDate()) > 0)){
				$formatedDate = BasicForm::dateFormat($frm->getDate());
				$date = date_parse($formatedDate);
				$today = date_parse(date("d-m-YYYY"));
				if(!$validator->isValid($formatedDate)){
					$errorMessages[HealthForm::idVaccination()][] = $validator->getMessages();
				}
				elseif ($date > $today) {
					$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->healthDate->noFuture;
				}
			}else{
				$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->healthDate->required;
			}
			
			return $errorMessages;
		}

		//valida o lote informado pelo usuï¿½rio
		public static function validateLot(HealthForm &$frm, &$errorMessages = null)
		{
			if (strlen($frm->getLot()) > 0){
				if(strlen($frm->getLot()) > 100){
					$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->healthLot->maxlength;
				}
			}else{
				$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->healthLot->minlength;
			}
			
			return $errorMessages;
		}
		
		public static function validateExistentVaccination(HealthForm &$frm, &$errorMessages = null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
			try
			{
				$select = $db->select()->from(array(TBL_VACCINATION))
						->where('status = 1')		
						->where('id_person = ?', $frm->getIdPerson())
						->where('id_vaccine = ?', $frm->getIdVaccine())
						->where('date = ?', BasicForm::dateFormat($frm->getDate()))
						->where('lot = ?', $frm->getLot())
						->where('id_vaccination <> ?', $frm->getIdVaccination());
	
				$res = $db->fetchAll($select);
				if (!empty($res))
				{
					$errorMessages[HealthForm::idVaccination()][][] = parent::getValidatorResources()->healthVaccination->existent;
				}
				return $errorMessages;
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->health->load->fail, E_USER_ERROR);
			}
		}
		
		//valida o form de vacinaï¿½ï¿½es
		public static function validateVaccinationHealth(HealthForm &$frm)
		{	
			$errorMessages = null;
			HealthValidator::validatePersonIdVaccination($frm, $errorMessages);
			HealthValidator::validateIdVaccine($frm, $errorMessages);
			HealthValidator::validateVaccinationDate($frm, $errorMessages);
			HealthValidator::validateLot($frm, $errorMessages);
			HealthValidator::validateExistentVaccination($frm, $errorMessages);
			
			return $errorMessages;
		}

	}
?>