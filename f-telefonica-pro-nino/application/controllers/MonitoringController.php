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
 * Anderson Tiago Marques  - S2it		   			  06/03/2012	                       Create file 
 * 
 */
require_once ('BasicController.php');
require_once ('AdditionalInformationBusiness.php');
class MonitoringController extends BasicController {
	
	/**
	 * Inicialização
	 */
	function init() {
		parent::init ();
		parent::setControllerResources ( 'Monitoring' );
		parent::setControllerHelp ( 'Monitoring' );
		
		$config = Zend_Registry::get ( CONFIG );
		
		Zend_Loader::loadClass ( 'Utils' );
		Zend_Loader::loadClass ( 'MonitoringForm' );
		Zend_Loader::loadClass ( 'NeighborhoodBusiness' );
		Zend_Loader::loadClass ( 'SchoolBusiness' );
		Zend_Loader::loadClass ( 'SocialProgramBusiness' );
		Zend_Loader::loadClass ( 'RepresentativeBusiness' );
		Zend_Loader::loadClass ( 'Representative' );
		Zend_Loader::loadClass ( 'MonitoringBusiness' );
		Zend_Loader::loadClass ( 'CategoryMonitoring', 'application/models/beans');
		
		$frm = new MonitoringForm ();
		$frm->assembleRequest ( $this->_request );
		$this->view->form = $frm;
	}
	
	function indexAction() {
		if ($this->getRequest ()->isPost ()) {
			if ($this->_hasParam ( "op" )) {
				$op = ( string ) $this->_getParam ( "op" );
				switch ($op) {
					case 'bairro' :
						$this->_redirect ( '/monitoring/neighborhood' );
						break;
					case 'beneficio' :
						$this->_redirect ( '/monitoring/benefit' );
						break;
					case 'escola' :
						$this->_redirect ( '/monitoring/school' );
						break;
					case 'representante' :
						$this->_redirect ( '/monitoring/representative' );
						break;
					case 'faixa' :
						$this->_redirect ( '/monitoring/agegroup' );
						break;
					case 'periodo' :
						$this->_redirect ( '/monitoring/attendanceperiod' );
						break;
				}
			} else {
				$this->view->assign ( "msg", "É obrigatório selecionar um único filtro." );
			}
		}
	}
	
	//Implementação - RF003 - Construir página Filtro bairro.
	function neighborhoodAction() {
		if ($this->getRequest ()->isPost ()) {
			if ($this->_hasParam ( "opcao" )) {
				$checks = $this->_getParam ( "check" );
				$opcao = $this->_getParam ( "opcao" );
				
				$count = false;
				
				if ($this->_hasParam ( "qtde" )) {
					$count = true;
				}
				
				if (count ( $checks ) < 1) {
					$this->view->assign ( "msg", "É obrigatório selecionar uma opção de filtro." );
				} else {
					switch ($opcao) {
						case 'view' :
							$admin = UserLogged::isAdministrator ();
							if ($admin === TRUE) {
								$this->view->assign ( "view", MonitoringBusiness::fetchNeighborhoor ( true, null, $checks, $count ) );
							} else {
								$entity = UserLogged::getEntityId ();
								$this->view->assign ( "view", MonitoringBusiness::fetchNeighborhoor ( false, $entity, $checks, $count ) );
							}
							$this->render ( 'viewneighborhoor' );
							break;
						case 'graphic' :
							$admin = UserLogged::isAdministrator ();
							if ($admin === TRUE) {
								$this->view->assign ( "checks", $checks );
								$neighborhood = array ();
								
								$qtdes = MonitoringBusiness::retPag ();
								$atual = ($this->_hasParam ( "pg" )) ? intval ( $this->_getParam ( "pg" ) ) : 1;
								$parChacks = array_chunk ( $checks, $qtdes );
								$counts = count ( $parChacks );
								$result = $parChacks [$atual - 1];
								$this->view->assign ( "atual", $atual );
								$this->view->assign ( "contar", $counts );
								
								foreach ( $result as $key => $value ) {
									$neighborhood [] = NeighborhoodBusiness::findForId ( $value );
								}
								
								$this->view->assign ( "neighborhoods", $neighborhood );
								$entitys = MonitoringBusiness::fetchNeighborhoor ( true, null, $result, true );
								
								$height = 80 + ((count ( $entitys ) * 20) * count ( $result ));
								$this->view->assign ( "height", $height );
								
								$this->view->assign ( "view", $entitys );
							} else {
								$this->view->assign ( "checks", $checks );
								$neighborhood = array ();
								
								$qtdes = MonitoringBusiness::retPag ();
								$atual = ($this->_hasParam ( "pg" )) ? intval ( $this->_getParam ( "pg" ) ) : 1;
								$parChacks = array_chunk ( $checks, $qtdes );
								$counts = count ( $parChacks );
								$result = $parChacks [$atual - 1];
								$this->view->assign ( "atual", $atual );
								$this->view->assign ( "contar", $counts );
								
								foreach ( $result as $key => $value ) {
									$neighborhood [] = NeighborhoodBusiness::findForId ( $value );
								}
								
								$this->view->assign ( "neighborhoods", $neighborhood );
								$entity = UserLogged::getEntityId ();
								$entitys = MonitoringBusiness::fetchNeighborhoor ( false, $entity, $result, true );
								
								$height = 80 + ((count ( $entitys ) * 20) * count ( $result ));
								$this->view->assign ( "height", $height );
								
								$this->view->assign ( "view", $entitys );
							
							}
							$this->render ( 'chartneighborhood' );
							break;
						case 'csv' :
							$admin = UserLogged::isAdministrator ();
							if ($admin === TRUE) {
								$this->view->assign ( "view", MonitoringBusiness::fetchNeighborhoor ( true, null, $checks, $count ) );
							} else {
								$entity = UserLogged::getEntityId ();
								$this->view->assign ( "view", MonitoringBusiness::fetchNeighborhoor ( false, $entity, $checks, $count ) );
							}
							$this->render ( 'csvneighborhoor' );
							break;
					}
				}
			}
		}
		//carrega todos os bairros que possuem vinculo com um pessoa que possui atendimento 
		$this->view->assign ( "neighborhoods", NeighborhoodBusiness::fetchAllForPerson () );
	}
	
