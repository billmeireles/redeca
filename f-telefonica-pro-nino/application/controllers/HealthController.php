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
 * Fabricio Meireles Monteiro  - S2it		   		05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class HealthController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Health');
		parent::setControllerHelp('Health');
		
		Zend_Loader::loadClass('HealthForm');
		Zend_Loader::loadClass('Health');
		Zend_Loader::loadClass('FrameworkHealth');
		Zend_Loader::loadClass('HealthBusiness');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('HistoryBusiness');
		Zend_Loader::loadClass('HealthValidator');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass('Resource');
		Zend_Loader::loadClass('PersonChangeHistory');
		Zend_Loader::loadClass('HealthPlan');
		Zend_Loader::loadClass('Vaccine');
		Zend_Loader::loadClass('Vaccination');

		$frm = new HealthForm();
		$frm->assembleRequest($this->_request);
		
		$this->view->healthPlans = HealthBusiness::loadAllHealthPlans();
		$this->view->vaccines = HealthBusiness::loadAllVaccines();
		$this->view->careUnits = HealthBusiness::loadAllCareUnits();
		$this->view->form = $frm;
		
	}
	
	/**
	 * Exibe as informa��es atualmente preenchidas
	 */
	function indexAction()
	{
		$this->view->form->setIdPerson($this->view->form->getPersonId());
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = HealthValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//carrega as informa��es de sa�de referente a uma pessoa
		$this->view->healthByPerson = HealthBusiness::loadHealthByPerson($this->view->form->getIdPerson());

		//carrega as informa��es de unidade de atendimento referente a saude
		$this->view->careUnit = HealthBusiness::loadCareUnityById($this->view->healthByPerson->id_entity);
		
		//carrega as informa��es de plano m�dico referente a saude
		$this->view->healthPlan = HealthBusiness::loadHealthPlanById($this->view->healthByPerson->id_health_plan);
		
		//carrega as informa��es de gesta��o referente a uma pessoa
		$this->view->pregnancyByPerson = HealthBusiness::loadPregnancyByPerson($this->view->form->getIdPerson());
		
		//carrega as informa��es de vacina��o referente ao usu�rio
		$this->view->vaccination = HealthBusiness::loadVaccinations($this->view->form->getIdPerson());
		
		//funcionalidade somente-leitura?
		if(ResourcePermission::isResourceReadOnly($this->_request))
		{
			$this->view->readOnly = TRUE;
		}
		else
		{
			$this->view->readOnly = FALSE;
		}
	}
	
	/**
	 * Popula o container
	 */
	function containerAction()
	{
		$this->indexAction();
	}
	
	/**
	 * Exibe formul�rio para edi��o de um usu�rio
	 */
	function viewAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = HealthValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//carrega todos os "tipo quadro sa�de"
		$this->view->healthTypes = HealthBusiness::loadAllHealthTypes();
		
		//carrega as informa��es de sa�de referente a uma pessoa
		$this->view->healthByPerson = HealthBusiness::loadHealthByPerson($this->view->form->getIdPerson());
//		desc($this->view->healthByPerson);die();

		if(Zend_Registry::get(CONFIG)->drugs->user->show == "true"){
			$this->view->form->setUserDrug($this->view->healthByPerson->{HLT_DRUG_USER});
		}else{
			$this->view->form->setUserDrug(null);
		}
		
		$this->view->form->setVaccine($this->view->healthByPerson->{HLT_VACCINE});
		$this->view->form->setProntuary($this->view->healthByPerson->{HLT_PRONTUARY});
		$this->view->form->setEntity($this->view->healthByPerson->{HLT_ENTITY});
		
		//carrega as informa��es de vacina��o referente ao usu�rio
		$this->view->vaccination = HealthBusiness::loadVaccinationsById($this->view->form->getIdPerson());
		
		if($this->view->healthByPerson->{HLT_HEALTH_PLAN})
		{
			if($this->view->healthByPerson->{HLT_HEALTH_PLAN} == "N�o")
			{
				$this->view->form->setCheckedHealthPlan(2);
			}
			else
			{
				$this->view->form->setCheckedHealthPlan(1);
			}
		}
		elseif($this->view->healthByPerson->{HLT_ID_HEALTH_PLAN})
		{
			$this->view->form->setCheckedHealthPlan(1);
		}
		else
		{
			$this->view->form->setCheckedHealthPlan(0);
		}
		
		
