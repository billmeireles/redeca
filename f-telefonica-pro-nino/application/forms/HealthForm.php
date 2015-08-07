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
 * @copyright Fundaçãnão Telefônica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Araçãatuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guarujá - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de Snão Carlos - http://www.saocarlos.sp.gov.br 
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
 * Fabricio Meireles Monteiro  - S2it		   		13/03/2008	                       Create file 
 * 
 */


require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_PREGNANCY',					'pregnancy');
define('F_MET',							'met');
define('F_PREGNANCY_SIS',				'pregnancySis');
define('F_PREGNANCY_BEGIN',				'pregnancyBegin');
define('F_USER_DRUG',					'userDrug');
define('F_VACCINE',						'vaccine');
define('F_HEALTH_PLAN',					'healthPlan');
define('F_ID_HEALTH_PLAN',				'idHealthPlan');
define('F_CHECKED_HEALTH_PLAN',			'checkedHealthPlan');
define('F_TYPE_HEALTH_ID',				'idTypeHealth');
define('F_TYPE_HEALTH_DESCR',			'descrTypeHealth');
define('F_COLL_FRAMEWORK_HEALTH',		'collFrameworkHealth');
define('F_PERSON_ID',					'person');
define('F_PRONTUARY',					'prontuary');
define('F_ENTITY',						'entity');
define('F_ID_VACCINE',					'idVaccine');
define('F_ID_VACCINATION',				'idVaccination');
define('F_DATE',						'date');
define('F_LOT',							'lot');
define('F_STATUS',						'status');

//a ser utilizado ? - aguardando feedback do Jordnão 
define('F_UBS_ID',				'idUBS');


class HealthForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $idPerson;
	private $pregnancy;
	private $met;
	private $pregnancySis;
	private $pregnancyBegin;
	private $vaccine;
	private $userDrug;
	private	$idHealthPlan;
	private $healthPlan;
	private $checkedHealthPlan;
	private $idTypeHealth;
	private $descrTypeHealth;
	private $collFrameworkHealth;
	private $prontuary;
	private $entity;
	private $idVaccine;
	private $idVaccination;
	private $date;
	private $lot;
	private $status;
	
	//a ser utilizado ? - aguardando feedback do Jordnão
	private $idUBS;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function idPerson()
	{
		return F_PERSON_ID;
	}
	
	public static function idSocialProgram()
	{
		return F_SOCIAL_PROGRAM_ID;
	}
	
	public static function pregnancy()
	{
		return F_PREGNANCY;
	}
	
	public static function met()
	{
		return F_MET;
	}
	
	public static function pregnancySis()
	{
		return F_PREGNANCY_SIS;
	}
	
	public static function pregnancyBegin()
	{
		return F_PREGNANCY_BEGIN;
	}
	
	public static function userDrug()
	{
		return F_USER_DRUG;
	}
	
	public static function vaccine()
	{
		return F_VACCINE;
	}
	
	public static function idHealthPlan()
	{
		return F_ID_HEALTH_PLAN;
	}
	
	public static function healthPlan()
	{
		return F_HEALTH_PLAN;
	}
	
	public static function checkedHealthPlan()
	{
		return F_CHECKED_HEALTH_PLAN;
	}
	
	public static function idTypeHealth()
	{
		return F_TYPE_HEALTH_ID;
	}
	
	public static function descrTypeHealth()
	{
		return F_TYPE_HEALTH_DESCR;
	}
	
	public static function idUBS()
	{
		return F_UBS_ID;
	}
	
	public static function collFrameworkHealth()
	{
		return F_COLL_FRAMEWORK_HEALTH;
	}
	
	public static function prontuary()
	{
		return F_PRONTUARY;
	}
	
	public static function entity()
	{
		return F_ENTITY;
	}

	public static function idVaccine()
	{
		return F_ID_VACCINE;
	}
	
	public static function idVaccination()
	{
		return F_ID_VACCINATION;
	}
	
	public static function date()
	{
		return F_DATE;
	}
	
	public static function lot()
	{
		return F_LOT;
	}
	
	public static function status()
	{
		return F_STATUS;
	}
	
	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma instÃ¢ncia da memÃ³ria
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->idPerson			= $_request->getParam(HealthForm::idPerson());
		
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->idPerson			= $_request->getPost(HealthForm::idPerson());
			$this->pregnancy 		= trim($filter->filter($_request->getPost(HealthForm::pregnancy())));
			$this->met				= $_request->getPost(HealthForm::met());
			$this->pregnancySis		= trim($filter->filter($_request->getPost(HealthForm::pregnancySis())));
			$this->pregnancyBegin	= trim($filter->filter($_request->getPost(HealthForm::pregnancyBegin())));						
			$this->userDrug			= $_request->getPost(HealthForm::userDrug());
			$this->vaccine			= $_request->getPost(HealthForm::vaccine());
			$this->idHealthPlan 	= $_request->getPost(HealthForm::idHealthPlan());
			$this->healthPlan 		= trim($filter->filter($_request->getPost(HealthForm::healthPlan())));
			$this->checkedHealthPlan= $_request->getPost(HealthForm::checkedHealthPlan());
			$this->idTypeHealth		= $_request->getPost(HealthForm::idTypeHealth());
			$this->idUBS			= $_request->getPost(HealthForm::idUBS());
			$this->prontuary		= trim($filter->filter($_request->getPost(HealthForm::prontuary())));
			$this->entity			= $_request->getPost(HealthForm::entity());
			$this->idVaccine		= $_request->getPost(HealthForm::idVaccine());
			$this->idVaccination	= $_request->getPost(HealthForm::idVaccination());
			$this->date				= $_request->getPost(HealthForm::date());
			$this->lot				= trim($_request->getPost(HealthForm::lot()));
			$this->status			= $_request->getPost(HealthForm::status());
			
			if($this->idTypeHealth != null)
			{
				foreach($this->idTypeHealth as $idHealth)
				{	
					$uniqueHealth = array();
					$uniqueHealth[FHL_ID_FRAMEWORK_HEALTH] = $idHealth; 
					
					$descriptionHealth = $_request->getPost(HealthForm::descrTypeHealth()."_".$idHealth);
					if($descriptionHealth != null)
					{
						$uniqueHealth[FHL_FRAMEWORK_HEALTH_DESCRIPTION] = $_request->getPost(HealthForm::descrTypeHealth()."_".$idHealth);	
					}
					else
					{
						$uniqueHealth[FHL_FRAMEWORK_HEALTH_DESCRIPTION] = null;
					}
					
					$objectsHealth[] = $uniqueHealth;
				}	
			}
			
			$this->collFrameworkHealth = $objectsHealth;
		}
	}
	
	/**
	 * Getters and Setters
	 */
	 public function getIdPerson()
	 {
	 	return $this->idPerson;	
	 }
	 
	 public function getPregnancy()
	 {
	 	return $this->pregnancy;	
	 }
	 
	 public function getMet()
	 {
	 	return $this->met;	
	 }
	 
	 public function getPregnancySis()
	 {
	 	return $this->pregnancySis;	
	 }
	 
	 public function getPregnancyBegin()
	 {
	 	return $this->pregnancyBegin;	
	 }
	 
	 public function getUserDrug()
	 {
	 	return $this->userDrug;	
	 }
	 
	 public function getVaccine()
	 {
	 	return $this->vaccine;	
	 }
	 
	 public function getIdVaccine()
	 {
	 	return $this->idVaccine;	
	 }
	 
	 public function getHealthPlan()
	 {
	 	return $this->healthPlan;	
	 }
	 
	 public function getIdHealthPlan()
	 {
	 	return $this->idHealthPlan;	
	 }
	 
	 public function getCheckedHealthPlan()
	 {
	 	return $this->checkedHealthPlan;	
	 }
	 
	 public function getIdTypeHealth()
	 {
	 	return $this->idTypeHealth;	
	 }
	 
	 public function getDescrTypeHealth()
	 {
	 	return $this->descrTypeHealth;	
	 }
	 
	 public function getIdUBS()
	 {
	 	return $this->idUBS;	
	 }
	 
	 public function getCollFrameworkHealth()
	 {
	 	return $this->collFrameworkHealth;	
	 }
	 
	 public function getProntuary()
	 {
	 	return $this->prontuary;	
	 }
	 
	 public function getEntity()
	 {
	 	return $this->entity;	
	 }

	public function getIdVaccination()
	 {
	 	return $this->idVaccination;	
	 }
	 
	 public function getDate()
	 {
	 	return $this->date;	
	 }

	 public function getLot()
	 {
	 	return $this->lot;	
	 }
	 
	public function getStatus()
	 {
	 	return $this->status;	
	 }
	 
	 
	 
	 public function setIdPerson($idPerson)
	 {
	 	$this->idPerson = $idPerson;	
	 }
	 
	 public function setPregnancy($pregnancy)
	 {
	 	$this->pregnancy = $pregnancy;	
	 }
	 
	 public function setMet($met)
	 {
	 	$this->met = $met;	
	 }
	 
	 public function setPregnancySis($pregnancySis)
	 {
	 	$this->pregnancySis = $pregnancySis;	
	 }
	 
	 public function setPregnancyBegin($pregnancyBegin)
	 {
	 	$this->pregnancyBegin = $pregnancyBegin;	
	 }
	 
	 public function setUserDrug($userDrug)
	 {
	 	$this->userDrug = $userDrug;	
	 }
	 
	 public function setVaccine($vaccine)
	 {
	 	$this->vaccine = $vaccine;	
	 }

	 public function setIdHealthPlan($idHealthPlan)
	 {
	 	$this->idHealthPlan = $idHealthPlan;	
	 }
	 
	 public function setHealthPlan($healthPlan)
	 {
	 	$this->healthPlan = $healthPlan;	
	 }
	 
	 public function setCheckedHealthPlan($checkedHealthPlan)
	 {
	 	$this->checkedHealthPlan = $checkedHealthPlan;	
	 }
	 
	 public function setIdTypeHealth($idTypeHealth)
	 {
	 	$this->idTypeHealth = $idTypeHealth;	
	 }
	 
	 public function setDescrTypeHealth($descrTypeHealth)
	 {
	 	$this->descrTypeHealth = $descrTypeHealth;	
	 }
	 
	 public function setIdUBS($idUBS)
	 {
	 	$this->idUBS = $idUBS;	
	 }
	 
	 public function setCollFrameWorkHealth($collFrameworkHealth)
	 {
	 	$this->collFrameworkHealth = $collFrameworkHealth;	
	 }
	 
	 public function setProntuary($prontuary)
	 {
	 	$this->prontuary = $prontuary;	
	 }
	
	 public function setEntity($entity)
	 {
	 	$this->entity = $entity;	
	 }
	
	 public function setIdVaccine($idVaccine)
	 {
	 	$this->idVaccine = $idVaccine;	
	 }
	 
	 public function setIdVaccination($idVaccination)
	 {
	 	$this->idVaccination = $idVaccination;	
	 }
	 
	 public function setDate($date)
	 {
	 	$this->date = $date;	
	 }

	 public function setLot($lot)
	 {
	 	$this->lot = $lot;	
	 }

	 public function setStatus($status)
	 {
	 	$this->status = $status;	
	 }
}