	//Implementação - RF004 - Construir página Filtro escola.
	function schoolAction() {
		if ($this->getRequest ()->isPost ()) {
			if ($this->_hasParam ( "opcao" )) {
				$checks = $this->_getParam ( "check" );
				$opcao = $this->_getParam ( "opcao" );
				
				$count = false;
				
				if ($this->_hasParam ( "qtde" )) {
					$count = true;
				}
				
				if (count ( $checks ) < 1) {
					$this->view->assign ( "msg", "É obrigatório selecionar uma opção de filtro." );
				} else {
					switch ($opcao) {
						case 'view' :
							$admin = UserLogged::isAdministrator ();
							if ($admin === TRUE) {
								$this->view->assign ( "view", MonitoringBusiness::fetchSchool ( true, null, $checks, $count ) );
							} else {
								$entity = UserLogged::getEntityId ();
								$this->view->assign ( "view", MonitoringBusiness::fetchSchool ( false, $entity, $checks, $count ) );
							}
							$this->render ( 'viewschool' );
							break;
						case 'graphic' :
							$admin = UserLogged::isAdministrator ();
							if ($admin === TRUE) {
								$this->view->assign ( "checks", $checks );
								$school = array ();
								
								$qtdes = MonitoringBusiness::retPag ();
								$atual = ($this->_hasParam ( "pg" )) ? intval ( $this->_getParam ( "pg" ) ) : 1;
								$parChacks = array_chunk ( $checks, $qtdes );
								$counts = count ( $parChacks );
								$result = $parChacks [$atual - 1];
								$this->view->assign ( "atual", $atual );
								$this->view->assign ( "contar", $counts );
								
								foreach ( $result as $key => $value ) {
									$school [] = SchoolBusiness::findForId ( $value );
								}
								
								$this->view->assign ( "schools", $school );
								$entitys = MonitoringBusiness::fetchSchool ( true, null, $result, true );
								
								$height = 80 + ((count ( $entitys ) * 20) * count ( $result ));
								$this->view->assign ( "height", $height );
								
								$this->view->assign ( "view", $entitys );
							} else {
								$this->view->assign ( "checks", $checks );
								$school = array ();
								
								$qtdes = MonitoringBusiness::retPag ();
								$atual = ($this->_hasParam ( "pg" )) ? intval ( $this->_getParam ( "pg" ) ) : 1;
								$parChacks = array_chunk ( $checks, $qtdes );
								$counts = count ( $parChacks );
								$result = $parChacks [$atual - 1];
								$this->view->assign ( "atual", $atual );
								$this->view->assign ( "contar", $counts );
								
								foreach ( $result as $key => $value ) {
									$school [] = SchoolBusiness::findForId ( $value );
								}
								
								$this->view->assign ( "schools", $school );
								
								$entity = UserLogged::getEntityId ();
								$entitys = MonitoringBusiness::fetchSchool ( false, $entity, $result, true );
								
								$height = 80 + ((count ( $entitys ) * 20) * count ( $result ));
								$this->view->assign ( "height", $height );
								
								$this->view->assign ( "view", $entitys );
							
							}
							$this->render ( 'chartschool' );
							break;
						case 'csv' :
							$admin = UserLogged::isAdministrator ();
							if ($admin === TRUE) {
								$this->view->assign ( "view", MonitoringBusiness::fetchSchool ( true, null, $checks, $count ) );
							} else {
								$entity = UserLogged::getEntityId ();
								$this->view->assign ( "view", MonitoringBusiness::fetchSchool ( false, $entity, $checks, $count ) );
							}
							$this->render ( 'csvschool' );
							break;
					}
				}
			}
		}
		//carrega todas as escolas cadastradas
		$this->view->allSchool = SchoolBusiness::load ();
	}
	