//		desc($this->view->form);die();
		
		//carrega as informa��es de gesta��o referente a uma pessoa
		$this->view->pregnancyByPerson = HealthBusiness::loadPregnancyByPerson($this->view->form->getIdPerson());
		
		$this->view->form->setMet($this->view->pregnancyByPerson->{PRG_MET});
		
		//objeto person
		$this->view->person = PersonBusiness::loadPerson($this->view->form->getIdPerson());
	
		//funcionalidade somente-leitura?
		if(ResourcePermission::isResourceReadOnly($this->_request))
		{
			$this->view->readOnly = TRUE;
		}
		else
		{
			$this->view->readOnly = FALSE;
		}
	}
	
	/**
	 * Salva usu�rio (edi��o)
	 */
	function editAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{		
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = HealthValidator::validateHealth($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{	
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//objeto person
				$this->view->person = PersonBusiness::loadPerson($this->view->form->getIdPerson());
				
				//carrega todos os "tipo quadro sa�de"
				$this->view->healthTypes = HealthBusiness::loadAllHealthTypes();
				
				//carrega as informa��es de vacina��o de acordo com o "id" do usu�rio
				$this->view->vaccination = HealthBusiness::loadVaccinationsById($this->view->form->getIdPerson());
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
//			desc($bean);die();
			//persiste as informa��es referentes a sa�de na base de dados
			HealthBusiness::save($bean, $this->_request->getParam('controller'));
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(HEALTH_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Salva ou Edita uma vacina��o
	 */
	function vaccinationEditAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = HealthValidator::validateVaccinationHealth($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{	
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//objeto person
				$this->view->person = PersonBusiness::loadPerson($this->view->form->getIdPerson());
				
				//carrega todos os "tipo quadro sa�de"
				$this->view->healthTypes = HealthBusiness::loadAllHealthTypes();
				
				//carrega as informa��es de vacina��o de acordo com o "id" do usu�rio
				$this->view->vaccination = HealthBusiness::loadVaccinationsById($this->view->form->getIdPerson());
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean"
			$bean = $this->assembleFormVaccinationToBean($this->view->form);
			
			if($bean[ID_VACCINATION] > 0){
				//desabilita o registro atual para manter o hist�rico e cria um novo
				HealthBusiness::disabVaccinations($this->view->form->getIdVaccination());
			}

			//cria um novo registro de vacina��o no banco
			HealthBusiness::saveVaccination($bean);
			
			return;
			//redireciona fluxo da aplica��o para p�gina a mesma p�gina
			//$this->_redirect(HEALTH_CONTROLLER .'/'.DEFAULT_VIEW_ACTION.''.PERSON_CONTROLLER.'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Desabilita uma vacina��o deixando 'hidden' no template
	 */
	function vaccinationDisabAction()
	{

		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			HealthBusiness::disabVaccinations($this->view->form->getIdVaccination());
			
			//redireciona fluxo da aplica��o para p�gina a mesma p�gina
			$this->_redirect(HEALTH_CONTROLLER .'/'.DEFAULT_VIEW_ACTION.''.PERSON_CONTROLLER.'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToBean(HealthForm $frm)
	{	
		if(!Utils::isEmpty($frm))
		{
			//cria uma vari�vel array de nome "bean" 
			$bean = array();
			
			if(!Utils::isEmpty($frm->getPregnancy()))
			{
				
				//adiciona uma vari�vel - pregnancy - do form no array "bean"
				$bean[PREGNANCY] = $frm->getPregnancy();
				
				if(!Utils::isEmpty($frm->getMet()))
				{
					//adiciona uma vari�vel - met - do form no array "bean"
					$bean[MET] = $frm->getMet();				
				}	
				
				if(!Utils::isEmpty($frm->getPregnancySis()))
				{
					//adiciona uma vari�vel - pregnancySis - do form no array "bean"
					$bean[SIS_PREGNANCY] = $frm->getPregnancySis();				
				}	
				
				if(!Utils::isEmpty($frm->getPregnancyBegin()))
				{	
					//formata a data inserida pelo usu�rio
					$beginPregnancy = HealthForm::dateFormat($frm->getPregnancyBegin());
				
					//adiciona uma vari�vel - pregnancyBegin - do form no array "bean"
					$bean[BEGIN_PREGNANCY] = $beginPregnancy;
				}		
			}
			else
			{
				$bean[PREGNANCY] = null;
				$bean[MET] = null;
				$bean[SIS_PREGNANCY] = null;
				$bean[BEGIN_PREGNANCY] = null;
			}
			
			if(!Utils::isEmpty($frm->getProntuary()))
			{
				//adiciona uma vari�vel - prontuary - do form no array "bean"
				$bean[PRONTUARY] = $frm->getProntuary();				
			}
			
			if(!Utils::isEmpty($frm->getEntity()))
			{
				//adiciona uma vari�vel - entity - do form no array "bean"
				$bean[ENTITY] = $frm->getEntity();				
			}
			
			if(Zend_Registry::get(CONFIG)->drugs->user->show == "true"){
				if(!Utils::isEmpty($frm->getUserDrug()))
				{
					//adiciona uma vari�vel - userDrug - do form no array "bean"
					$bean[USER_DRUG] = $frm->getUserDrug();				
				}
			}else{
				$bean[USER_DRUG] = 0;
			}
			
			if(!Utils::isEmpty($frm->getVaccine()))
			{
				//adiciona uma vari�vel - vaccine - do form no array "bean"
				$bean[VACCINE] = $frm->getVaccine();
			}
			
			if($frm->getCheckedHealthPlan() == 1)
			{	
				if($frm->getIdHealthPlan() == 'outro'){
					//adiciona uma vari�vel - healthPlan - do form no array "bean"
					$bean[NAME_PLAN] = $frm->getHealthPlan();					
				}else{
					$bean[ID_HEALTH_PLAN] = $frm->getIdHealthPlan();
					$bean[NAME_PLAN] = null;
				}
			}
			elseif($frm->getCheckedHealthPlan() == 2)
			{
				$bean[NAME_PLAN] = 'N�o';
			}
			else
			{
				$bean[NAME_PLAN] = null;
			}
						
			if(!Utils::isEmpty($frm->getIdTypeHealth()))
			{
				//adiciona uma vari�vel - idTypeHealth - do form no array "bean"
				$bean[TYPE_HEALTH] = $frm->getIdTypeHealth();				
			}
			
			if(!Utils::isEmpty($frm->getCollFrameworkHealth()))
			{
				//adiciona uma cole��o de objetos sa�de no array "bean"
				$bean[OBJECTS_HEALTH] = $frm->getCollFrameworkHealth();			
			}
			
			if(!Utils::isEmpty($frm->getIdPerson()))
			{
				//adiciona uma vari�vel - idPerson - do form no array "bean"
				$bean[ID_PERSON] = $frm->getIdPerson();				
			}
			
			return $bean;
		}
	}
	
	/**
	 * Recupera as informa��es do form de vacina��o e retorna no array
	 */	
	function assembleFormVaccinationToBean(HealthForm $frm)
	{	
		if(!Utils::isEmpty($frm))
		{
			
			//cria uma vari�vel array de nome 'vaccination'
			$vaccination = array();
			
			if(!Utils::isEmpty($frm->getIdPerson()))
			{
				//adiciona uma vari�vel - idPerson - do form no array "bean"
				$vaccination[ID_PERSON] = $frm->getIdPerson();				
			}
			
			if(!Utils::isEmpty($frm->getIdVaccination()))
			{
				//adiciona uma vari�vel - idVaccination - do form no array "vaccination"
				$vaccination[ID_VACCINATION] = $frm->getIdVaccination();
			}				
			
			if(!Utils::isEmpty($frm->getIdVaccine()))
			{
				//adiciona uma vari�vel - idVaccine - do form no array "vaccination"
				$vaccination[ID_VACCINE] = $frm->getIdVaccine();
			}

			if(!Utils::isEmpty($frm->getDate()))
			{
				//formata a data inserida pelo usu�rio
				$dateVaccination = HealthForm::dateFormat($frm->getDate());
			
				//adiciona uma vari�vel - date - do form no array "vaccination"
				$vaccination[DATE] = $dateVaccination;
			}
			
			if(!Utils::isEmpty($frm->getLot()))
			{
				//adiciona uma vari�vel - lot - do form no array "bean"
				$vaccination[LOT] = $frm->getLot();				
			}
			
			if(!Utils::isEmpty($frm->getStatus()))
			{
				$vaccination[STATUS] = $frm->getStatus();
			}
				
			return $vaccination;
		}
	}
	
	//redireciona aplica��o para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
}