	//Implementação - RF006 - Construir página Filtro benefício.
	function benefitAction() {
		if($this->getRequest()->isPost()){
			if ($this->_hasParam("opcao")){
				$checks = $this->_getParam("check");
				$opcao = $this->_getParam("opcao");
				
				$count = false;
				
				if ($this->_hasParam("qtde")){
					$count = true;
				}
				
				if ( count($checks) < 1)
				{
					$this->view->assign("msg", "É obrigatório selecionar uma opção de filtro.");
				}
				else
				{
					switch ($opcao){
						case 'view':
							$admin = UserLogged::isAdministrator();
							if($admin === TRUE)
							{
								$this->view->assign("view", MonitoringBusiness::fetchBenefit(true, null, $checks, $count));
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$this->view->assign("view", MonitoringBusiness::fetchBenefit(false, $entity, $checks, $count));
							}
							$this->render('viewbenefit');
							break;
						case 'graphic':
							$admin = UserLogged::isAdministrator();
							
							$this->view->assign("checks", $checks);
							$benefit = array();
							
							$qtdes = MonitoringBusiness::retPag();
							$atual = ($this->_hasParam("pg")) ? intval($this->_getParam("pg")) : 1;
							$parChacks = array_chunk($checks, $qtdes);
							$counts    = count($parChacks);
							$result    = $parChacks[$atual-1];
							$this->view->assign("atual", $atual);
							$this->view->assign("contar", $counts);
							
							foreach ( $result as $key => $value ) {
								$ben = SocialProgramBusiness::loadSocialPrograms($value)->toArray();
								$benefit[] = $ben[0];
							}
								
							$this->view->assign("benefits", $benefit);
							
							if($admin === TRUE)
							{
								$entitys = MonitoringBusiness::fetchBenefit(true, null, $result, true);

								$height = 80 + ((count($entitys) * 20) * count($result));
								$this->view->assign("height", $height);
								
								$this->view->assign("view", $entitys);
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$entitys = MonitoringBusiness::fetchBenefit(false, $entity, $result, true);
								
								$height = 80 + ((count($entitys) * 20) * count($result));
								$this->view->assign("height", $height);
								
								$this->view->assign("view", $entitys);
							}
							$this->render('chartbenefit');
							break;
						case 'csv':
							$admin = UserLogged::isAdministrator();
							if($admin === TRUE)
							{
								$this->view->assign("view", MonitoringBusiness::fetchBenefit(true, null, $checks, $count));
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$this->view->assign("view", MonitoringBusiness::fetchBenefit(false, $entity, $checks, $count));
							}
							$this->render('csvbenefit');
							break;
					}
				}
			}
		}
		//carrega todos os programas cadastrados
		$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms ();
	}
	
	//Implementação - RF007 - Construir página Filtro representante legal.
	function representativeAction() {
		if($this->getRequest()->isPost()){
			if ($this->_hasParam("opcao"))
			{
				$checks = $this->_getParam("check");
				$opcao = $this->_getParam("opcao");
				
				$count = false;
				
				if ($this->_hasParam("qtde")){
					$count = true;
				}
				
				if ( count($checks) < 1)
				{
					$this->view->assign("msg", "É obrigatório selecionar uma opção de filtro.");
				}
				else
				{
					switch ($opcao){
						case 'view':
							$admin = UserLogged::isAdministrator();
							if($admin === TRUE)
							{
								$this->view->assign("view", MonitoringBusiness::fetchRepresentative(true, null, $checks, $count));
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$this->view->assign("view", MonitoringBusiness::fetchRepresentative(false, $entity, $checks, $count));
							}
							$this->render('viewrepresentative');
							break;
						case 'graphic':
							$admin = UserLogged::isAdministrator();
							
							$this->view->assign("checks", $checks);
							$representative = array();
							
							$qtdes = MonitoringBusiness::retPag();
							$atual = ($this->_hasParam("pg")) ? intval($this->_getParam("pg")) : 1;
							$parChacks = array_chunk($checks, $qtdes);
							$counts    = count($parChacks);
							$result    = $parChacks[$atual-1];
							$this->view->assign("atual", $atual);
							$this->view->assign("contar", $counts);
							
							foreach ( $result as $key => $value ) {
								$representative[] = RepresentativeBusiness::findForId($value);
							}
								
							$this->view->assign("representatives", $representative);
							
							if($admin === TRUE)
							{
								$entitys = MonitoringBusiness::fetchRepresentative(true, null, $result, true);

								$height = 80 + ((count($entitys) * 20) * count($result));
								$this->view->assign("height", $height);
								
								$this->view->assign("view", $entitys);
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$entitys = MonitoringBusiness::fetchRepresentative(false, $entity, $result, true);
								
								$height = 80 + ((count($entitys) * 20) * count($result));
								$this->view->assign("height", $height);
								
								$this->view->assign("view", $entitys);
							}
							$this->render('chartrepresentative');
							break;
						case 'csv':
							$admin = UserLogged::isAdministrator();
							if($admin === TRUE)
							{
								$this->view->assign("view", MonitoringBusiness::fetchRepresentative(true, null, $checks, $count));
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$this->view->assign("view", MonitoringBusiness::fetchRepresentative(false, $entity, $checks, $count));
							}
							$this->render('csvrepresentative');
							break;
					}
				}
			}
		}
		//carrega todos os representantes legais
		$this->view->representatives = RepresentativeBusiness::fetchAll ();
	}
	
	//Implementação - RF005 - Construir página Filtro faixa etária.
	function ageGroupAction() {
		if($this->getRequest()->isPost()){
			if ($this->_hasParam("opcao"))
			{
				$checks = $this->_getParam("check");
				$opcao = $this->_getParam("opcao");
				
				$count = false;
				
				if ($this->_hasParam("qtde")){
					$count = true;
				}
				
				if ( count($checks) < 1)
				{
					$this->view->assign("msg", "É obrigatório selecionar uma opção de filtro.");
				}
				else
				{
					switch ($opcao){
						case 'view':
							$admin = UserLogged::isAdministrator();
							if($admin === TRUE)
							{
								$this->view->assign("view", MonitoringBusiness::fetchAgeGroup(true, null, $checks, $count));
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$this->view->assign("view", MonitoringBusiness::fetchAgeGroup(false, $entity, $checks, $count));
							}
							$this->render('viewagegroup');
							break;
						case 'graphic':
							$admin = UserLogged::isAdministrator();
							
							$this->view->assign("checks", $checks);
							$representative = array();
							
							$qtdes = MonitoringBusiness::retPag();
							$atual = ($this->_hasParam("pg")) ? intval($this->_getParam("pg")) : 1;
							$parChacks = array_chunk($checks, $qtdes);
							$counts    = count($parChacks);
							$result    = $parChacks[$atual-1];
							$this->view->assign("atual", $atual);
							$this->view->assign("contar", $counts);
							
							foreach ( $result as $key => $value ) {
								$ageGroup = AdditionalInformationBusiness::loadAgeGroup($value);
								$ageGroup = $ageGroup[0];
								$agegroups[] = $ageGroup;
							}
								
							$this->view->assign("agegroups", $agegroups);
							
							if($admin === TRUE)
							{
								$entitys = MonitoringBusiness::fetchAgeGroup(true, null, $result, true);

								$height = 80 + ((count($entitys) * 20) * count($result));
								$this->view->assign("height", $height);
								
								$this->view->assign("view", $entitys);
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$entitys = MonitoringBusiness::fetchAgeGroup(false, $entity, $result, true);
								
								$height = 80 + ((count($entitys) * 20) * count($result));
								$this->view->assign("height", $height);
								
								$this->view->assign("view", $entitys);
							}
							$this->render('chartagegroup');
							break;
						case 'csv':
							$admin = UserLogged::isAdministrator();
							if($admin === TRUE)
							{
								$this->view->assign("view", MonitoringBusiness::fetchAgeGroup(true, null, $checks, $count));
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$this->view->assign("view", MonitoringBusiness::fetchAgeGroup(false, $entity, $checks, $count));
							}
							$this->render('csvagegroup');
							break;
					}
				}
			}
		}
		$this->view->ageGroup = AdditionalInformationBusiness::loadAllAgeGroup ();
	}
	
	//Implementação - RF008 - Construir página Filtro período atendimento.
	function attendancePeriodAction() {
		if ($this->getRequest ()->isPost ()) {
			
			$error = false;
			$initial = $this->_getParam("initial" );
			$final = $this->_getParam("final" );
			$initEx = explode ("/", $initial );
			$finalEx = explode ( "/", $final );
			
			$year = date ( "Y" );
			$validInit = checkdate ( $initEx [1], $initEx [0], $initEx [2] );
			$validFinal = checkdate ( $finalEx [1], $finalEx [0], $finalEx [2] );
			
			if ($validInit == false && $validFinal == false) {
				$this->view->value = $this->_getParam ( "initial" );
				$this->view->valuef = $this->_getParam ( "final" );
				$this->view->assign ( 'msg', 'Datas inválidas' );
				$error = true;
			} 
			
			else if ($validInit == false && $validFinal == true) {
				$this->view->value = $this->_getParam ( "initial" );
				$this->view->valuef = $this->_getParam ( "final" );
				$this->view->assign ( 'msg', 'Data inicial inválida' );
				$error = true;
			} 

			else if ($validInit == true && $validFinal == false) {
				$this->view->value = $this->_getParam ( "initial" );
				$this->view->valuef = $this->_getParam ( "final" );
				$this->view->assign ( 'msg', 'Data final inválida' );
				$error = true;
			} 

			else if ($initial != "" && $final != "") {
				if ((($initEx [1] == 4) || ($initEx [1] == 6) || ($initEx [1] == 9) || ($initEx [1] == 11)) && ($initEx [0] > 30)) {
					$this->view->value = $this->_getParam ( "initial" );
					$this->view->valuef = $this->_getParam ( "final" );
					$this->view->assign ( 'msg', 'Data inicial inválida' );
					$error = true;
				}
				
				if ((($finalEx [1] == 4) || ($finalEx [1] == 6) || ($finalEx [1] == 9) || ($finalEx [1] == 11)) && ($finalEx [0] > 30)) {
					$this->view->value = $this->_getParam ( "initial" );
					$this->view->valuef = $this->_getParam ( "final" );
					$this->view->assign ( 'msg', 'Data final inválida' );
					$error = true;
				}
				
				if ((($finalEx [1] == 4) || ($finalEx [1] == 6) || ($finalEx [1] == 9) || ($finalEx [1] == 11)) && ($finalEx [0] > 30) && (($initEx [1] == 4) || ($initEx [1] == 6) || ($initEx [1] == 9) || ($initEx [1] == 11)) && ($initEx [0] > 30)) {
					$this->view->value = $this->_getParam ( "initial" );
					$this->view->valuef = $this->_getParam ( "final" );
					$this->view->assign ( 'msg', 'Datas inválidas' );
					$error = true;
				}
				if ($initEx [1] > 12) {
					$this->view->value = $this->_getParam ( "initial" );
					$this->view->valuef = $this->_getParam ( "final" );
					$this->view->assign ( 'msg', 'Data inicial inválida' );
					$error = true;
				}
				
				if ($finalEx [1] > 12) {
					$this->view->value = $this->_getParam ( "initial" );
					$this->view->valuef = $this->_getParam ( "final" );
					$this->view->assign ( 'msg', 'Data final inválida' );
					$error = true;
				}
				
				if ($initEx [2] . $initEx [1] . $initEx [0] > $finalEx [2] . $finalEx [1] . $finalEx [0]) {
					$this->view->valuef = $this->_getParam ( "final" );
					$this->view->value = $this->_getParam ( "initial" );
					$this->view->assign ( 'msg', 'Data final deve ser maior que inicial.' );
					$error = true;
				}
			
			} else if ($initial != "" && $final == "") {
				$this->view->value = $this->_getParam ( "initial" );
				$this->view->assign ( 'msg', 'É obrigatório preencher o campo "Data Final".' );
				$error = true;
			} else if ($initial == "" && $final != "") {
				$this->view->valuef = $this->_getParam ( "final" );
				$this->view->assign ( 'msg', 'É obrigatório preencher o campo "Data Inicial".' );
				$error = true;
			} else if (strlen ( $initial ) > 10 || strlen ( $final ) > 10) {
				$this->view->valuef = $this->_getParam ( "final" );
				$this->view->value = $this->_getParam ( "initial" );
				$this->view->assign ( 'msg', 'As datas devem conter no máximo 10 dígitos.' );
				$error = true;
			} else {
				$this->view->assign ( "msg", "É obrigatório preencher os campos de datas." );
				$error = true;
			}
						
			if ($error == false) {
				//Formatando data yyyy-mm-dd
				$initial = $initEx[2].'-'.$initEx[1].'-'.$initEx[0].' 00:00:00';
				$final = $finalEx[2].'-'.$finalEx[1].'-'.$finalEx[0].' 23:59:59';
			
				if ($this->_hasParam("opcao"))
				{
					$opcao = $this->_getParam("opcao");
					
					$count = false;
					
					if ($this->_hasParam("qtde")){
						$count = true;
					}
					
					$this->view->assign("initial", $this->_getParam("initial"));
					$this->view->assign("final", $this->_getParam("final"));
							
					switch ($opcao){
						case 'view':
							$admin = UserLogged::isAdministrator();
							if($admin === TRUE)
							{
								$this->view->assign("view", MonitoringBusiness::fetchAttendancePeriod(true, null, $initial, $final, $count));
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$this->view->assign("view", MonitoringBusiness::fetchAttendancePeriod(false, $entity, $initial, $final, $count));
							}
							$this->render('viewattendanceperiod');
							break;
						case 'graphic':
							$admin = UserLogged::isAdministrator();
							
							$representative = array();
							
							$categorys = array ();
							for ( $index = 0, $max_count = 2; $index < $max_count; $index++ ) {
								$categorym = new CategoryMonitoring();
								
								$cat = "";
								if ($index == 0){
									$cat = "Em Andamento";
								}else if($index == 1){
									$cat = "Encerrado";
								}
													
								$categorym->setIdCategory($index)->setName($cat);
								$categorys[] = $categorym;
							}
								
							$this->view->assign("attendanceperiods", $categorys);
							
							if($admin === TRUE)
							{
								$entitys = MonitoringBusiness::fetchAttendancePeriod(true, null, $initial, $final, true);

								$height = 80 + ((count($entitys) * 20) * 2);
								$this->view->assign("height", $height);
								
								$this->view->assign("view", $entitys);
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$entitys = MonitoringBusiness::fetchAttendancePeriod(false, $entity, $initial, $final, true);
								
								$height = 80 + ((count($entitys) * 20) * 2);
								$this->view->assign("height", $height);
								
								$this->view->assign("view", $entitys);
							}
							$this->render('chartattendanceperiod');
							break;
						case 'csv':
							$admin = UserLogged::isAdministrator();
							if($admin === TRUE)
							{
								$this->view->assign("view", MonitoringBusiness::fetchAttendancePeriod(true, null, $initial, $final, $count));
							}
							else
							{
								$entity = UserLogged::getEntityId();
								$this->view->assign("view", MonitoringBusiness::fetchAttendancePeriod(false, $entity, $initial, $final, $count));
							}
							$this->render('csvattendanceperiod');
							break;
					}
				}
			}
		}
	}

}
?>
